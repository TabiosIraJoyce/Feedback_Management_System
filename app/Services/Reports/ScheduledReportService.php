<?php

namespace App\Services\Reports;

use App\Models\ScheduledReport;
use App\Models\User;
use Carbon\Carbon;

class ScheduledReportService
{
    public function create(array $payload, User $user): ScheduledReport
    {
        return ScheduledReport::create([
            'user_id' => $user->id,
            'name' => $payload['name'],
            'report_type' => $payload['report'],
            'format' => $payload['format'],
            'frequency' => $payload['frequency'],
            'filters' => $this->extractFilters($payload),
            'recipients' => $this->parseRecipients($payload['recipients'] ?? null),
            'next_run_at' => $this->nextRunAt($payload['frequency']),
            'is_active' => (bool) ($payload['is_active'] ?? true),
        ]);
    }

    public function update(ScheduledReport $scheduledReport, array $payload): ScheduledReport
    {
        $scheduledReport->update([
            'name' => $payload['name'],
            'report_type' => $payload['report'],
            'format' => $payload['format'],
            'frequency' => $payload['frequency'],
            'filters' => $this->extractFilters($payload),
            'recipients' => $this->parseRecipients($payload['recipients'] ?? null),
            'next_run_at' => $this->nextRunAt($payload['frequency']),
            'is_active' => (bool) ($payload['is_active'] ?? false),
        ]);

        return $scheduledReport->fresh();
    }

    public function nextRunAt(string $frequency, ?Carbon $from = null): Carbon
    {
        $from ??= now();

        return match ($frequency) {
            'daily' => $from->copy()->addDay(),
            'monthly' => $from->copy()->addMonth(),
            default => $from->copy()->addWeek(),
        };
    }

    private function extractFilters(array $payload): array
    {
        return [
            'branch' => $payload['branch'] ?? null,
            'product' => $payload['product'] ?? null,
            'status' => $payload['status'] ?? null,
            'category' => $payload['category'] ?? null,
            'from_date' => $payload['from_date'] ?? null,
            'to_date' => $payload['to_date'] ?? null,
        ];
    }

    private function parseRecipients(?string $recipients): array
    {
        return collect(explode(',', (string) $recipients))
            ->map(fn ($item) => trim($item))
            ->filter()
            ->values()
            ->all();
    }
}
