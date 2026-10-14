<?php

namespace App\Console\Commands;

use App\Models\ScheduledReport;
use App\Services\Reports\ReportAuditService;
use App\Services\Reports\ReportExportService;
use App\Services\Reports\ReportService;
use App\Services\Reports\ScheduledReportService;
use Illuminate\Console\Command;

class RunScheduledReportsCommand extends Command
{
    protected $signature = 'reports:run-scheduled';

    protected $description = 'Generate due scheduled reports and store their export files';

    public function handle(
        ReportService $reports,
        ReportExportService $exporter,
        ScheduledReportService $scheduler,
        ReportAuditService $audit
    ): int {
        $dueReports = ScheduledReport::with('user')
            ->where('is_active', true)
            ->whereNotNull('next_run_at')
            ->where('next_run_at', '<=', now())
            ->get();

        foreach ($dueReports as $scheduledReport) {
            $filters = $reports->normalizeFilters([
                ...($scheduledReport->filters ?? []),
                'report' => $scheduledReport->report_type,
            ]);

            $report = $reports->getReport($scheduledReport->report_type, $filters, $scheduledReport->user, false);
            $rows = collect($report['rows']);
            $filename = str($scheduledReport->name.'-'.now()->format('Ymd-His'))->slug()->value().'.'.$scheduledReport->format;
            $path = 'reports/'.$filename;

            if ($scheduledReport->format === 'csv') {
                $exporter->storeCsv($path, $report['columns'], $rows);
            } else {
                $exporter->storePdf($path, [
                    'title' => $report['title'],
                    'columns' => $report['columns'],
                    'rows' => $rows,
                    'filters' => $filters,
                ]);
            }

            $scheduledReport->update([
                'last_run_at' => now(),
                'next_run_at' => $scheduler->nextRunAt($scheduledReport->frequency, now()),
            ]);

            $audit->log('scheduled_export', $scheduledReport->report_type, $scheduledReport->format, $filters, [
                'row_count' => $rows->count(),
                'recipients' => $scheduledReport->recipients,
            ], $scheduledReport->user, $scheduledReport, $path);

            $this->info("Generated {$scheduledReport->name} -> {$path}");
        }

        $this->info('Scheduled report run complete.');

        return self::SUCCESS;
    }
}
