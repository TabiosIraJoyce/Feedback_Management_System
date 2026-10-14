<?php

namespace App\Models;

use Database\Factories\FeedbackFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feedback extends Model
{
    /** @use HasFactory<FeedbackFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'feedback';

    protected $fillable = [
        'branch_id',
        'department_id',
        'user_id',
        'name',
        'email',
        'subject',
        'type',
        'status',
        'message',
        'sentiment',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable')->latest();
    }
}
