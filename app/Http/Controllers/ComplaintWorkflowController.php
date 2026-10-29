<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Services\ComplaintWorkflowService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ComplaintWorkflowController extends Controller
{
    public function escalateToDepartmentHead(Request $request, Complaint $complaint, ComplaintWorkflowService $workflow): RedirectResponse
    {
        $this->authorize('view', $complaint);
        $data = $request->validate([
            'reason' => ['required', 'string', 'min:10', 'max:3000'],
            'actions_taken' => ['required', 'string', 'min:10', 'max:5000'],
        ]);

        $workflow->escalateToDepartmentHead($complaint, $request->user(), $data['reason'], $data['actions_taken']);

        return back()->with('status', __('Complaint escalated to the Department Head.'));
    }

    public function escalateToUpperManagement(Request $request, Complaint $complaint, ComplaintWorkflowService $workflow): RedirectResponse
    {
        $this->authorize('view', $complaint);
        $data = $request->validate(['reason' => ['required', 'string', 'min:10', 'max:3000']]);
        $workflow->escalateToUpperManagement($complaint, $request->user(), $data['reason']);

        return back()->with('status', __('Complaint escalated to Upper Management.'));
    }

    public function returnToSupervisor(Request $request, Complaint $complaint, ComplaintWorkflowService $workflow): RedirectResponse
    {
        $this->authorize('view', $complaint);
        $data = $request->validate(['reason' => ['required', 'string', 'min:10', 'max:3000']]);
        $workflow->returnToSupervisor($complaint, $request->user(), $data['reason']);

        return back()->with('status', __('Complaint returned to the supervisor for further action.'));
    }

    public function resolve(Request $request, Complaint $complaint, ComplaintWorkflowService $workflow): RedirectResponse
    {
        $this->authorize('view', $complaint);
        $data = $request->validate(['resolution' => ['required', 'string', 'min:10', 'max:5000']]);
        $workflow->resolve($complaint, $request->user(), $data['resolution']);

        return back()->with('status', __('Complaint resolved successfully.'));
    }

    public function note(Request $request, Complaint $complaint, ComplaintWorkflowService $workflow): RedirectResponse
    {
        $this->authorize('view', $complaint);
        $data = $request->validate(['note' => ['required', 'string', 'min:3', 'max:5000']]);
        $workflow->addInternalNote($complaint, $request->user(), $data['note']);

        return back()->with('status', __('Internal note added.'));
    }
}
