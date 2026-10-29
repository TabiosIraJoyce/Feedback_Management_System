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
use App\Services\ComplaintWorkflowService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function __construct(
        private AttachmentUploadService $attachments,
        private ComplaintWorkflowService $workflow,
    )
    {
    }

    public function index(Request $request): View
    {
        $user = $request->user();
        $statusOptions = [
            'open' => __('Open'),
            'pending' => __('Pending'),
            'in_progress' => __('In Progress'),
            'escalated' => __('Escalated'),
            'resolved' => __('Resolved'),
            'closed' => __('Closed'),
            'reopened' => __('Reopened'),
        ];
        $selectedStatus = $request->query('status');
        $filters = $request->validate([
            'branch' => ['nullable', 'integer', 'exists:branches,id'],
            'department' => ['nullable', 'integer', 'exists:departments,id'],
            'category' => ['nullable', 'string', 'max:100'],
            'escalation_level' => ['nullable', 'integer', 'in:0,1,2'],
            'due' => ['nullable', 'string', 'in:overdue,soon'],
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date', 'after_or_equal:from_date'],
        ]);
        $complaints = Complaint::with(['branch', 'department', 'submitter', 'assignee', 'attachments']);
        $this->applyComplaintScope($complaints, $user);

        if (! empty($filters['branch'])) {
            $complaints->where('branch_id', $filters['branch']);
        }
        if (! empty($filters['department'])) {
            $complaints->where('department_id', $filters['department']);
        }

        if (is_string($selectedStatus) && array_key_exists($selectedStatus, $statusOptions)) {
            if ($selectedStatus === 'pending') {
                $complaints->whereIn('status', ['open', 'reopened']);
            } elseif ($selectedStatus === 'resolved') {
                $complaints->whereIn('status', ['resolved', 'closed']);
            } else {
                $complaints->where('status', $selectedStatus);
            }
        } else {
            $selectedStatus = '';
        }

        if (! empty($filters['category'])) {
            $complaints->where('category', $filters['category']);
        }
        if (array_key_exists('escalation_level', $filters) && $filters['escalation_level'] !== null) {
            $complaints->where('escalation_level', (int) $filters['escalation_level']);
        }
        if (! empty($filters['from_date'])) {
            $complaints->whereDate('created_at', '>=', $filters['from_date']);
        }
        if (! empty($filters['to_date'])) {
            $complaints->whereDate('created_at', '<=', $filters['to_date']);
        }
        if (($filters['due'] ?? null) === 'overdue') {
            $complaints->whereNotNull('due_at')->where('due_at', '<', now())->whereNotIn('status', ['resolved', 'closed']);
        } elseif (($filters['due'] ?? null) === 'soon') {
            $complaints->whereNotNull('due_at')->whereBetween('due_at', [now(), now()->addHours(8)])->whereNotIn('status', ['resolved', 'closed']);
        }

        return view('complaints.index', [
            'complaints' => $complaints->latest()->paginate(10)->withQueryString(),
            'statusOptions' => $statusOptions,
            'selectedStatus' => $selectedStatus,
            'selectedCategory' => $request->query('category', ''),
            'selectedEscalationLevel' => $request->query('escalation_level', ''),
            'categories' => Complaint::query()->select('category')->distinct()->orderBy('category')->pluck('category')->filter()->values(),
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
        unset($payload['assigned_to_user_id']);

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
        $this->workflow->assignOnSubmission($complaint);

        $this->attachments->uploadMany($complaint, $request->file('attachments', []), $request->user());

        return redirect()->route('complaints.show', $complaint)
            ->with('status', 'Complaint submitted successfully.');
    }

    public function show(Complaint $complaint): View
    {
        $this->authorize('view', $complaint);

        $complaint->load(['branch', 'department', 'submitter', 'assignee', 'slaEscalations', 'statusHistory.actor', 'statusHistory.fromAssignee', 'statusHistory.toAssignee', 'attachments.uploadedBy']);
        $canHandleComplaint = $this->workflow->isCurrentHandler($complaint, request()->user());
        $canSeeInternalHistory = request()->user()->hasRole(['Admin', 'Super Admin']) || $canHandleComplaint;
        if (! $canSeeInternalHistory) {
            $complaint->setRelation('statusHistory', $complaint->statusHistory->where('visible_to_public', true)->values());
        }

        return view('complaints.show', [
            'complaint' => $complaint,
            'canHandleComplaint' => $canHandleComplaint,
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
        abort_unless($this->workflow->isCurrentHandler($complaint, $request->user()), 403);

        $payload = $request->validated();
        $payload['assigned_to_user_id'] = $complaint->assigned_to_user_id;
        $payload['status'] = $complaint->status;
        $payload['escalation_level'] = $complaint->escalation_level;

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
        $this->authorize('view', $complaint);
        abort_unless($request->user()->hasRole(['Admin', 'Super Admin']) && $request->user()->can('assign complaints'), 403);

        $validated = $request->validate([
            'assigned_to_user_id' => ['required', 'exists:users,id'],
        ]);

        $assignee = User::role('Supervisor')->where('is_active', true)
            ->where('branch_id', $complaint->branch_id)
            ->where(function ($query) use ($complaint): void {
                $query->where('department_id', $complaint->department_id)
                    ->orWhereNull('department_id');
            })
            ->findOrFail($validated['assigned_to_user_id']);

        $from = $complaint->assigned_to_user_id;
        $previousStatus = $complaint->status;
        $complaint->skipAutomaticStatusHistory = true;
        $complaint->update(['assigned_to_user_id' => $assignee->id, 'status' => 'in_progress', 'escalation_level' => 0]);
        $complaint->skipAutomaticStatusHistory = false;
        $complaint->statusHistory()->create([
            'actor_user_id' => $request->user()->id,
            'from_assigned_user_id' => $from,
            'to_assigned_user_id' => $assignee->id,
            'status' => $complaint->status,
            'action' => 'reassigned_to_supervisor',
            'previous_status' => $previousStatus,
            'new_status' => $complaint->status,
            'title' => __('Reassigned to Supervisor'),
            'note' => __('Administrator reassigned the complaint to :name.', ['name' => $assignee->name]),
            'visible_to_public' => false,
        ]);

        return back()->with('status', 'Complaint assigned successfully.');
    }

    public function close(Request $request, Complaint $complaint): RedirectResponse
    {
        $this->authorize('view', $complaint);
        abort_unless($request->user()->can('close complaints'), 403);
        $this->workflow->close($complaint, $request->user());

        return back()->with('status', 'Complaint closed successfully.');
    }

    public function start(Request $request, Complaint $complaint): RedirectResponse
    {
        $this->authorize('view', $complaint);
        $this->workflow->startInvestigation($complaint, $request->user());

        return back()->with('status', __('Complaint marked as in progress.'));
    }

    public function reopen(Request $request, Complaint $complaint): RedirectResponse
    {
        $this->authorize('view', $complaint);
        abort_unless($request->user()->can('reopen complaints'), 403);

        $this->workflow->reopen($complaint, $request->user());

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
        return User::role('Supervisor')->where('is_active', true)
            ->when($user->isScopedReviewer() && $user->branch_id, fn ($query) => $query->where('branch_id', $user->branch_id))
            ->orderBy('name')
            ->get();
    }

    private function applyComplaintScope($query, User $user): void
    {
        if ($user->hasRole(['Admin', 'Super Admin'])) {
            return;
        }

        if ($user->hasRole('Supervisor')) {
            $query->where('assigned_to_user_id', $user->id)->where('escalation_level', 0);
            return;
        }

        if ($user->hasRole('Department Head')) {
            $query->where('assigned_to_user_id', $user->id)->where('escalation_level', 1);
            return;
        }

        if ($user->hasRole('Upper Management')) {
            $query->where('assigned_to_user_id', $user->id)->where('escalation_level', 2);
            return;
        }

        $query->where('submitted_by_user_id', $user->id);
    }
}
