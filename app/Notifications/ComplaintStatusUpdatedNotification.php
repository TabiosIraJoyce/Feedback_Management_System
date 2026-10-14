<?php

namespace App\Notifications;

use App\Models\Complaint;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class ComplaintStatusUpdatedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Complaint $complaint,
        public string $message,
        public string $action = 'status_updated',
    ) {
    }

    public function via($notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'complaint_id' => $this->complaint->id,
            'reference_no' => $this->complaint->reference_no,
            'subject' => $this->complaint->subject,
            'status' => $this->complaint->status,
            'action' => $this->action,
            'message' => $this->message,
            'url' => route('complaints.show', $this->complaint),
        ];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toDatabase($notifiable));
    }
}
