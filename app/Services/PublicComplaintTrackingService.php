<?php

namespace App\Services;

use App\Models\Complaint;
use App\Models\ComplaintStatusHistory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PublicComplaintTrackingService
{
    private const TIMELINE = [
        'open' => 'Submitted',
        'under_review' => 'Under Review',
        'assigned' => 'Assigned',
        'in_progress' => 'In Progress',
        'escalated' => 'Escalated',
        'resolved' => 'Resolved',
        'closed' => 'Closed',
        'reopened' => 'Reopened',
    ];

    public function findByTrackingNumber(string $trackingNumber): Complaint
    {
        return Complaint::query()
            ->with([
                'branch',
                'department',
                'assignee.branch',
                'statusHistory' => fn ($query) => $query->where('visible_to_public', true)->orderBy('created_at'),
                'slaEscalations' => fn ($query) => $query->latest(),
            ])
            ->where('reference_no', strtoupper($trackingNumber))
            ->firstOrFail();
    }

    public function timeline(Complaint $complaint): array
    {
        $history = $complaint->statusHistory;
        $presentStatuses = $history->pluck('status')->all();
        $effectiveCurrent = $this->effectiveCurrentStatus($complaint, $history);

        $steps = [
            ['key' => 'open', 'label' => __('Submitted')],
            ['key' => 'under_review', 'label' => __('Under Review')],
            ['key' => 'assigned', 'label' => __('Assigned')],
            ['key' => 'in_progress', 'label' => __('In Progress')],
            ['key' => 'escalated', 'label' => __('Escalated')],
            ['key' => 'resolved', 'label' => __('Resolved')],
            ['key' => 'closed', 'label' => __('Closed')],
        ];

        $currentIndex = collect($steps)->search(fn ($step) => $step['key'] === $effectiveCurrent);
        $currentIndex = $currentIndex === false ? 0 : $currentIndex;

        return array_map(function (array $step, int $index) use ($history, $presentStatuses, $effectiveCurrent, $currentIndex): array {
            $matchingHistory = $history->firstWhere('status', $step['key']);

            return [
                'key' => $step['key'],
                'label' => $step['label'],
                'active' => $effectiveCurrent === $step['key'],
                'completed' => in_array($step['key'], $presentStatuses, true) || $index <= $currentIndex,
                'timestamp' => $matchingHistory?->created_at,
                'note' => $matchingHistory?->note,
            ];
        }, $steps, array_keys($steps));
    }

    public function publicHistory(Complaint $complaint): Collection
    {
        return $complaint->statusHistory
            ->map(function (ComplaintStatusHistory $entry) {
                return [
                    'title' => $entry->title ?: ($this->statusLabel($entry->status).' '.__('Update')),
                    'status' => $entry->status,
                    'note' => $entry->note ?: __('Complaint updated.'),
                    'created_at' => $entry->created_at,
                ];
            });
    }

    public function slaSummary(Complaint $complaint): array
    {
        if (! $complaint->due_at) {
            return [
                'label' => __('No SLA Set'),
                'tone' => 'slate',
                'details' => __('No due date has been assigned yet.'),
            ];
        }

        if (in_array($complaint->status, ['resolved', 'closed'], true)) {
            $completedAt = $complaint->closed_at ?? $complaint->resolved_at;
            $onTime = $completedAt ? $completedAt->lessThanOrEqualTo($complaint->due_at) : null;

            return [
                'label' => $onTime ? __('Met SLA') : __('Past SLA'),
                'tone' => $onTime ? 'emerald' : 'rose',
                'details' => __('Target due').' '.optional($complaint->due_at)->format('M d, Y h:i A'),
            ];
        }

        if ($complaint->due_at->isPast()) {
            return [
                'label' => __('Overdue'),
                'tone' => 'rose',
                'details' => __('Due').' '.optional($complaint->due_at)->diffForHumans(),
            ];
        }

        if ($complaint->due_at->diffInHours(now()) <= 8) {
            return [
                'label' => __('Near Due'),
                'tone' => 'amber',
                'details' => __('Target due').' '.optional($complaint->due_at)->format('M d, Y h:i A'),
            ];
        }

        return [
            'label' => __('On Track'),
            'tone' => 'emerald',
            'details' => __('Target due').' '.optional($complaint->due_at)->format('M d, Y h:i A'),
        ];
    }

    public function escalationSummary(Complaint $complaint): array
    {
        $latestEscalation = $complaint->slaEscalations->first();

        return [
            'is_escalated' => $latestEscalation !== null || $complaint->status === 'escalated',
            'latest_note' => $latestEscalation?->note,
            'latest_at' => $latestEscalation?->created_at,
            'count' => $complaint->slaEscalations->count(),
        ];
    }

    public function estimatedResolution(Complaint $complaint): array
    {
        if (in_array($complaint->status, ['resolved', 'closed'], true)) {
            $completedAt = $complaint->closed_at ?? $complaint->resolved_at;

            return [
                'label' => __('Completed'),
                'date' => $completedAt?->format('M d, Y h:i A'),
                'relative' => $completedAt?->diffForHumans(),
                'source' => __('Based on actual completion time.'),
            ];
        }

        if ($complaint->due_at) {
            return [
                'label' => __('Expected completion'),
                'date' => $complaint->due_at->format('M d, Y h:i A'),
                'relative' => $complaint->due_at->diffForHumans(),
                'source' => __('Based on the current SLA target.'),
            ];
        }

        $averageResolutionDays = Complaint::query()
            ->where('branch_id', $complaint->branch_id)
            ->where('category', $complaint->category)
            ->whereNotNull('closed_at')
            ->selectRaw('AVG('.$this->resolutionDaysExpression().') as avg_days')
            ->value('avg_days');

        $fallbackDays = $averageResolutionDays ? max(1, (int) round((float) $averageResolutionDays)) : 3;
        $estimatedAt = $complaint->created_at->copy()->addDays($fallbackDays);

        return [
            'label' => __('Estimated completion'),
            'date' => $estimatedAt->format('M d, Y h:i A'),
            'relative' => $estimatedAt->diffForHumans(),
            'source' => __('Estimated from similar complaints handled by this branch.'),
        ];
    }

    public function snapshot(Complaint $complaint): array
    {
        $lastVisibleUpdate = $complaint->statusHistory->last()?->created_at ?? $complaint->updated_at;

        return [
            'reference_no' => $complaint->reference_no,
            'status' => $complaint->status,
            'status_label' => $this->statusLabel($complaint->status),
            'updated_at' => $lastVisibleUpdate?->toIso8601String(),
            'updated_human' => $lastVisibleUpdate?->diffForHumans(),
            'sla' => $this->slaSummary($complaint),
            'escalation' => $this->escalationSummary($complaint),
            'estimated_resolution' => $this->estimatedResolution($complaint),
            'history_count' => $complaint->statusHistory->count(),
        ];
    }

    public function canSubmitSurvey(Complaint $complaint): bool
    {
        return in_array($complaint->status, ['resolved', 'closed'], true)
            && $complaint->satisfaction_submitted_at === null;
    }

    public function canPublicReopen(Complaint $complaint): bool
    {
        return $complaint->allow_public_reopen
            && in_array($complaint->status, ['resolved', 'closed'], true)
            && ($complaint->closed_at?->greaterThanOrEqualTo(now()->subDays(7)) ?? true);
    }

    public function submitSurvey(Complaint $complaint, array $data): void
    {
        $validated = Validator::make($data, [
            'satisfaction_rating' => ['required', 'integer', 'min:1', 'max:5'],
            'satisfaction_comment' => ['nullable', 'string', 'max:2000'],
        ])->validate();

        $previousStatus = $complaint->status;
        $complaint->update([
            'satisfaction_rating' => $validated['satisfaction_rating'],
            'satisfaction_comment' => $validated['satisfaction_comment'] ?? null,
            'satisfaction_submitted_at' => now(),
        ]);

        activity()
            ->performedOn($complaint)
            ->withProperties(Arr::only($validated, ['satisfaction_rating', 'satisfaction_comment']))
            ->log('Public complaint satisfaction survey submitted.');
    }

    public function publicReopen(Complaint $complaint, array $data): void
    {
        $validated = Validator::make($data, [
            'reason' => ['required', 'string', 'min:10', 'max:1500'],
        ])->validate();

        $complaint->update([
            'status' => 'reopened',
            'closed_at' => null,
            'public_reopened_at' => now(),
        ]);

        ComplaintStatusHistory::create([
            'complaint_id' => $complaint->id,
            'from_assigned_user_id' => $complaint->assigned_to_user_id,
            'to_assigned_user_id' => $complaint->assigned_to_user_id,
            'status' => 'reopened',
            'action' => 'customer_reopened',
            'previous_status' => $previousStatus,
            'new_status' => 'reopened',
            'title' => __('Customer Requested Reopen'),
            'note' => $validated['reason'],
            'visible_to_public' => true,
        ]);

        activity()
            ->performedOn($complaint)
            ->withProperties(['reason' => $validated['reason']])
            ->log('Complaint reopened from public tracking portal.');
    }

    public function searchAudit(string $trackingNumber, Request $request): void
    {
        activity()
            ->withProperties([
                'tracking_number' => strtoupper($trackingNumber),
                'ip' => $request->ip(),
                'user_agent' => (string) $request->userAgent(),
            ])
            ->log('Public complaint tracking lookup performed.');
    }

    public function statusLabel(string $status): string
    {
        return __(self::TIMELINE[$status] ?? str($status)->replace('_', ' ')->title()->toString());
    }

    private function effectiveCurrentStatus(Complaint $complaint, Collection $history): string
    {
        if ($complaint->status === 'reopened') {
            return 'in_progress';
        }

        if ($complaint->assigned_to_user_id && ! $history->contains('status', 'assigned') && in_array($complaint->status, ['open', 'in_progress'], true)) {
            return 'assigned';
        }

        return $complaint->status;
    }

    private function resolutionDaysExpression(): string
    {
        return match (DB::connection()->getDriverName()) {
            'mysql', 'mariadb' => 'TIMESTAMPDIFF(HOUR, created_at, closed_at) / 24',
            'pgsql' => 'EXTRACT(EPOCH FROM (closed_at - created_at)) / 86400',
            default => 'JULIANDAY(closed_at) - JULIANDAY(created_at)',
        };
    }
}
