<?php

namespace App\Models;

use Database\Factories\FormSubmissionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id',
        'branch_id',
        'department_id',
        'payload',
        'status',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
