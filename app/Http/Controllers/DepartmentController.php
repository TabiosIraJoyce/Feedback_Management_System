<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Models\Branch;
use App\Models\Department;
use App\Services\QrCodeService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class DepartmentController extends Controller
{
    public function __construct(private QrCodeService $qrCodes)
    {
    }

    public function index(): View
    {
        $departments = Department::with(['branch', 'qrCode'])
            ->withCount(['feedbackTickets', 'formSubmissions'])
            ->when(request('search'), function ($query, $search) {
                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhere('qr_token', 'like', "%{$search}%");
                });
            })
            ->when(request('branch'), fn ($query, $branch) => $query->where('branch_id', $branch))
            ->when(request('status'), fn ($query, $status) => $query->where('status', $status))
            ->orderBy('name')
            ->paginate(15);

        return view('departments.index', [
            'departments' => $departments,
            'branches' => Branch::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(StoreDepartmentRequest $request): RedirectResponse
    {
        $department = Department::create($request->safe()->except('logo'));

        if ($request->hasFile('logo')) {
            $department->update([
                'logo' => $request->file('logo')->store('department-logos', 'public'),
            ]);
        }

        $this->qrCodes->syncForDepartment($department->fresh('branch'));

        activity()
            ->causedBy($request->user())
            ->performedOn($department)
            ->withProperties(['branch_id' => $department->branch_id])
            ->log('Department created.');

        return back()->with('status', 'Department created successfully.');
    }

    public function update(UpdateDepartmentRequest $request, Department $department): RedirectResponse
    {
        $department->update($request->safe()->except('logo'));

        if ($request->hasFile('logo')) {
            Storage::disk('public')->delete($department->logo);
            $department->update([
                'logo' => $request->file('logo')->store('department-logos', 'public'),
            ]);
        }

        $this->qrCodes->syncForDepartment($department->fresh('branch'));

        activity()
            ->causedBy($request->user())
            ->performedOn($department)
            ->withProperties(['branch_id' => $department->branch_id])
            ->log('Department updated.');

        return back()->with('status', 'Department updated successfully.');
    }

    public function toggleStatus(Department $department): RedirectResponse
    {
        $department->update([
            'status' => $department->status === 'active' ? 'inactive' : 'active',
        ]);

        $this->qrCodes->syncForDepartment($department->fresh('branch'));

        activity()
            ->causedBy(request()->user())
            ->performedOn($department)
            ->withProperties(['status' => $department->status])
            ->log('Department status updated.');

        return back()->with('status', 'Department status updated successfully.');
    }

    public function destroy(Department $department): RedirectResponse
    {
        activity()
            ->causedBy(request()->user())
            ->performedOn($department)
            ->withProperties(['branch_id' => $department->branch_id])
            ->log('Department deleted.');

        $department->delete();

        return back()->with('status', 'Department deleted successfully.');
    }
}
