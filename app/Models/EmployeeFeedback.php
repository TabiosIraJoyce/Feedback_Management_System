<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EmployeeFeedback extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'employee_feedback';

    protected $fillable = [
        'user_id',
        'branch_id',
        'department_id',
        'reference_no',
        'employee_name',
        'category',
        'subject',
        'description',
        'attachment_path',
        'attachment_original_name',
        'attachment_mime_type',
        'priority',
        'status',
        'is_anonymous',
        'reopened_count',
        'internal_notes',
        'last_status_at',
        'resolved_at',
        'closed_at',
    ];

    protected function casts(): array
    {
        return [
            'is_anonymous' => 'boolean',
            'last_status_at' => 'datetime',
            'resolved_at' => 'datetime',
            'closed_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (EmployeeFeedback $feedback): void {
            if (! $feedback->reference_no) {
                do {
                    $feedback->reference_no = 'EFB-'.Str::upper(Str::random(8));
                } while (static::where('reference_no', $feedback->reference_no)->exists());
            }

            if (! $feedback->last_status_at) {
                $feedback->last_status_at = now();
            }
        });

        static::deleting(function (EmployeeFeedback $feedback): void {
            if ($feedback->attachment_path) {
                Storage::disk('public')->delete($feedback->attachment_path);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(EmployeeFeedbackStatusHistory::class)->latest();
    }

    public function getDisplayNameAttribute(): string
    {
        if ($this->is_anonymous) {
            return 'Anonymous Employee';
        }

        return $this->employee_name ?: ($this->user?->name ?? 'Unknown Employee');
    }

    public function getAttachmentUrlAttribute(): ?string
    {
        return $this->attachment_path ? Storage::disk('public')->url($this->attachment_path) : null;
    }

    public function scopeVisibleTo($query, User $user)
    {
        if ($user->can('manage feedback') && ! $user->isScopedReviewer()) {
            return $query;
        }

        if ($user->isDepartmentScopedReviewer()) {
            return $query->where('department_id', $user->department_id);
        }

        if ($user->isBranchScopedSupervisor()) {
            return $query->where('branch_id', $user->branch_id);
        }

        return $query->where('user_id', $user->id);
    }
}
