<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ScheduledReport extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'report_type',
        'format',
        'frequency',
        'filters',
        'recipients',
        'next_run_at',
        'last_run_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'filters' => 'array',
            'recipients' => 'array',
            'next_run_at' => 'datetime',
            'last_run_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(ReportAuditLog::class);
    }
}
