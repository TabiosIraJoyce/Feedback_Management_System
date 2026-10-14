<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeFeedbackStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_feedback_id',
        'user_id',
        'from_status',
        'to_status',
        'note',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'meta' => 'array',
        ];
    }

    public function feedback(): BelongsTo
    {
        return $this->belongsTo(EmployeeFeedback::class, 'employee_feedback_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
