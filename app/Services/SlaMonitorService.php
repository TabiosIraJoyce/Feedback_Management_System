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

        $openStatuses = ['pending', 'under_review', 'assigned', 'in_progress', 'reopened'];

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

        if ($diffSeconds >= 0) {
            return 'On Time';
        }

        // negative means past deadline
        $overSeconds = abs($diffSeconds);

        // if significantly past SLA, escalate
        if ($overSeconds > 0) {
            // mark escalated if not already
            if ($complaint->status !== 'escalated') {
                $old = $complaint->status;
                $complaint->status = 'escalated';
                $complaint->save();

                $es = SlaEscalation::create([
                    'complaint_id' => $complaint->id,
                    'old_status' => $old,
                    'new_status' => 'escalated',
                    'note' => 'SLA exceeded — automatic escalation',
                    'notified' => false,
                ]);

                try {
                    if ($complaint->assignedTo) {
                        $complaint->assignedTo->notify(new SlaEscalatedNotification($complaint, $es));
                    }
                } catch (\Throwable $e) {
                    Log::error('SLA notification failed: '.$e->getMessage());
                }

                return 'Escalated';
            }

            return 'Escalated';
        }

        // Near due logic: when remaining time is <= 10% of total SLA
        if ($totalSeconds > 0) {
            $remaining = $totalSeconds - abs($diffSeconds);
            if ($remaining / $totalSeconds <= 0.1) {
                return 'Near Due';
            }
        }

        return 'On Time';
    }
}
