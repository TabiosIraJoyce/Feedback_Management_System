<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportFilterRequest;
use App\Models\ReportAuditLog;
use App\Models\ScheduledReport;
use App\Services\Reports\ReportAuditService;
use App\Services\Reports\ReportExportService;
use App\Services\Reports\ReportService;
use App\Services\Reports\ScheduledReportService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(ReportFilterRequest $request, ReportService $reports): View
    {
        $filters = $reports->normalizeFilters($request->validated());
        $report = $reports->getReport($filters['report'], $filters, $request->user());

        return view('reports.index', [
            'definitions' => $reports->reportDefinitions(),
            'filters' => $filters,
            'report' => $report,
            'widgets' => $reports->widgets($filters, $request->user()),
            'options' => $reports->filterOptions($request->user(), $filters['report']),
            'scheduledReports' => ScheduledReport::with('user')->latest()->paginate(8, ['*'], 'schedules_page'),
            'auditLogs' => ReportAuditLog::with(['user', 'scheduledReport'])->latest('generated_at')->paginate(10, ['*'], 'logs_page'),
        ]);
    }

    public function export(
        ReportFilterRequest $request,
        string $reportType,
        string $format,
        ReportService $reports,
        ReportExportService $exporter,
        ReportAuditService $audit
    ): StreamedResponse|\Illuminate\Http\Response {
        $filters = $reports->normalizeFilters([...$request->validated(), 'report' => $reportType]);
        $report = $reports->getReport($reportType, $filters, $request->user(), false);
        $rows = collect($report['rows']);
        $filename = str($reportType.'-'.now()->format('Ymd-His'))->slug()->value().'.'.$format;

        $audit->log('manual_export', $reportType, $format, $filters, [
            'row_count' => $rows->count(),
        ], $request->user());

        if ($format === 'csv') {
            return $exporter->downloadCsv($filename, $report['columns'], $rows);
        }

        return $exporter->downloadPdf($filename, [
            'title' => $report['title'],
            'columns' => $report['columns'],
            'rows' => $rows,
            'filters' => $filters,
        ]);
    }

    public function storeSchedule(
        ReportFilterRequest $request,
        ScheduledReportService $scheduler,
        ReportAuditService $audit
    ): RedirectResponse {
        $scheduledReport = $scheduler->create($request->validated(), $request->user());

        $audit->log('schedule_created', $scheduledReport->report_type, $scheduledReport->format, $scheduledReport->filters ?? [], [
            'frequency' => $scheduledReport->frequency,
        ], $request->user(), $scheduledReport);

        return back()->with('status', 'Scheduled report created successfully.');
    }

    public function updateSchedule(
        ReportFilterRequest $request,
        ScheduledReport $scheduledReport,
        ScheduledReportService $scheduler,
        ReportAuditService $audit
    ): RedirectResponse {
        $scheduledReport = $scheduler->update($scheduledReport, $request->validated());

        $audit->log('schedule_updated', $scheduledReport->report_type, $scheduledReport->format, $scheduledReport->filters ?? [], [
            'frequency' => $scheduledReport->frequency,
            'active' => $scheduledReport->is_active,
        ], $request->user(), $scheduledReport);

        return back()->with('status', 'Scheduled report updated successfully.');
    }

    public function destroySchedule(ScheduledReport $scheduledReport, ReportAuditService $audit): RedirectResponse
    {
        $audit->log('schedule_deleted', $scheduledReport->report_type, $scheduledReport->format, $scheduledReport->filters ?? [], [], request()->user(), $scheduledReport);
        $scheduledReport->delete();

        return back()->with('status', 'Scheduled report deleted successfully.');
    }
}
