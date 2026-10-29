<?php

namespace App\Services;

use App\Models\Branch;
use App\Models\Complaint;
use App\Models\ComplaintStatusHistory;
use App\Models\Department;
use App\Models\Feedback;
use App\Models\FeedbackTicket;
use App\Models\Product;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardAnalyticsService
{
    private const CACHE_TTL = 45;

    private const COMPLAINT_STATUSES = [
        'open' => 'Open',
        'in_progress' => 'In Progress',
        'escalated' => 'Escalated',
        'resolved' => 'Resolved',
        'closed' => 'Closed',
        'reopened' => 'Reopened',
    ];

    public function buildFilters(Request $request): array
    {
        return [
            'branch' => $request->query('branch'),
            'product' => $request->query('product'),
            'status' => $request->query('status'),
            'category' => $request->query('category'),
            'from_date' => $request->query('from_date'),
            'to_date' => $request->query('to_date'),
        ];
    }

    public function statusOptions(): array
    {
        return collect(self::COMPLAINT_STATUSES)
            ->mapWithKeys(fn (string $label, string $key) => [$key => __($label)])
            ->all();
    }

    public function getDashboardData(Request $request, Authenticatable $user): array
    {
        $filters = $this->buildFilters($request);
        $cacheKey = $this->buildCacheKey($filters, $user);

        return Cache::remember($cacheKey, self::CACHE_TTL, fn () => $this->buildDashboardPayload($filters, $user));
    }

    private function buildDashboardPayload(array $filters, Authenticatable $user): array
    {
        $query = $this->buildComplaintQuery($filters, $user);

        $stats = $this->buildSummary($query, $filters, $user);
        $trend = $this->buildTrendData($filters, $user);
        $statusDistribution = $this->buildStatusDistribution($filters, $user);
        $categoryAnalytics = $this->buildCategoryAnalytics($filters, $user);
        $branchPerformance = $this->buildBranchPerformance($filters, $user);
        $productAnalytics = $this->buildProductAnalytics($filters, $user);
        $slaAnalytics = $this->buildSlaAnalytics($query);
        $reopenedAnalytics = $this->buildReopenedAnalytics($query);
        $branchRanking = $this->buildBranchRanking($filters, $user);

        return [
            'filters' => $filters,
            'stats' => $stats,
            'generated_at' => now()->toIso8601String(),
            'analytics' => [
                'trend' => $trend,
                'status_distribution' => $statusDistribution,
                'category' => $categoryAnalytics,
                'feedback_distribution' => $user->hasRole('Upper Management') ? [] : $this->buildFeedbackDistribution($filters, $user),
                'department_highlights' => $user->hasRole(['Admin', 'Super Admin']) ? $this->buildDepartmentHighlights($filters, $user) : [],
                'branch_performance' => $branchPerformance,
                'product' => $productAnalytics,
                'sla' => $slaAnalytics,
                'reopened' => $reopenedAnalytics,
                'branch_ranking' => $branchRanking,
            ],
        ];
    }

    private function buildComplaintQuery(array $filters, Authenticatable $user): Builder
    {
        $query = Complaint::query();

        if (! empty($filters['branch'])) {
            $query->where('branch_id', $filters['branch']);
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        if (! empty($filters['from_date'])) {
            $query->whereDate('created_at', '>=', $filters['from_date']);
        }

        if (! empty($filters['to_date'])) {
            $query->whereDate('created_at', '<=', $filters['to_date']);
        }

        if (! empty($filters['product'])) {
            $branchIds = DB::table('branch_products')
                ->where('product_id', $filters['product'])
                ->pluck('branch_id')
                ->all();

            $query->whereIn('branch_id', $branchIds ?: [0]);
        }

        return $this->applyUserScope($query, $user);
    }

    private function applyUserScope(Builder $query, Authenticatable $user): Builder
    {
        if (method_exists($user, 'hasRole') && $user->hasRole('Supervisor')) {
            $query->where('assigned_to_user_id', $user->id)->where('escalation_level', 0);
        } elseif (method_exists($user, 'hasRole') && $user->hasRole('Department Head')) {
            $query->where('assigned_to_user_id', $user->id)->where('escalation_level', 1);
        } elseif (method_exists($user, 'hasRole') && $user->hasRole('Upper Management')) {
            $query->where('assigned_to_user_id', $user->id)->where('escalation_level', 2);
        } elseif (method_exists($user, 'hasRole') && ($user->hasRole('Employee') || $user->hasRole('Customer'))) {
            $query->where('submitted_by_user_id', $user->id);
        }

        return $query;
    }

    private function buildCacheKey(array $filters, Authenticatable $user): string
    {
        $roles = method_exists($user, 'getRoleNames') ? $user->getRoleNames()->sort()->implode(',') : '';
        $scope = implode('|', [
            $user->getAuthIdentifier(),
            $roles,
            $user->branch_id ?? '',
            $user->department_id ?? '',
        ]);

        return 'dashboard_metrics_v3_'.md5($scope.'|'.json_encode($filters));
    }

    private function buildSummary(Builder $query, array $filters, Authenticatable $user): array
    {
        $total = (clone $query)->count();
        $pending = (clone $query)->whereIn('status', ['open', 'reopened'])->count();
        $inProgress = (clone $query)->where('status', 'in_progress')->count();
        $resolved = (clone $query)->whereIn('status', ['resolved', 'closed'])->count();
        $escalated = $this->escalatedComplaintCount($query);
        $reopened = (clone $query)->where('status', 'reopened')->count();
        $awaitingReview = (clone $query)->where('status', 'escalated')->count();
        $escalatedToDepartmentHead = ComplaintStatusHistory::query()
            ->where('action', 'escalated_to_department_head')
            ->when(method_exists($user, 'hasRole') && $user->hasRole('Supervisor'), fn ($history) => $history->where('from_assigned_user_id', $user->id))
            ->count();
        $escalatedToUpperManagement = ComplaintStatusHistory::query()
            ->where('action', 'escalated_to_upper_management')
            ->when(method_exists($user, 'hasRole') && $user->hasRole('Department Head'), fn ($history) => $history->where('actor_user_id', $user->id))
            ->count();
        $dueSoon = (clone $query)
            ->whereNotNull('due_at')
            ->whereBetween('due_at', [Carbon::now(), Carbon::now()->addHours(8)])
            ->whereNotIn('status', ['resolved', 'closed'])
            ->count();
        $avgResolution = (clone $query)
            ->whereIn('status', ['resolved', 'closed'])
            ->whereNotNull('closed_at')
            ->selectRaw('AVG('.$this->resolutionDaysExpression().') as avg_resolution_days')
            ->value('avg_resolution_days');

        return [
            'total_feedback' => $this->buildFeedbackSubmissionCount($filters, $user),
            'total_complaints' => $total,
            'pending_complaints' => $pending,
            'in_progress_complaints' => $inProgress,
            'resolved_complaints' => $resolved,
            'escalated_complaints' => $escalated,
            'reopened_complaints' => $reopened,
            'awaiting_review' => $awaitingReview,
            'escalated_to_department_head' => $escalatedToDepartmentHead,
            'escalated_to_upper_management' => $escalatedToUpperManagement,
            'due_soon_complaints' => $dueSoon,
            'avg_resolution_days' => $avgResolution ? round((float) $avgResolution, 1) : 0,
        ];
    }

    private function buildFeedbackQuery(array $filters, Authenticatable $user): Builder
    {
        $query = Feedback::query()->where('type', 'customer');

        if (! empty($filters['branch'])) {
            $query->where('branch_id', $filters['branch']);
        }

        if (! empty($filters['department'])) {
            $query->where('department_id', $filters['department']);
        }

        if (! empty($filters['product'])) {
            $branchIds = DB::table('branch_products')->where('product_id', $filters['product'])->pluck('branch_id');
            $query->whereIn('branch_id', $branchIds->isNotEmpty() ? $branchIds : [0]);
        }

        if (! empty($filters['from_date'])) {
            $query->whereDate('created_at', '>=', $filters['from_date']);
        }

        if (! empty($filters['to_date'])) {
            $query->whereDate('created_at', '<=', $filters['to_date']);
        }

        if (method_exists($user, 'isDepartmentScopedReviewer') && $user->isDepartmentScopedReviewer()) {
            $query->where('department_id', $user->department_id);
        } elseif (method_exists($user, 'hasRole') && $user->hasRole('Supervisor') && ! empty($user->department_id)) {
            $query->where('department_id', $user->department_id);
        } elseif (method_exists($user, 'isBranchScopedSupervisor') && $user->isBranchScopedSupervisor() && isset($user->branch_id)) {
            $query->where('branch_id', $user->branch_id);
        } elseif (method_exists($user, 'hasRole') && ($user->hasRole('Employee') || $user->hasRole('Customer'))) {
            $query->where('user_id', $user->id);
        } elseif (method_exists($user, 'hasRole') && $user->hasRole('Upper Management')) {
            $query->whereRaw('1 = 0');
        }

        return $query;
    }

    private function buildFeedbackSubmissionCount(array $filters, Authenticatable $user): int
    {
        if (method_exists($user, 'hasRole') && $user->hasRole('Upper Management')) {
            return (clone $this->buildComplaintQuery($filters, $user))->count();
        }

        $tickets = $this->buildFeedbackTicketQuery($filters, $user)->count();
        $authenticatedFeedback = $this->buildFeedbackQuery($filters, $user)->whereNotNull('user_id')->count();

        $customerComplaints = Complaint::query()
            ->whereHas('submitter', fn (Builder $submitter) => $submitter->role('Customer'))
            ->when(! empty($filters['branch']), fn ($query) => $query->where('branch_id', $filters['branch']))
            ->when(! empty($filters['department']), fn ($query) => $query->where('department_id', $filters['department']))
            ->when(! empty($filters['from_date']), fn ($query) => $query->whereDate('created_at', '>=', $filters['from_date']))
            ->when(! empty($filters['to_date']), fn ($query) => $query->whereDate('created_at', '<=', $filters['to_date']))
            ->when(! empty($filters['product']), function ($query) use ($filters): void {
                $branchIds = DB::table('branch_products')->where('product_id', $filters['product'])->pluck('branch_id');
                $query->whereIn('branch_id', $branchIds->isNotEmpty() ? $branchIds : [0]);
            })
            ->when(method_exists($user, 'hasRole') && $user->hasRole('Customer'), fn ($query) => $query->where('submitted_by_user_id', $user->getAuthIdentifier()))
            ->when(method_exists($user, 'isDepartmentScopedReviewer') && $user->isDepartmentScopedReviewer(), fn ($query) => $query->where('department_id', $user->department_id))
            ->when(method_exists($user, 'isBranchScopedSupervisor') && $user->isBranchScopedSupervisor() && isset($user->branch_id), fn ($query) => $query->where('branch_id', $user->branch_id))
            ->count();

        return $tickets + $authenticatedFeedback + $customerComplaints;
    }

    private function buildFeedbackTicketQuery(array $filters, Authenticatable $user): Builder
    {
        $query = FeedbackTicket::query()
            ->when(! empty($filters['branch']), fn ($query) => $query->where('branch_id', $filters['branch']))
            ->when(! empty($filters['department']), fn ($query) => $query->where('department_id', $filters['department']))
            ->when(! empty($filters['from_date']), fn ($query) => $query->whereDate('created_at', '>=', $filters['from_date']))
            ->when(! empty($filters['to_date']), fn ($query) => $query->whereDate('created_at', '<=', $filters['to_date']))
            ->when(! empty($filters['product']), function ($query) use ($filters): void {
                $branchIds = DB::table('branch_products')->where('product_id', $filters['product'])->pluck('branch_id');
                $query->whereIn('branch_id', $branchIds->isNotEmpty() ? $branchIds : [0]);
            })
            ->when(method_exists($user, 'isDepartmentScopedReviewer') && $user->isDepartmentScopedReviewer(), fn ($query) => $query->where('department_id', $user->department_id))
            ->when(method_exists($user, 'hasRole') && $user->hasRole('Supervisor') && ! empty($user->department_id), fn ($query) => $query->where('department_id', $user->department_id))
            ->when(method_exists($user, 'isBranchScopedSupervisor') && $user->isBranchScopedSupervisor() && isset($user->branch_id), fn ($query) => $query->where('branch_id', $user->branch_id));

        if (method_exists($user, 'hasRole') && ($user->hasRole('Upper Management') || $user->hasRole('Employee') || $user->hasRole('Customer'))) {
            $query->whereRaw('1 = 0');
        }

        return $query;
    }

    private function buildFeedbackDistribution(array $filters, Authenticatable $user): array
    {
        $query = $this->buildFeedbackTicketQuery($filters, $user);

        $counts = [
            'complaints' => (clone $query)->where('category', 'complaint')->count() + $this->authenticatedCustomerComplaintCount($filters, $user),
            'suggestions' => (clone $query)->where('category', 'suggestion')->count(),
            'compliments' => (clone $query)->whereIn('satisfaction', ['like', 'good', 'positive'])->where('category', '!=', 'suggestion')->count()
                + $this->buildFeedbackQuery($filters, $user)->whereNotNull('user_id')->where('sentiment', 'positive')->count(),
        ];
        $total = array_sum($counts);

        return collect($counts)
            ->map(fn (int $count, string $key) => [
                'key' => $key,
                'label' => __(ucfirst($key)),
                'total' => $count,
                'percent' => $total > 0 ? (int) round(($count / $total) * 100) : 0,
            ])
            ->values()
            ->all();
    }

    private function authenticatedCustomerComplaintCount(array $filters, Authenticatable $user): int
    {
        $query = Complaint::query()
            ->whereHas('submitter', fn (Builder $submitter) => $submitter->role('Customer'))
            ->when(! empty($filters['branch']), fn ($builder) => $builder->where('branch_id', $filters['branch']))
            ->when(! empty($filters['from_date']), fn ($builder) => $builder->whereDate('created_at', '>=', $filters['from_date']))
            ->when(! empty($filters['to_date']), fn ($builder) => $builder->whereDate('created_at', '<=', $filters['to_date']))
            ->when(method_exists($user, 'isDepartmentScopedReviewer') && $user->isDepartmentScopedReviewer(), fn ($builder) => $builder->where('department_id', $user->department_id))
            ->when(method_exists($user, 'hasRole') && $user->hasRole('Supervisor') && ! empty($user->department_id), fn ($builder) => $builder->where('department_id', $user->department_id))
            ->when(method_exists($user, 'isBranchScopedSupervisor') && $user->isBranchScopedSupervisor() && isset($user->branch_id), fn ($builder) => $builder->where('branch_id', $user->branch_id));

        return $this->applyUserScope($query, $user)->count();
    }

    private function buildDepartmentHighlights(array $filters, Authenticatable $user): array
    {
        $complaintCounts = $this->buildComplaintQuery($filters, $user)
            ->whereNotNull('department_id')
            ->select('department_id', DB::raw('COUNT(*) as total'))
            ->groupBy('department_id')
            ->pluck('total', 'department_id');

        $publicPositiveCounts = $this->buildFeedbackTicketQuery($filters, $user)
            ->whereIn('satisfaction', ['like', 'good', 'positive'])
            ->whereNotNull('department_id')
            ->select('department_id', DB::raw('COUNT(*) as total'))
            ->groupBy('department_id')
            ->pluck('total', 'department_id');

        $authenticatedPositiveCounts = $this->buildFeedbackQuery($filters, $user)
            ->where('sentiment', 'positive')
            ->whereNotNull('user_id')
            ->whereNotNull('department_id')
            ->select('department_id', DB::raw('COUNT(*) as total'))
            ->groupBy('department_id')
            ->pluck('total', 'department_id');
        $positiveFeedbackCounts = $publicPositiveCounts;
        foreach ($authenticatedPositiveCounts as $departmentId => $count) {
            $positiveFeedbackCounts->put(
                $departmentId,
                (int) $positiveFeedbackCounts->get($departmentId, 0) + (int) $count,
            );
        }

        $departmentIds = $complaintCounts->keys()
            ->merge($positiveFeedbackCounts->keys())
            ->unique()
            ->values();

        $departments = Department::with('branch')
            ->whereIn('id', $departmentIds)
            ->get()
            ->keyBy('id');

        return [
            'most_complaints' => $this->departmentHighlight($complaintCounts, $departments),
            'most_positive_feedback' => $this->departmentHighlight($positiveFeedbackCounts, $departments),
        ];
    }

    private function departmentHighlight(Collection $counts, Collection $departments): array
    {
        $departmentId = $counts->sortDesc()->keys()->first();
        $department = $departmentId ? $departments->get($departmentId) : null;

        return [
            'name' => $department?->name ?? __('No data yet'),
            'branch_name' => $department?->branch?->name,
            'count' => (int) ($departmentId ? $counts->get($departmentId, 0) : 0),
        ];
    }

    private function buildTrendData(array $filters, Authenticatable $user): array
    {
        $now = Carbon::now();
        $start = $now->copy()->subDays(6)->startOfDay();

        $query = $this->buildComplaintQuery($filters, $user)
            ->whereDate('created_at', '>=', $start);

        $counts = $query
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date')
            ->all();

        $labels = [];
        $values = [];

        for ($day = 0; $day < 7; $day++) {
            $date = $start->copy()->addDays($day);
            $key = $date->toDateString();
            $labels[] = $date->format('M j');
            $values[] = (int) ($counts[$key] ?? 0);
        }

        return ['labels' => $labels, 'values' => $values];
    }

    private function buildStatusDistribution(array $filters, Authenticatable $user): array
    {
        $statusData = $this->buildComplaintQuery($filters, $user)
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($item) => ['status' => $item->status, 'total' => (int) $item->total])
            ->all();

        return $statusData;
    }

    private function buildCategoryAnalytics(array $filters, Authenticatable $user): array
    {
        return $this->buildComplaintQuery($filters, $user)
            ->select('category', DB::raw('COUNT(*) as total'))
            ->groupBy('category')
            ->orderByDesc('total')
            ->limit(8)
            ->get()
            ->map(fn ($item) => ['category' => $item->category, 'total' => (int) $item->total])
            ->all();
    }

    private function buildBranchPerformance(array $filters, Authenticatable $user): array
    {
        $query = Branch::query()
            ->select([
                'branches.id',
                'branches.name',
                DB::raw('COUNT(complaints.id) as total_complaints'),
                DB::raw('SUM(CASE WHEN complaints.status IN ("resolved", "closed") THEN 1 ELSE 0 END) as resolved_count'),
                DB::raw('COUNT(DISTINCT escalated_complaints.complaint_id) as escalated_count'),
                DB::raw('SUM(CASE WHEN complaints.status = "reopened" THEN 1 ELSE 0 END) as reopened_count'),
                DB::raw('AVG(CASE WHEN complaints.closed_at IS NOT NULL THEN '.$this->resolutionDaysExpression().' ELSE NULL END) as avg_resolution_days'),
            ])
            ->join('complaints', 'branches.id', '=', 'complaints.branch_id')
            ->leftJoinSub($this->escalatedComplaintsSubquery(), 'escalated_complaints', function ($join): void {
                $join->on('escalated_complaints.complaint_id', '=', 'complaints.id');
            });

        if (! empty($filters['branch'])) {
            $query->where('branches.id', $filters['branch']);
        }

        if (! empty($filters['status'])) {
            $query->where('complaints.status', $filters['status']);
        }

        if (! empty($filters['category'])) {
            $query->where('complaints.category', $filters['category']);
        }

        if (! empty($filters['from_date'])) {
            $query->whereDate('complaints.created_at', '>=', $filters['from_date']);
        }

        if (! empty($filters['to_date'])) {
            $query->whereDate('complaints.created_at', '<=', $filters['to_date']);
        }

        if (! empty($filters['product'])) {
            $branchIds = DB::table('branch_products')
                ->where('product_id', $filters['product'])
                ->pluck('branch_id')
                ->all();

            $query->whereIn('branches.id', $branchIds ?: [0]);
        }

        $this->applyAggregateScope($query, $user);

        return $query->groupBy('branches.id')
            ->orderByDesc('total_complaints')
            ->limit(6)
            ->get()
            ->map(fn ($branch) => [
                'name' => $branch->name,
                'total_complaints' => (int) $branch->total_complaints,
                'resolved_count' => (int) $branch->resolved_count,
                'escalated_count' => (int) $branch->escalated_count,
                'reopened_count' => (int) $branch->reopened_count,
                'avg_resolution_days' => $branch->avg_resolution_days ? round($branch->avg_resolution_days, 1) : 0,
            ])
            ->all();
    }

    private function buildProductAnalytics(array $filters, Authenticatable $user): array
    {
        $baseQuery = DB::table('products')
            ->select([
                'products.id',
                'products.name',
                DB::raw('COUNT(DISTINCT complaints.id) as complaint_count'),
            ])
            ->join('branch_products', 'products.id', '=', 'branch_products.product_id')
            ->join('complaints', 'branch_products.branch_id', '=', 'complaints.branch_id')
            ->where('branch_products.status', 'active');

        if (! empty($filters['branch'])) {
            $baseQuery->where('complaints.branch_id', $filters['branch']);
        }

        if (! empty($filters['status'])) {
            $baseQuery->where('complaints.status', $filters['status']);
        }

        if (! empty($filters['category'])) {
            $baseQuery->where('complaints.category', $filters['category']);
        }

        if (! empty($filters['from_date'])) {
            $baseQuery->whereDate('complaints.created_at', '>=', $filters['from_date']);
        }

        if (! empty($filters['to_date'])) {
            $baseQuery->whereDate('complaints.created_at', '<=', $filters['to_date']);
        }

        if (! empty($filters['product'])) {
            $baseQuery->where('products.id', $filters['product']);
        }

        $this->applyAggregateScope($baseQuery, $user);

        return $baseQuery
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('complaint_count')
            ->limit(6)
            ->get()
            ->map(fn ($row) => ['name' => $row->name, 'complaint_count' => (int) $row->complaint_count])
            ->all();
    }

    private function buildSlaAnalytics(Builder $query): array
    {
        $totalOpen = (clone $query)
            ->whereNotIn('status', ['resolved', 'closed'])
            ->count();

        $overdue = (clone $query)
            ->whereNotNull('due_at')
            ->where('due_at', '<', Carbon::now())
            ->whereNotIn('status', ['resolved', 'closed'])
            ->count();

        $closed = (clone $query)
            ->whereIn('status', ['resolved', 'closed'])
            ->whereNotNull('closed_at');

        $resolvedCount = $closed->count();
        $onTime = (clone $closed)
            ->whereColumn('closed_at', '<=', 'due_at')
            ->count();

        $slaHitRate = $resolvedCount > 0 ? round(($onTime / $resolvedCount) * 100, 1) : 0;

        return [
            'open_sla' => $totalOpen,
            'overdue' => $overdue,
            'resolved_on_time' => $onTime,
            'sla_hit_rate' => $slaHitRate,
        ];
    }

    private function buildReopenedAnalytics(Builder $query): array
    {
        $reopened = (clone $query)->where('status', 'reopened')->count();
        $total = (clone $query)->count();

        return [
            'reopened_count' => $reopened,
            'reopened_rate' => $total > 0 ? round(($reopened / $total) * 100, 1) : 0,
        ];
    }

    private function buildBranchRanking(array $filters, Authenticatable $user): array
    {
        return $this->buildBranchPerformance($filters, $user);
    }

    private function applyAggregateScope($query, Authenticatable $user): void
    {
        if (method_exists($user, 'isDepartmentScopedReviewer') && $user->isDepartmentScopedReviewer()) {
            $query->where('complaints.department_id', $user->department_id);
        } elseif (method_exists($user, 'hasRole') && $user->hasRole('Supervisor') && isset($user->branch_id)) {
            $query->where('complaints.branch_id', $user->branch_id);
        } elseif (method_exists($user, 'hasRole') && $user->hasRole('Upper Management')) {
            $query->where('complaints.assigned_to_user_id', $user->getAuthIdentifier())
                ->where('complaints.escalation_level', 2);
        } elseif (method_exists($user, 'hasRole') && ($user->hasRole('Employee') || $user->hasRole('Customer'))) {
            $query->where(function ($builder) use ($user): void {
                $builder->where('complaints.submitted_by_user_id', $user->id)
                    ->orWhere('complaints.assigned_to_user_id', $user->id);
            });
        }
    }

    private function escalatedComplaintCount(Builder $query): int
    {
        return (clone $query)->where('escalation_level', '>', 0)->count();
    }

    private function escalatedComplaintsSubquery()
    {
        return DB::table('complaints')
            ->where('escalation_level', '>', 0)
            ->selectRaw('id as complaint_id');
    }

    private function resolutionDaysExpression(): string
    {
        return match (DB::connection()->getDriverName()) {
            'mysql', 'mariadb' => 'TIMESTAMPDIFF(HOUR, complaints.created_at, complaints.closed_at) / 24',
            'pgsql' => 'EXTRACT(EPOCH FROM (complaints.closed_at - complaints.created_at)) / 86400',
            default => 'JULIANDAY(complaints.closed_at) - JULIANDAY(complaints.created_at)',
        };
    }
}
