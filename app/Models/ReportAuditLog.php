<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportAuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'scheduled_report_id',
        'action',
        'report_type',
        'format',
        'status',
        'filters',
        'meta',
        'file_path',
        'generated_at',
    ];

    protected function casts(): array
    {
        return [
            'filters' => 'array',
            'meta' => 'array',
            'generated_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scheduledReport(): BelongsTo
    {
        return $this->belongsTo(ScheduledReport::class);
    }
}
