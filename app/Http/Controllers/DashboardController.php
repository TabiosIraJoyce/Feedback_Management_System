<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Complaint;
use App\Models\Feedback;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Services\DashboardAnalyticsService;

class DashboardController extends Controller
{
    public function __invoke(Request $request, DashboardAnalyticsService $analytics): View
    {
        $user = $request->user();
        $dashboard = $analytics->getDashboardData($request, $user);

        $recentComplaints = Complaint::with('branch')
            ->when($user->isDepartmentScopedReviewer(), fn ($query) => $query->where('department_id', $user->department_id))
            ->when($user->isBranchScopedSupervisor() && $user->branch_id, fn ($query) => $query->where('branch_id', $user->branch_id))
            ->when($user->hasRole('Employee') || $user->hasRole('Customer'), fn ($query) => $query->where(function ($builder) use ($user) {
                $builder->where('submitted_by_user_id', $user->id)
                    ->orWhere('assigned_to_user_id', $user->id);
            }))
            ->latest()
            ->take(8)
            ->get();

        return view('dashboard', [
            'dashboard' => $dashboard,
            'recentComplaints' => $recentComplaints,
            'recentComplaintItems' => $this->recentComplaintsPayload($recentComplaints),
            'branches' => Cache::remember('dashboard_branches_list', 3600, fn () => Branch::orderBy('name')->get(['id', 'name'])),
            'statusOptions' => $analytics->statusOptions(),
            'categories' => Cache::remember('dashboard_category_list', 3600, fn () => Complaint::select('category')
                ->distinct()
                ->orderBy('category')
                ->pluck('category')
                ->all()),
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
            'recent_complaints' => $this->recentComplaintsPayload($this->recentComplaints($user)),
        ]);
    }

    private function recentComplaints($user)
    {
        return Complaint::with('branch')
            ->when($user->isDepartmentScopedReviewer(), fn ($query) => $query->where('department_id', $user->department_id))
            ->when($user->isBranchScopedSupervisor() && $user->branch_id, fn ($query) => $query->where('branch_id', $user->branch_id))
            ->when($user->hasRole('Employee') || $user->hasRole('Customer'), fn ($query) => $query->where(function ($builder) use ($user) {
                $builder->where('submitted_by_user_id', $user->id)
                    ->orWhere('assigned_to_user_id', $user->id);
            }))
            ->latest()
            ->take(8)
            ->get();
    }

    private function recentComplaintsPayload($recentComplaints): array
    {
        return $recentComplaints
            ->map(fn (Complaint $complaint) => [
                'url' => route('complaints.show', $complaint),
                'subject' => $complaint->subject,
                'reference_no' => $complaint->reference_no,
                'branch_name' => $complaint->branch->name,
                'status' => $complaint->status,
                'status_label' => __(str($complaint->status)->replace('_', ' ')->title()->toString()),
            ])
            ->values()
            ->all();
    }
}
