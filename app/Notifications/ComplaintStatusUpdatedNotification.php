<?php

namespace App\Notifications;

use App\Models\Complaint;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
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
        return ['database', 'broadcast', 'mail'];
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

    public function toMail($notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject(__('Complaint update: :reference', ['reference' => $this->complaint->reference_no]))
            ->greeting(__('Hello :name,', ['name' => $this->complaint->customer_name]))
            ->line(__('The status of your complaint is now :status.', ['status' => __(str($this->complaint->status)->replace('_', ' ')->title()->toString())]));

        if (in_array($this->complaint->status, ['resolved', 'closed'], true) && $this->complaint->resolution_summary) {
            $mail->line(__('Resolution: :summary', ['summary' => $this->complaint->resolution_summary]));
        }

        return $mail
            ->action(__('Track your complaint'), route('track-complaint.show', $this->complaint->reference_no))
            ->line(__('Keep your reference number to follow any future updates.'));
    }
}
