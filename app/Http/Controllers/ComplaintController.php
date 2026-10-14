<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreComplaintRequest;
use App\Http\Requests\UpdateComplaintRequest;
use App\Models\Branch;
use App\Models\Complaint;
use App\Models\Department;
use App\Models\Feedback;
use App\Models\User;
use App\Services\AttachmentUploadService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function __construct(private AttachmentUploadService $attachments)
    {
    }

    public function index(Request $request): View
    {
        $user = $request->user();
        $complaints = Complaint::with(['branch', 'department', 'submitter', 'assignee', 'attachments']);

        if ($user->isDepartmentScopedReviewer()) {
            $complaints->where('department_id', $user->department_id);
        } elseif ($user->hasRole('Supervisor') && $user->branch_id) {
            $complaints->where('branch_id', $user->branch_id);
        } elseif ($user->hasRole('Employee') || $user->hasRole('Customer')) {
            $complaints->where(function ($query) use ($user): void {
                $query->where('submitted_by_user_id', $user->id)
                    ->orWhere('assigned_to_user_id', $user->id);
            });
        }

        return view('complaints.index', [
            'complaints' => $complaints->latest()->paginate(10),
            'branches' => $this->branchesForUser($user),
            'departments' => $this->departmentsForUser($user),
            'assignableUsers' => $this->assignableUsersFor($user),
        ]);
    }

    public function create(Request $request): View
    {
        $this->authorize('create', Complaint::class);

        return view('complaints.create', [
            'branches' => $this->branchesForUser($request->user()),
            'departments' => $this->departmentsForUser($request->user()),
            'assignableUsers' => $this->assignableUsersFor($request->user()),
            'user' => $request->user(),
        ]);
    }

    public function store(StoreComplaintRequest $request): RedirectResponse
    {
        $this->authorize('create', Complaint::class);

        $payload = $request->validated();

        if (! $request->user()->can('assign complaints')) {
            $payload['assigned_to_user_id'] = null;
        }

        if ($request->user()->hasRole('Customer') && ($payload['sentiment'] ?? null) === 'positive') {
            $feedback = Feedback::create([
                'branch_id' => $payload['branch_id'],
                'department_id' => $payload['department_id'] ?? null,
                'user_id' => $request->user()->id,
                'name' => $payload['customer_name'],
                'email' => $payload['customer_email'] ?? null,
                'subject' => 'Positive feedback submission',
                'type' => 'customer',
                'status' => 'submitted',
                'message' => $payload['message'],
                'sentiment' => 'positive',
            ]);

            $this->attachments->uploadMany($feedback, $request->file('attachments', []), $request->user());

            return redirect()->route('feedback.show', ['feedback' => $feedback->id])
                ->with('status', 'Feedback submitted successfully.');
        }

        $complaint = Complaint::create([
            ...$payload,
            'submitted_by_user_id' => $request->user()->id,
            'status' => 'open',
            'allow_public_reopen' => $request->boolean('allow_public_reopen', true),
        ]);

        $this->attachments->uploadMany($complaint, $request->file('attachments', []), $request->user());

        return redirect()->route('complaints.show', $complaint)
            ->with('status', 'Complaint submitted successfully.');
    }

    public function show(Complaint $complaint): View
    {
        $this->authorize('view', $complaint);

        $complaint->load(['branch', 'department', 'submitter', 'assignee', 'slaEscalations', 'attachments.uploadedBy']);

        return view('complaints.show', [
            'complaint' => $complaint,
            'departments' => $this->departmentsForUser(request()->user()),
            'assignableUsers' => $this->assignableUsersFor(request()->user()),
        ]);
    }

    public function edit(Complaint $complaint): View
    {
        $this->authorize('update', $complaint);

        return view('complaints.edit', [
            'complaint' => $complaint,
            'branches' => $this->branchesForUser(request()->user()),
            'departments' => $this->departmentsForUser(request()->user()),
            'assignableUsers' => $this->assignableUsersFor(request()->user()),
        ]);
    }

    public function update(UpdateComplaintRequest $request, Complaint $complaint): RedirectResponse
    {
        $this->authorize('update', $complaint);

        $payload = $request->validated();

        if (! $request->user()->can('assign complaints')) {
            $payload['assigned_to_user_id'] = $complaint->assigned_to_user_id;
        }

        if (! $request->user()->can('manage complaints')) {
            $payload['status'] = $complaint->status;
        }

        $payload['allow_public_reopen'] = $request->boolean('allow_public_reopen', $complaint->allow_public_reopen);
        $payload['resolved_at'] = in_array($payload['status'], ['resolved', 'closed'], true)
            ? ($complaint->resolved_at ?? now())
            : null;
        $payload['closed_at'] = in_array($payload['status'], ['resolved', 'closed'], true) ? now() : null;

        $complaint->update($payload);
        $this->attachments->uploadMany($complaint, $request->file('attachments', []), $request->user());

        return redirect()->route('complaints.show', $complaint)
            ->with('status', 'Complaint updated successfully.');
    }

    public function destroy(Complaint $complaint): RedirectResponse
    {
        $this->authorize('delete', $complaint);

        $complaint->delete();

        return redirect()->route('complaints.index')->with('status', 'Complaint archived successfully.');
    }

    public function assign(Request $request, Complaint $complaint): RedirectResponse
    {
        $this->authorize('update', $complaint);
        abort_unless($request->user()->can('assign complaints'), 403);

        $validated = $request->validate([
            'assigned_to_user_id' => ['required', 'exists:users,id'],
        ]);

        $complaint->update([
            'assigned_to_user_id' => $validated['assigned_to_user_id'],
            'status' => 'in_progress',
        ]);

        return back()->with('status', 'Complaint assigned successfully.');
    }

    public function close(Request $request, Complaint $complaint): RedirectResponse
    {
        $this->authorize('update', $complaint);
        abort_unless($request->user()->can('close complaints'), 403);

        $complaint->update([
            'status' => 'closed',
            'resolved_at' => $complaint->resolved_at ?? now(),
            'closed_at' => now(),
        ]);

        return back()->with('status', 'Complaint closed successfully.');
    }

    public function reopen(Request $request, Complaint $complaint): RedirectResponse
    {
        $this->authorize('update', $complaint);
        abort_unless($request->user()->can('reopen complaints'), 403);

        $complaint->update([
            'status' => 'reopened',
            'resolved_at' => null,
            'closed_at' => null,
        ]);

        return back()->with('status', 'Complaint reopened successfully.');
    }

    private function departmentsForUser($user)
    {
        return Department::with('branch')
            ->when($user->isDepartmentScopedReviewer(), fn ($query) => $query->whereKey($user->department_id))
            ->when($user->isBranchScopedSupervisor() && $user->branch_id, fn ($query) => $query->where('branch_id', $user->branch_id))
            ->when(($user->hasRole('Employee') || $user->hasRole('Customer')) && $user->branch_id, fn ($query) => $query->where('branch_id', $user->branch_id))
            ->orderBy('name')
            ->get();
    }

    private function branchesForUser($user)
    {
        return Branch::query()
            ->when($user->isScopedReviewer() && $user->branch_id, fn ($query) => $query->whereKey($user->branch_id))
            ->when(($user->hasRole('Employee') || $user->hasRole('Customer')) && $user->branch_id, fn ($query) => $query->whereKey($user->branch_id))
            ->orderBy('name')
            ->get();
    }

    private function assignableUsersFor($user)
    {
        return User::assignableUsers()
            ->when($user->isDepartmentScopedReviewer(), fn ($query) => $query->where('department_id', $user->department_id))
            ->when($user->isBranchScopedSupervisor() && $user->branch_id, fn ($query) => $query->where('branch_id', $user->branch_id))
            ->orderBy('name')
            ->get();
    }
}
