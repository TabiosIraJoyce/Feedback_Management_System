<?php

namespace App\Notifications;

use App\Models\EmployeeFeedback;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class EmployeeFeedbackWorkflowNotification extends Notification
{
    use Queueable;

    public function __construct(
        public EmployeeFeedback $feedback,
        public string $action,
        public string $message
    ) {
    }

    public function via($notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'employee_feedback_id' => $this->feedback->id,
            'reference_no' => $this->feedback->reference_no,
            'subject' => $this->feedback->subject,
            'status' => $this->feedback->status,
            'action' => $this->action,
            'message' => $this->message,
            'url' => route('employee-feedback.show', $this->feedback),
        ];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toDatabase($notifiable));
    }
}
