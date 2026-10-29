<?php

namespace App\Notifications;

use App\Models\Complaint;
use App\Models\SlaEscalation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SlaEscalatedNotification extends Notification
{
    use Queueable;

    public Complaint $complaint;
    public SlaEscalation $escalation;

    public function __construct(Complaint $complaint, SlaEscalation $escalation)
    {
        $this->complaint = $complaint;
        $this->escalation = $escalation;
    }

    public function via($notifiable)
    {
        return ['database', 'mail', 'broadcast'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Overdue Complaint: '.$this->complaint->reference_no)
            ->line('Complaint '.$this->complaint->reference_no.' has exceeded its SLA deadline and remains assigned at its current workflow level.')
            ->action('View complaint', url(route('complaints.show', $this->complaint->id)))
            ->line('Please attend to this as soon as possible.');
    }

    public function toDatabase($notifiable)
    {
        return [
            'complaint_id' => $this->complaint->id,
            'reference_no' => $this->complaint->reference_no,
            'message' => $this->escalation->note,
            'note' => $this->escalation->note,
            'url' => route('complaints.show', $this->complaint),
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'complaint_id' => $this->complaint->id,
            'reference_no' => $this->complaint->reference_no,
            'message' => $this->escalation->note,
            'url' => route('complaints.show', $this->complaint),
        ]);
    }
}
