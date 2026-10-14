<?php

namespace App\Services\Reports;

use App\Models\ReportAuditLog;
use App\Models\ScheduledReport;
use App\Models\User;

class ReportAuditService
{
    public function log(
        string $action,
        string $reportType,
        ?string $format,
        array $filters,
        array $meta = [],
        ?User $user = null,
        ?ScheduledReport $scheduledReport = null,
        ?string $filePath = null,
        string $status = 'success'
    ): ReportAuditLog {
        return ReportAuditLog::create([
            'user_id' => $user?->id,
            'scheduled_report_id' => $scheduledReport?->id,
            'action' => $action,
            'report_type' => $reportType,
            'format' => $format,
            'status' => $status,
            'filters' => $filters,
            'meta' => $meta,
            'file_path' => $filePath,
            'generated_at' => now(),
        ]);
    }
}
