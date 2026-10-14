<?php

namespace App\Services\EmployeeFeedback;

use App\Models\EmployeeFeedback;
use App\Models\User;
use App\Notifications\EmployeeFeedbackWorkflowNotification;
use Illuminate\Support\Collection;

class EmployeeFeedbackNotificationService
{
    public function notifyCreated(EmployeeFeedback $feedback, User $actor): void
    {
        $message = "{$feedback->reference_no} was submitted and is waiting for review.";
        $this->notifyUsers($this->reviewersFor($feedback), $feedback, 'created', $message, $actor);
    }

    public function notifyStatusChanged(EmployeeFeedback $feedback, User $actor, string $note = ''): void
    {
        $message = trim("{$feedback->reference_no} moved to {$feedback->status}. {$note}");

        $recipients = $this->reviewersFor($feedback);

        if ($feedback->user && $feedback->user->id !== $actor->id) {
            $recipients->push($feedback->user);
        }

        $this->notifyUsers($recipients, $feedback, 'status_changed', $message, $actor);
    }

    public function notifyNotesUpdated(EmployeeFeedback $feedback, User $actor): void
    {
        $message = "Internal notes were updated for {$feedback->reference_no}.";
        $this->notifyUsers($this->reviewersFor($feedback), $feedback, 'notes_updated', $message, $actor);
    }

    private function reviewersFor(EmployeeFeedback $feedback): Collection
    {
        $departmentHeads = User::query()
            ->where('department_id', $feedback->department_id)
            ->whereHas('roles', fn ($query) => $query->whereIn('name', ['Department Head', 'Supervisor']))
            ->get();

        $admins = User::query()
            ->whereHas('roles', fn ($query) => $query->whereIn('name', ['Admin', 'Super Admin']))
            ->get();

        return $departmentHeads->concat($admins)->unique('id')->values();
    }

    private function notifyUsers(Collection $users, EmployeeFeedback $feedback, string $action, string $message, User $actor): void
    {
        $users
            ->filter(fn ($user) => $user->id !== $actor->id)
            ->each(fn ($user) => $user->notify(new EmployeeFeedbackWorkflowNotification($feedback, $action, $message)));
    }
}
