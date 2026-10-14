<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Http\Requests\StoreEmployeeFeedbackRequest;
use App\Http\Requests\UpdateEmployeeFeedbackNotesRequest;
use App\Http\Requests\UpdateEmployeeFeedbackRequest;
use App\Http\Requests\UpdateEmployeeFeedbackStatusRequest;
use App\Models\Department;
use App\Models\EmployeeFeedback;
use App\Services\EmployeeFeedback\EmployeeFeedbackAnalyticsService;
use App\Services\EmployeeFeedback\EmployeeFeedbackWorkflowService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmployeeFeedbackController extends Controller
{
    public function index(Request $request, EmployeeFeedbackAnalyticsService $analytics): View
    {
        $this->authorize('viewAny', EmployeeFeedback::class);
        $filters = $analytics->filters();
        $user = $request->user();

        return view('employee-feedback.index', [
            'feedbackEntries' => $analytics->query($user, $filters)->latest()->paginate(12)->withQueryString(),
            'overview' => $analytics->overview($user, $filters),
            'statusDistribution' => $analytics->statusDistribution($user, $filters),
            'categoryDistribution' => $analytics->categoryDistribution($user, $filters),
            'filters' => $filters,
            'branches' => $this->branchesForUser($user),
            'statuses' => $this->statuses(),
            'priorities' => $this->priorities(),
            'categories' => $this->categories(),
        ]);
    }

    public function create(Request $request): View
    {
        $this->authorize('create', EmployeeFeedback::class);

        return view('employee-feedback.create', [
            'departments' => $this->departmentsForUser($request->user()),
            'priorities' => $this->priorities(),
            'categories' => $this->categories(),
        ]);
    }

    public function store(StoreEmployeeFeedbackRequest $request, EmployeeFeedbackWorkflowService $workflow): RedirectResponse
    {
        $this->authorize('create', EmployeeFeedback::class);

        $feedback = $workflow->create($request->validated(), $request->user());

        return redirect()->route('employee-feedback.show', $feedback)
            ->with('status', 'Employee feedback submitted successfully.');
    }

    public function show(EmployeeFeedback $employeeFeedback): View
    {
        $this->authorize('view', $employeeFeedback);
        $employeeFeedback->load(['branch', 'department', 'user', 'statusHistories.user']);

        return view('employee-feedback.show', [
            'feedback' => $employeeFeedback,
            'statuses' => $this->statuses(),
        ]);
    }

    public function edit(EmployeeFeedback $employeeFeedback, Request $request): View
    {
        $this->authorize('update', $employeeFeedback);

        return view('employee-feedback.edit', [
            'feedback' => $employeeFeedback,
            'departments' => $this->departmentsForUser($request->user()),
            'priorities' => $this->priorities(),
            'categories' => $this->categories(),
        ]);
    }

    public function update(UpdateEmployeeFeedbackRequest $request, EmployeeFeedback $employeeFeedback, EmployeeFeedbackWorkflowService $workflow): RedirectResponse
    {
        $this->authorize('update', $employeeFeedback);

        $workflow->update($employeeFeedback, $request->validated(), $request->user());

        return redirect()->route('employee-feedback.show', $employeeFeedback)
            ->with('status', 'Employee feedback updated successfully.');
    }

    public function updateStatus(UpdateEmployeeFeedbackStatusRequest $request, EmployeeFeedback $employeeFeedback, EmployeeFeedbackWorkflowService $workflow): RedirectResponse
    {
        $this->authorize('review', $employeeFeedback);

        $workflow->transition($employeeFeedback, $request->validated()['status'], $request->user(), $request->validated()['note'] ?? null);

        return back()->with('status', 'Employee feedback status updated successfully.');
    }

    public function reopen(Request $request, EmployeeFeedback $employeeFeedback, EmployeeFeedbackWorkflowService $workflow): RedirectResponse
    {
        $this->authorize('reopen', $employeeFeedback);

        $workflow->reopen($employeeFeedback, $request->user(), (string) $request->input('note'));

        return back()->with('status', 'Employee feedback reopened successfully.');
    }

    public function close(Request $request, EmployeeFeedback $employeeFeedback, EmployeeFeedbackWorkflowService $workflow): RedirectResponse
    {
        $this->authorize('close', $employeeFeedback);

        $workflow->transition($employeeFeedback, 'closed', $request->user(), (string) $request->input('note', 'Closed manually.'));

        return back()->with('status', 'Employee feedback closed successfully.');
    }

    public function updateNotes(UpdateEmployeeFeedbackNotesRequest $request, EmployeeFeedback $employeeFeedback, EmployeeFeedbackWorkflowService $workflow): RedirectResponse
    {
        $this->authorize('review', $employeeFeedback);

        $workflow->updateNotes($employeeFeedback, (string) ($request->validated()['internal_notes'] ?? ''), $request->user());

        return back()->with('status', 'Internal notes updated successfully.');
    }

    public function destroy(EmployeeFeedback $employeeFeedback): RedirectResponse
    {
        $this->authorize('delete', $employeeFeedback);
        $employeeFeedback->delete();

        activity()
            ->causedBy(request()->user())
            ->performedOn($employeeFeedback)
            ->log('Employee feedback archived.');

        return redirect()->route('employee-feedback.index')->with('status', 'Employee feedback archived successfully.');
    }

    private function departmentsForUser($user)
    {
        if (($user->hasRole('Employee') || $user->hasRole('Customer')) && ! $user->branch_id) {
            return collect();
        }

        return Department::with('branch')
            ->when($user->isDepartmentScopedReviewer(), fn ($query) => $query->whereKey($user->department_id))
            ->when($user->isBranchScopedSupervisor() && $user->branch_id, fn ($query) => $query->where('branch_id', $user->branch_id))
            ->when(($user->hasRole('Employee') || $user->hasRole('Customer')) && $user->branch_id, fn ($query) => $query->where('branch_id', $user->branch_id))
            ->where('status', 'active')
            ->orderBy('name')
            ->get();
    }

    private function branchesForUser($user)
    {
        if (($user->hasRole('Employee') || $user->hasRole('Customer')) && ! $user->branch_id) {
            return collect();
        }

        return Branch::query()
            ->when($user->isDepartmentScopedReviewer() && $user->branch_id, fn ($query) => $query->whereKey($user->branch_id))
            ->when($user->isBranchScopedSupervisor() && $user->branch_id, fn ($query) => $query->whereKey($user->branch_id))
            ->when(($user->hasRole('Employee') || $user->hasRole('Customer')) && $user->branch_id, fn ($query) => $query->whereKey($user->branch_id))
            ->where('status', 'active')
            ->orderBy('name')
            ->get();
    }

    private function statuses(): array
    {
        return [
            'pending' => 'Pending',
            'under_review' => 'Under Review',
            'in_progress' => 'In Progress',
            'resolved' => 'Resolved',
            'closed' => 'Closed',
        ];
    }

    private function priorities(): array
    {
        return [
            'low' => 'Low',
            'medium' => 'Medium',
            'high' => 'High',
            'urgent' => 'Urgent',
        ];
    }

    private function categories(): array
    {
        return [
            'suggestion' => 'Suggestion',
            'operational_concern' => 'Operational Concern',
            'incident_report' => 'Incident Report',
            'internal_feedback' => 'Internal Feedback',
            'other' => 'Other',
        ];
    }
}
