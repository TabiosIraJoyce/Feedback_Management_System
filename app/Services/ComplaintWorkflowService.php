<?php

namespace App\Services;

use App\Models\Complaint;
use App\Models\ComplaintStatusHistory;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ComplaintWorkflowService
{
    public function assignOnSubmission(Complaint $complaint): ?User
    {
        $supervisorQuery = User::role('Supervisor')
            ->where('is_active', true)
            ->where('branch_id', $complaint->branch_id);

        $supervisor = $complaint->department_id
            ? (clone $supervisorQuery)->where('department_id', $complaint->department_id)->orderBy('id')->first()
            : (clone $supervisorQuery)->whereNull('department_id')->orderBy('id')->first();

        if (! $supervisor && $complaint->department_id) {
            $supervisor = User::role('Supervisor')
                ->where('is_active', true)
                ->where('branch_id', $complaint->branch_id)
                ->whereNull('department_id')
                ->orderBy('id')
                ->first();
        }

        if ($supervisor) {
            $previousStatus = $complaint->status;
            $complaint->skipAutomaticStatusHistory = true;
            $complaint->forceFill(['assigned_to_user_id' => $supervisor->id])->save();
            $complaint->skipAutomaticStatusHistory = false;
            $this->record($complaint, null, 'assigned', null, $supervisor->id, __('Assigned to the branch supervisor.'), false, null, $previousStatus);
        }

        return $supervisor;
    }

    public function escalateToDepartmentHead(Complaint $complaint, User $actor, string $reason, string $actions): void
    {
        $this->assertSupervisorOwns($complaint, $actor);

        $departmentHead = User::role('Department Head')
            ->where('is_active', true)
            ->where('branch_id', $complaint->branch_id)
            ->where('department_id', $complaint->department_id)
            ->first();

        if (! $complaint->department_id || ! $departmentHead) {
            throw ValidationException::withMessages([
                'department_id' => __('No active Department Head is configured for this complaint department.'),
            ]);
        }

        $this->transition($complaint, $actor, $departmentHead, 1, $reason, $actions, 'escalated_to_department_head');
    }

    public function escalateToUpperManagement(Complaint $complaint, User $actor, string $reason): void
    {
        $this->assertDepartmentHeadOwns($complaint, $actor);

        $recipientId = (int) SettingsService::get('upper_management_user_id');
        $recipient = $recipientId > 0
            ? User::role('Upper Management')->where('is_active', true)->find($recipientId)
            : null;

        if (! $recipient) {
            throw ValidationException::withMessages([
                'upper_management_user_id' => __('Configure an active Upper Management recipient in System Settings first.'),
            ]);
        }

        $this->transition($complaint, $actor, $recipient, 2, $reason, null, 'escalated_to_upper_management');
    }

    public function returnToSupervisor(Complaint $complaint, User $actor, string $reason): void
    {
        $this->assertDepartmentHeadOwns($complaint, $actor);

        $origin = $complaint->statusHistory()
            ->where('action', 'escalated_to_department_head')
            ->latest('id')
            ->first()?->from_assigned_user_id;
        $supervisor = $origin ? User::role('Supervisor')->where('is_active', true)->find($origin) : null;

        if (! $supervisor) {
            throw ValidationException::withMessages([
                'assigned_to_user_id' => __('The originating supervisor is no longer available. Reassign this case through an administrator.'),
            ]);
        }

        $this->transition($complaint, $actor, $supervisor, 0, $reason, null, 'returned_to_supervisor', 'in_progress');
    }

    public function resolve(Complaint $complaint, User $actor, string $resolution): void
    {
        $this->assertCurrentHandler($complaint, $actor);

        $from = $complaint->assigned_to_user_id;
        $previousStatus = $complaint->status;
        DB::transaction(function () use ($complaint, $actor, $resolution, $from, $previousStatus): void {
            $complaint->skipAutomaticStatusHistory = true;
            $complaint->forceFill([
                'status' => 'resolved',
                'resolved_at' => now(),
                'closed_at' => null,
                'resolution_summary' => $resolution,
            ])->save();
            $complaint->skipAutomaticStatusHistory = false;

            $this->record($complaint, $actor->id, 'resolved', $from, $from, $resolution, true, null, $previousStatus);
        });
    }

    public function startInvestigation(Complaint $complaint, User $actor): void
    {
        $this->assertCurrentHandler($complaint, $actor);
        if (! in_array($complaint->status, ['open', 'reopened'], true)) {
            throw ValidationException::withMessages(['status' => __('This complaint is already being handled.')]);
        }

        $previousStatus = $complaint->status;
        $complaint->skipAutomaticStatusHistory = true;
        $complaint->forceFill(['status' => 'in_progress'])->save();
        $complaint->skipAutomaticStatusHistory = false;
        $this->record($complaint, $actor->id, 'investigation_started', $complaint->assigned_to_user_id, $complaint->assigned_to_user_id, __('Investigation started.'), true, null, $previousStatus);
    }

    public function addInternalNote(Complaint $complaint, User $actor, string $note): void
    {
        $this->assertCurrentHandler($complaint, $actor);
        $this->record($complaint, $actor->id, 'internal_note', $complaint->assigned_to_user_id, $complaint->assigned_to_user_id, $note, false);
    }

    public function close(Complaint $complaint, User $actor): void
    {
        $this->assertCurrentHandler($complaint, $actor);

        if ($complaint->status !== 'resolved') {
            throw ValidationException::withMessages(['status' => __('Resolve the complaint before closing it.')]);
        }

        $previousStatus = $complaint->status;
        $complaint->skipAutomaticStatusHistory = true;
        $complaint->forceFill(['status' => 'closed', 'closed_at' => now()])->save();
        $complaint->skipAutomaticStatusHistory = false;
        $this->record($complaint, $actor->id, 'closed', $actor->id, $actor->id, __('Complaint formally closed.'), false, null, $previousStatus);
    }

    public function reopen(Complaint $complaint, User $actor, ?string $reason = null): void
    {
        $this->assertCurrentHandler($complaint, $actor);
        if (! in_array($complaint->status, ['resolved', 'closed'], true)) {
            throw ValidationException::withMessages(['status' => __('Only a resolved or closed complaint can be reopened.')]);
        }

        $previousStatus = $complaint->status;
        $complaint->skipAutomaticStatusHistory = true;
        $complaint->forceFill(['status' => 'reopened', 'resolved_at' => null, 'closed_at' => null])->save();
        $complaint->skipAutomaticStatusHistory = false;
        $this->record($complaint, $actor->id, 'reopened', $actor->id, $actor->id, $reason ?: __('Complaint reopened for additional action.'), true, null, $previousStatus);
    }

    public function isCurrentHandler(Complaint $complaint, User $user): bool
    {
        if ((int) $complaint->assigned_to_user_id !== (int) $user->id) {
            return false;
        }

        return match ((int) $complaint->escalation_level) {
            0 => $user->hasRole('Supervisor'),
            1 => $user->hasRole('Department Head') && (int) $user->department_id === (int) $complaint->department_id,
            2 => $user->hasRole('Upper Management'),
            default => false,
        };
    }

    private function assertSupervisorOwns(Complaint $complaint, User $actor): void
    {
        if (! $actor->hasRole('Supervisor') || ! $this->isCurrentHandler($complaint, $actor) || (int) $complaint->escalation_level !== 0) {
            throw new AuthorizationException(__('Only the assigned supervisor can perform this action.'));
        }
    }

    private function assertDepartmentHeadOwns(Complaint $complaint, User $actor): void
    {
        if (! $actor->hasRole('Department Head') || ! $this->isCurrentHandler($complaint, $actor) || (int) $complaint->escalation_level !== 1) {
            throw new AuthorizationException(__('Only the assigned Department Head can perform this action.'));
        }
    }

    private function assertCurrentHandler(Complaint $complaint, User $actor): void
    {
        if (! $this->isCurrentHandler($complaint, $actor)) {
            throw new AuthorizationException(__('You are not the assigned handler for this complaint.'));
        }
    }

    private function transition(
        Complaint $complaint,
        User $actor,
        User $recipient,
        int $level,
        string $reason,
        ?string $actionSummary,
        string $action,
        string $status = 'escalated',
    ): void {
        $from = $complaint->assigned_to_user_id;
        $previousStatus = $complaint->status;
        DB::transaction(function () use ($complaint, $actor, $recipient, $level, $reason, $actionSummary, $action, $status, $from, $previousStatus): void {
            $complaint->skipAutomaticStatusHistory = true;
            $complaint->forceFill([
                'status' => $status,
                'escalation_level' => $level,
                'assigned_to_user_id' => $recipient->id,
            ])->save();
            $complaint->skipAutomaticStatusHistory = false;

            $this->record($complaint, $actor->id, $action, $from, $recipient->id, $actionSummary, false, $reason, $previousStatus);

        });
    }

    private function record(
        Complaint $complaint,
        ?int $actorId,
        string $action,
        ?int $fromAssignee,
        ?int $toAssignee,
        ?string $note,
        bool $visibleToPublic,
        ?string $reason = null,
        ?string $previousStatus = null,
    ): ComplaintStatusHistory {
        return ComplaintStatusHistory::create([
            'complaint_id' => $complaint->id,
            'actor_user_id' => $actorId,
            'from_assigned_user_id' => $fromAssignee,
            'to_assigned_user_id' => $toAssignee,
            'status' => $complaint->status,
            'action' => $action,
            'previous_status' => $previousStatus ?? $complaint->status,
            'new_status' => $complaint->status,
            'title' => str($action)->replace('_', ' ')->title()->toString(),
            'note' => $note,
            'escalation_reason' => $reason,
            'visible_to_public' => $visibleToPublic,
        ]);
    }
}
