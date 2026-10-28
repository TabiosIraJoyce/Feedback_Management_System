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
            ->when($user->isDepartmentScopedReviewer(), fn ($query) => $query->where('department_id', $user->department_id))
            ->when($user->isBranchScopedSupervisor() && $user->branch_id, fn ($query) => $query->where('branch_id', $user->branch_id))
            ->when($user->hasRole('Employee') || $user->hasRole('Customer'), fn ($query) => $query->where(function ($builder) use ($user) {
                $builder->where('submitted_by_user_id', $user->id)
                    ->orWhere('assigned_to_user_id', $user->id);
            }));
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
