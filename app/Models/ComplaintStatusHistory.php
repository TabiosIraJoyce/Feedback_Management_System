<?php

namespace App\Models;

use Database\Factories\ComplaintStatusHistoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComplaintStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'complaint_id',
        'actor_user_id',
        'from_assigned_user_id',
        'to_assigned_user_id',
        'status',
        'action',
        'previous_status',
        'new_status',
        'title',
        'note',
        'escalation_reason',
        'visible_to_public',
    ];

    public function complaint(): BelongsTo
    {
        return $this->belongsTo(Complaint::class);
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_user_id');
    }

    public function fromAssignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_assigned_user_id');
    }

    public function toAssignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to_assigned_user_id');
    }
}
