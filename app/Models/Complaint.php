<?php

namespace App\Models;

use App\Models\ComplaintStatusHistory;
use App\Notifications\ComplaintStatusUpdatedNotification;
use Database\Factories\ComplaintFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;

class Complaint extends Model
{
    /** @use HasFactory<ComplaintFactory> */
    use HasFactory, SoftDeletes;

    public bool $skipAutomaticStatusHistory = false;

    protected $fillable = [
        'branch_id',
        'department_id',
        'submitted_by_user_id',
        'assigned_to_user_id',
        'reference_no',
        'customer_name',
        'customer_email',
        'customer_phone',
        'subject',
        'category',
        'priority',
        'status',
        'escalation_level',
        'message',
        'sentiment',
        'resolution_summary',
        'due_at',
        'resolved_at',
        'closed_at',
        'allow_public_reopen',
        'satisfaction_rating',
        'satisfaction_comment',
        'satisfaction_submitted_at',
        'public_reopened_at',
    ];

    protected function casts(): array
    {
        return [
            'due_at' => 'datetime',
            'resolved_at' => 'datetime',
            'closed_at' => 'datetime',
            'allow_public_reopen' => 'boolean',
            'escalation_level' => 'integer',
            'satisfaction_submitted_at' => 'datetime',
            'public_reopened_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Complaint $complaint): void {
            if (! $complaint->reference_no) {
                do {
                    $complaint->reference_no = 'GLD-'.date('Y').'-'.str_pad((string) rand(0, 999999), 6, '0', STR_PAD_LEFT);
                } while (Complaint::where('reference_no', $complaint->reference_no)->exists());
            }
        });

        static::created(function (Complaint $complaint): void {
            ComplaintStatusHistory::create([
                'complaint_id' => $complaint->id,
                'actor_user_id' => auth()->id(),
                'status' => $complaint->status,
                'action' => 'submitted',
                'new_status' => $complaint->status,
                'title' => 'Complaint Submitted',
                'note' => 'Complaint was submitted.',
                'visible_to_public' => true,
            ]);
        });

        static::updated(function (Complaint $complaint): void {
            if ($complaint->wasChanged(['status', 'assigned_to_user_id']) && ! $complaint->skipAutomaticStatusHistory) {
                $oldStatus = $complaint->getOriginal('status');
                $oldAssignee = $complaint->getOriginal('assigned_to_user_id');
                $statusChanged = $complaint->wasChanged('status');
                ComplaintStatusHistory::create([
                    'complaint_id' => $complaint->id,
                    'actor_user_id' => auth()->id(),
                    'from_assigned_user_id' => $oldAssignee,
                    'to_assigned_user_id' => $complaint->assigned_to_user_id,
                    'status' => $complaint->status,
                    'action' => $statusChanged ? 'status_updated' : 'assigned',
                    'previous_status' => $oldStatus,
                    'new_status' => $complaint->status,
                    'title' => $statusChanged ? 'Status Changed' : 'Assignment Changed',
                    'note' => $statusChanged
                        ? 'Status changed to '.ucwords(str_replace('_', ' ', $complaint->status)).'.'
                        : 'Complaint assignment changed.',
                    'visible_to_public' => true,
                ]);
            }

            if (! $complaint->wasChanged(['status', 'assigned_to_user_id'])) {
                return;
            }

            $message = $complaint->notificationMessage();
            $actorId = auth()->id();

            collect([$complaint->submitter, $complaint->assignee])
                ->filter()
                ->unique('id')
                ->reject(fn (User $user): bool => $actorId !== null && $user->id === $actorId)
                ->each(function (User $user) use ($complaint, $message): void {
                    $user->notify(new ComplaintStatusUpdatedNotification($complaint, $message, $complaint->notificationAction()));
                    Cache::forget("user_notifications_{$user->id}");
                    Cache::forget("user_unread_notifications_{$user->id}");
                });

            if ($complaint->wasChanged('status') && $complaint->customer_email) {
                try {
                    Notification::route('mail', $complaint->customer_email)->notify(
                        new ComplaintStatusUpdatedNotification($complaint, $message, $complaint->notificationAction())
                    );
                } catch (\Throwable $exception) {
                    report($exception);
                }
            }
        });
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(ComplaintStatusHistory::class)->orderBy('created_at', 'asc');
    }

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by_user_id');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->assignee();
    }

    public function slaEscalations(): HasMany
    {
        return $this->hasMany(SlaEscalation::class)->latest();
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable')->latest();
    }

    public function notificationMessage(): string
    {
        if ($this->wasChanged('assigned_to_user_id') && $this->assignee) {
            return "{$this->reference_no} is now assigned to {$this->assignee->name}.";
        }

        return "{$this->reference_no} is now ".str($this->status)->replace('_', ' ')->title().'.';
    }

    public function notificationAction(): string
    {
        return $this->wasChanged('assigned_to_user_id') ? 'assigned' : 'status_updated';
    }
}
