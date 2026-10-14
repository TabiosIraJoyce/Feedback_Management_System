<?php

namespace App\Services\Reports;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportExportService
{
    public function downloadCsv(string $filename, array $columns, Collection $rows): StreamedResponse
    {
        return response()->streamDownload(function () use ($columns, $rows): void {
            $handle = fopen('php://output', 'wb');
            fputcsv($handle, collect($columns)->pluck('label')->all());

            foreach ($rows as $row) {
                fputcsv($handle, collect($columns)->map(fn ($column) => $row[$column['key']] ?? '')->all());
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    public function downloadPdf(string $filename, array $payload): Response
    {
        return Pdf::loadView('reports.export-pdf', $payload)
            ->setPaper('a4', 'landscape')
            ->download($filename);
    }

    public function storeCsv(string $path, array $columns, Collection $rows): string
    {
        $lines = [collect($columns)->pluck('label')->implode(',')];

        foreach ($rows as $row) {
            $values = collect($columns)->map(fn ($column) => $this->escapeCsvValue($row[$column['key']] ?? ''))->implode(',');
            $lines[] = $values;
        }

        Storage::disk('local')->put($path, implode(PHP_EOL, $lines));

        return $path;
    }

    public function storePdf(string $path, array $payload): string
    {
        $pdf = Pdf::loadView('reports.export-pdf', $payload)
            ->setPaper('a4', 'landscape');

        Storage::disk('local')->put($path, $pdf->output());

        return $path;
    }

    private function escapeCsvValue(string $value): string
    {
        $escaped = str_replace('"', '""', $value);
        return "\"{$escaped}\"";
    }
}
