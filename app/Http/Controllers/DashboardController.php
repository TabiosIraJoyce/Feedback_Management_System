<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Services\DashboardAnalyticsService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request, DashboardAnalyticsService $analytics): View
    {
        $user = $request->user();
        $dashboard = $analytics->getDashboardData($request, $user);

        return view('dashboard', [
            'dashboard' => $dashboard,
            'urgentComplaintItems' => $this->urgentComplaintsPayload($this->urgentComplaints($user)),
            'assignedComplaintItems' => $this->assignedComplaintsPayload($user),
            'recentResolvedItems' => $this->recentResolvedPayload($user),
        ]);
    }

    public function metrics(Request $request, DashboardAnalyticsService $analytics): JsonResponse|RedirectResponse
    {
        if (! $request->expectsJson() && ! $request->ajax()) {
            return redirect()->route('dashboard', $request->query());
        }

        $user = $request->user();
        $dashboard = $analytics->getDashboardData($request, $user);

        return response()->json([
            ...$dashboard,
            'urgent_complaints' => $this->urgentComplaintsPayload($this->urgentComplaints($user)),
            'assigned_complaints' => $this->assignedComplaintsPayload($user),
            'recent_resolved' => $this->recentResolvedPayload($user),
        ]);
    }

    private function urgentComplaints($user)
    {
        return $this->scopedComplaints($user)
            ->with(['branch', 'assignee'])
            ->where(function ($query): void {
                $query->where(function ($urgent): void {
                    $urgent->where('priority', 'urgent')
                        ->whereNotIn('status', ['resolved', 'closed']);
                })
                    ->orWhere(function ($overdue): void {
                        $overdue->whereNotNull('due_at')
                            ->where('due_at', '<', now())
                            ->whereNotIn('status', ['resolved', 'closed']);
                    });
            })
            ->orderByRaw("CASE WHEN priority = 'urgent' THEN 0 ELSE 1 END")
            ->latest()
            ->take(5)
            ->get();
    }

    private function scopedComplaints($user)
    {
        return Complaint::query()
            ->when($user->hasRole('Supervisor'), fn ($query) => $query->where('assigned_to_user_id', $user->id)->where('escalation_level', 0))
            ->when($user->hasRole('Department Head'), fn ($query) => $query->where('assigned_to_user_id', $user->id)->where('escalation_level', 1))
            ->when($user->hasRole('Upper Management'), fn ($query) => $query->where('assigned_to_user_id', $user->id)->where('escalation_level', 2))
            ->when($user->hasRole('Employee') || $user->hasRole('Customer'), fn ($query) => $query->where('submitted_by_user_id', $user->id));
    }

    private function recentResolvedPayload($user): array
    {
        return $this->scopedComplaints($user)
            ->with('branch')
            ->whereIn('status', ['resolved', 'closed'])
            ->latest('resolved_at')
            ->limit(5)
            ->get()
            ->map(fn (Complaint $complaint) => [
                'url' => route('complaints.show', $complaint),
                'subject' => $complaint->subject,
                'reference_no' => $complaint->reference_no,
                'branch_name' => $complaint->branch?->name ?? __('Unknown branch'),
                'status_label' => __(str($complaint->status)->replace('_', ' ')->title()->toString()),
            ])
            ->all();
    }

    private function assignedComplaintsPayload($user): array
    {
        return $this->scopedComplaints($user)
            ->with(['branch', 'assignee'])
            ->whereNotIn('status', ['resolved', 'closed'])
            ->latest()
            ->limit(6)
            ->get()
            ->map(fn (Complaint $complaint) => [
                'url' => route('complaints.show', $complaint),
                'subject' => $complaint->subject,
                'reference_no' => $complaint->reference_no,
                'branch_name' => $complaint->branch?->name ?? __('Unknown branch'),
                'assigned_to' => $complaint->assignee?->name ?? __('Unassigned'),
                'status_label' => $complaint->status === 'escalated'
                    ? ((int) $complaint->escalation_level === 1 ? __('Escalated to Department Head') : __('Escalated to Upper Management'))
                    : __(str($complaint->status)->replace('_', ' ')->title()->toString()),
            ])
            ->all();
    }

    private function urgentComplaintsPayload($urgentComplaints): array
    {
        return $urgentComplaints
            ->map(fn (Complaint $complaint) => [
                'url' => route('complaints.show', $complaint),
                'subject' => $complaint->subject,
                'reference_no' => $complaint->reference_no,
                'branch_name' => $complaint->branch?->name ?? __('Unknown branch'),
                'assigned_to' => $complaint->assignee?->name ?? __('Unassigned'),
                'priority' => $complaint->priority,
                'priority_label' => __($complaint->priority === 'urgent' ? 'Urgent' : 'Overdue'),
                'status_label' => __(str($complaint->status)->replace('_', ' ')->title()->toString()),
            ])
            ->values()
            ->all();
    }
}
