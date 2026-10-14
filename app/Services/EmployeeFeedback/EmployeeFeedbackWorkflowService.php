<?php

namespace App\Services\EmployeeFeedback;

use App\Models\Department;
use App\Models\EmployeeFeedback;
use App\Models\EmployeeFeedbackStatusHistory;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EmployeeFeedbackWorkflowService
{
    private const ALLOWED_TRANSITIONS = [
        'pending' => ['under_review', 'in_progress', 'closed'],
        'under_review' => ['in_progress', 'resolved', 'closed'],
        'in_progress' => ['resolved', 'closed'],
        'resolved' => ['closed'],
        'closed' => [],
    ];

    public function __construct(private EmployeeFeedbackNotificationService $notifications)
    {
    }

    public function create(array $payload, User $user): EmployeeFeedback
    {
        return DB::transaction(function () use ($payload, $user): EmployeeFeedback {
            $department = Department::with('branch')->findOrFail($payload['department_id']);
            $this->guardDepartmentScope($department, $user);

            $feedback = EmployeeFeedback::create([
                'user_id' => $user->id,
                'branch_id' => $department->branch_id,
                'department_id' => $department->id,
                'employee_name' => ! empty($payload['is_anonymous']) ? null : ($payload['employee_name'] ?: $user->name),
                'category' => $payload['category'],
                'subject' => $payload['subject'],
                'description' => $payload['description'],
                'priority' => $payload['priority'],
                'status' => 'pending',
                'is_anonymous' => (bool) ($payload['is_anonymous'] ?? false),
                'last_status_at' => now(),
            ]);

            if (($payload['attachment'] ?? null) instanceof UploadedFile) {
                $this->storeAttachment($feedback, $payload['attachment']);
            }

            $this->recordHistory($feedback, $user, null, 'pending', 'Feedback submitted.');

            activity()
                ->causedBy($user)
                ->performedOn($feedback)
                ->withProperties(['branch_id' => $feedback->branch_id, 'department_id' => $feedback->department_id])
                ->log('Employee feedback submitted.');

            $this->notifications->notifyCreated($feedback->fresh(['department', 'branch', 'user']), $user);

            return $feedback;
        });
    }

    public function update(EmployeeFeedback $feedback, array $payload, User $user): EmployeeFeedback
    {
        return DB::transaction(function () use ($feedback, $payload, $user): EmployeeFeedback {
            $department = Department::with('branch')->findOrFail($payload['department_id']);
            $this->guardDepartmentScope($department, $user);

            $feedback->update([
                'branch_id' => $department->branch_id,
                'department_id' => $department->id,
                'employee_name' => ! empty($payload['is_anonymous']) ? null : ($payload['employee_name'] ?: $user->name),
                'category' => $payload['category'],
                'subject' => $payload['subject'],
                'description' => $payload['description'],
                'priority' => $payload['priority'],
                'is_anonymous' => (bool) ($payload['is_anonymous'] ?? false),
            ]);

            if (($payload['attachment'] ?? null) instanceof UploadedFile) {
                $this->replaceAttachment($feedback, $payload['attachment']);
            }

            activity()
                ->causedBy($user)
                ->performedOn($feedback)
                ->withProperties(['branch_id' => $feedback->branch_id, 'department_id' => $feedback->department_id])
                ->log('Employee feedback updated.');

            return $feedback->fresh(['department', 'branch', 'user']);
        });
    }

    public function transition(EmployeeFeedback $feedback, string $status, User $user, ?string $note = null): EmployeeFeedback
    {
        if ($feedback->status === $status) {
            return $feedback;
        }

        if (! in_array($status, self::ALLOWED_TRANSITIONS[$feedback->status] ?? [], true)) {
            abort(422, "Status cannot move from {$feedback->status} to {$status}.");
        }

        return DB::transaction(function () use ($feedback, $status, $user, $note): EmployeeFeedback {
            $fromStatus = $feedback->status;

            $feedback->update([
                'status' => $status,
                'last_status_at' => now(),
                'resolved_at' => $status === 'resolved' ? now() : $feedback->resolved_at,
                'closed_at' => $status === 'closed' ? now() : null,
            ]);

            $this->recordHistory($feedback, $user, $fromStatus, $status, $note);

            activity()
                ->causedBy($user)
                ->performedOn($feedback)
                ->withProperties(['from_status' => $fromStatus, 'to_status' => $status, 'note' => $note])
                ->log('Employee feedback status updated.');

            $this->notifications->notifyStatusChanged($feedback->fresh(['department', 'branch', 'user']), $user, (string) $note);

            return $feedback;
        });
    }

    public function reopen(EmployeeFeedback $feedback, User $user, ?string $note = null): EmployeeFeedback
    {
        if (! in_array($feedback->status, ['resolved', 'closed'], true)) {
            abort(422, 'Only resolved or closed feedback can be reopened.');
        }

        return DB::transaction(function () use ($feedback, $user, $note): EmployeeFeedback {
            $fromStatus = $feedback->status;

            $feedback->update([
                'status' => 'pending',
                'last_status_at' => now(),
                'closed_at' => null,
                'reopened_count' => $feedback->reopened_count + 1,
            ]);

            $this->recordHistory($feedback, $user, $fromStatus, 'pending', $note ?: 'Feedback reopened.');

            activity()
                ->causedBy($user)
                ->performedOn($feedback)
                ->withProperties(['from_status' => $fromStatus, 'to_status' => 'pending', 'note' => $note])
                ->log('Employee feedback reopened.');

            $this->notifications->notifyStatusChanged($feedback->fresh(['department', 'branch', 'user']), $user, $note ?: 'Feedback reopened.');

            return $feedback;
        });
    }

    public function updateNotes(EmployeeFeedback $feedback, string $notes, User $user): EmployeeFeedback
    {
        $feedback->update(['internal_notes' => $notes]);

        activity()
            ->causedBy($user)
            ->performedOn($feedback)
            ->withProperties(['internal_notes' => $notes])
            ->log('Employee feedback notes updated.');

        $this->notifications->notifyNotesUpdated($feedback->fresh(['department', 'branch', 'user']), $user);

        return $feedback;
    }

    private function recordHistory(EmployeeFeedback $feedback, User $user, ?string $fromStatus, string $toStatus, ?string $note = null): EmployeeFeedbackStatusHistory
    {
        return EmployeeFeedbackStatusHistory::create([
            'employee_feedback_id' => $feedback->id,
            'user_id' => $user->id,
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'note' => $note,
        ]);
    }

    private function storeAttachment(EmployeeFeedback $feedback, UploadedFile $file): void
    {
        $feedback->update([
            'attachment_path' => $file->store('employee-feedback-attachments', 'public'),
            'attachment_original_name' => $file->getClientOriginalName(),
            'attachment_mime_type' => $file->getClientMimeType(),
        ]);
    }

    private function replaceAttachment(EmployeeFeedback $feedback, UploadedFile $file): void
    {
        if ($feedback->attachment_path) {
            Storage::disk('public')->delete($feedback->attachment_path);
        }

        $this->storeAttachment($feedback, $file);
    }

    private function guardDepartmentScope(Department $department, User $user): void
    {
        if ($user->isDepartmentScopedReviewer() && (int) $department->id !== (int) $user->department_id) {
            abort(403, 'You cannot submit or manage feedback outside your assigned department.');
        }

        if (($user->hasRole('Employee') || $user->isBranchScopedSupervisor()) && $user->branch_id && (int) $department->branch_id !== (int) $user->branch_id) {
            abort(403, 'You cannot submit feedback outside your assigned branch.');
        }
    }
}
