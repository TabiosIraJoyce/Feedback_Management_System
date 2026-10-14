<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBranchRequest;
use App\Http\Requests\UpdateBranchRequest;
use App\Models\Branch;
// BusinessType removed — no longer used
use App\Services\QrCodeService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class BranchController extends Controller
{
    public function index(): View
    {
        $branches = Branch::with('qrCode')
            ->withCount(['users', 'complaints', 'feedbackEntries'])
            ->when(request('search'), fn ($query, $search) => $query->where(fn ($query) => $query
                ->where('name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%")
            ))
            ->when(request('business_type'), fn ($query, $type) => $query->whereNull('business_type_id'))
            ->when(request('status'), fn ($query, $status) => $query->where('status', $status))
            ->orderBy('name')
            ->paginate(15);

        return view('branches.index', [
            'branches' => $branches,
            'businessTypes' => [],
        ]);
    }

    public function __construct(private QrCodeService $qrCodes)
    {
    }

    public function store(StoreBranchRequest $request): RedirectResponse
    {
        $branch = Branch::create($request->validated());

        if ($request->hasFile('logo')) {
            $branch->update([
                'logo' => $request->file('logo')->store('branch-logos', 'public'),
            ]);
        }

        $this->qrCodes->syncForBranch($branch);

        return back()->with('status', 'Branch created successfully.');
    }

    public function update(UpdateBranchRequest $request, Branch $branch): RedirectResponse
    {
        $branch->update($request->validated());

        if ($request->hasFile('logo')) {
            Storage::disk('public')->delete($branch->logo);
            $branch->update([
                'logo' => $request->file('logo')->store('branch-logos', 'public'),
            ]);
        }

        $this->qrCodes->syncForBranch($branch->fresh());

        return back()->with('status', 'Branch updated successfully.');
    }

    public function toggleStatus(Branch $branch): RedirectResponse
    {
        $branch->update([
            'status' => $branch->status === 'active' ? 'inactive' : 'active',
        ]);

        $this->qrCodes->syncForBranch($branch->fresh());

        return back()->with('status', 'Branch status updated successfully.');
    }

    public function destroy(Branch $branch): RedirectResponse
    {
        $branch->delete();

        return back()->with('status', 'Branch deleted successfully.');
    }
}
