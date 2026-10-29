<?php

namespace App\Services;

use App\Models\SlaConfig;
use App\Models\SlaEscalation;
use App\Models\Complaint;
use App\Notifications\SlaEscalatedNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SlaMonitorService
{
    protected array $configs = [];

    public function __construct()
    {
        $this->configs = SlaConfig::where('enabled', true)->pluck('hours', 'category')->toArray();
    }

    public function evaluateAll(): array
    {
        $results = [];

        $openStatuses = ['open', 'pending', 'under_review', 'assigned', 'in_progress', 'reopened'];

        $complaints = Complaint::whereIn('status', $openStatuses)->with(['branch', 'assignedTo'])->get();

        foreach ($complaints as $complaint) {
            $status = $this->evaluate($complaint);
            $results[] = ['complaint_id' => $complaint->id, 'status' => $status];
        }

        return $results;
    }

    public function evaluate(Complaint $complaint): string
    {
        $category = $complaint->category ?? 'default';
        $hours = $this->configs[$category] ?? $this->configs['default'] ?? 24;

        $start = $complaint->created_at;
        $deadline = Carbon::parse($start)->addHours($hours);
        $now = Carbon::now();

        $diffSeconds = $deadline->diffInSeconds($now, false);
        $totalSeconds = $deadline->diffInSeconds($start);

        if ($now->greaterThan($deadline)) {
            $alreadyNotified = SlaEscalation::query()
                ->where('complaint_id', $complaint->id)
                ->where('new_status', 'overdue')
                ->exists();

            if (! $alreadyNotified) {
                $es = SlaEscalation::create([
                    'complaint_id' => $complaint->id,
                    'old_status' => $complaint->status,
                    'new_status' => 'overdue',
                    'note' => 'SLA deadline exceeded. Complaint remains assigned at its current workflow level.',
                    'notified' => false,
                ]);

                try {
                    if ($complaint->assignedTo) {
                        $complaint->assignedTo->notify(new SlaEscalatedNotification($complaint, $es));
                    }
                } catch (\Throwable $e) {
                    Log::error('SLA notification failed: '.$e->getMessage());
                }

            }

            return 'Overdue';
        }

        if ($totalSeconds > 0) {
            $remaining = $now->diffInSeconds($deadline, false);
            if ($remaining / $totalSeconds <= 0.1) {
                return 'Near Due';
            }
        }

        return 'On Time';
    }
}
