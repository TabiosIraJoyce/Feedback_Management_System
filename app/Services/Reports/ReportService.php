<?php

namespace App\Services\Reports;

use App\Models\Branch;
use App\Models\Complaint;
use App\Models\Feedback;
use App\Models\Product;
use App\Models\SlaEscalation;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportService
{
    private const COMPLAINT_STATUSES = [
        'open' => 'Open',
        'in_progress' => 'In Progress',
        'resolved' => 'Resolved',
        'closed' => 'Closed',
        'reopened' => 'Reopened',
    ];

    private const FEEDBACK_STATUSES = [
        'submitted' => 'Submitted',
        'reviewed' => 'Reviewed',
        'archived' => 'Archived',
    ];

    public function reportDefinitions(): array
    {
        return [
            'complaint-summary' => 'Complaint Summary',
            'branch-performance' => 'Branch Performance',
            'product-services' => 'Product / Service',
            'sla-performance' => 'SLA Performance',
            'employee-feedback' => 'Employee Feedback',
            'complaint-trends' => 'Complaint Trends',
            'reopened-complaints' => 'Reopened Complaints',
            'escalation-reports' => 'Escalation Reports',
            'branch-rankings' => 'Branch Rankings',
        ];
    }

    public function filterOptions(Authenticatable $user, string $report = 'complaint-summary'): array
    {
        return [
            'branches' => Branch::query()
                ->when(method_exists($user, 'isScopedReviewer') && $user->isScopedReviewer() && isset($user->branch_id), fn ($query) => $query->whereKey($user->branch_id))
                ->orderBy('name')
                ->get(['id', 'name']),
            'products' => Product::orderBy('name')->get(['id', 'name']),
            'statuses' => $this->statusOptionsForReport($report),
            'categories' => Complaint::query()->select('category')->distinct()->orderBy('category')->pluck('category')->filter()->values(),
            'frequencies' => [
                'daily' => 'Daily',
                'weekly' => 'Weekly',
                'monthly' => 'Monthly',
            ],
            'formats' => [
                'pdf' => 'PDF',
                'csv' => 'CSV',
            ],
        ];
    }

    public function statusOptionsForReport(string $report): array
    {
        return $report === 'employee-feedback'
            ? self::FEEDBACK_STATUSES
            : self::COMPLAINT_STATUSES;
    }

    public function normalizeFilters(array $input): array
    {
        return [
            'report' => $input['report'] ?? 'complaint-summary',
            'branch' => $input['branch'] ?? null,
            'product' => $input['product'] ?? null,
            'status' => $input['status'] ?? null,
            'category' => $input['category'] ?? null,
            'from_date' => $input['from_date'] ?? null,
            'to_date' => $input['to_date'] ?? null,
        ];
    }

    public function widgets(array $filters, Authenticatable $user): array
    {
        $complaints = $this->complaintQuery($filters, $user);
        $feedback = $this->feedbackQuery($filters, $user);

        $totalComplaints = (clone $complaints)->count();
        $resolvedComplaints = (clone $complaints)->whereIn('status', ['resolved', 'closed'])->count();
        $escalatedComplaints = $this->escalatedComplaintCount($complaints);
        $overdueComplaints = (clone $complaints)
            ->whereNotNull('due_at')
            ->where('due_at', '<', now())
            ->whereNotIn('status', ['resolved', 'closed'])
            ->count();
        $feedbackCount = (clone $feedback)->count();

        return [
            'total_complaints' => $totalComplaints,
            'resolution_rate' => $totalComplaints > 0 ? round(($resolvedComplaints / $totalComplaints) * 100, 1) : 0,
            'escalation_rate' => $totalComplaints > 0 ? round(($escalatedComplaints / $totalComplaints) * 100, 1) : 0,
            'sla_hit_rate' => $this->slaHitRate($complaints),
            'overdue_complaints' => $overdueComplaints,
            'feedback_count' => $feedbackCount,
        ];
    }

    public function getReport(string $report, array $filters, Authenticatable $user, bool $paginate = true): array
    {
        return match ($report) {
            'complaint-summary' => $this->complaintSummaryReport($filters, $user, $paginate),
            'branch-performance' => $this->branchPerformanceReport($filters, $user, $paginate),
            'product-services' => $this->productServicesReport($filters, $user, $paginate),
            'sla-performance' => $this->slaPerformanceReport($filters, $user, $paginate),
            'employee-feedback' => $this->employeeFeedbackReport($filters, $user, $paginate),
            'complaint-trends' => $this->complaintTrendReport($filters, $user, $paginate),
            'reopened-complaints' => $this->reopenedComplaintsReport($filters, $user, $paginate),
            'escalation-reports' => $this->escalationReport($filters, $user, $paginate),
            'branch-rankings' => $this->branchRankingReport($filters, $user, $paginate),
            default => $this->complaintSummaryReport($filters, $user, $paginate),
        };
    }

    private function complaintSummaryReport(array $filters, Authenticatable $user, bool $paginate): array
    {
        $query = $this->complaintQuery($filters, $user)
            ->with(['branch:id,name', 'submitter:id,name', 'assignee:id,name'])
            ->select([
                'id',
                'reference_no',
                'subject',
                'customer_name',
                'branch_id',
                'submitted_by_user_id',
                'assigned_to_user_id',
                'status',
                'priority',
                'category',
                'created_at',
                'due_at',
                'closed_at',
            ])
            ->latest();

        return [
            'title' => 'Complaint Summary Report',
            'columns' => [
                ['key' => 'reference_no', 'label' => 'Reference'],
                ['key' => 'subject', 'label' => 'Subject'],
                ['key' => 'customer_name', 'label' => 'Customer'],
                ['key' => 'branch_name', 'label' => 'Branch'],
                ['key' => 'status', 'label' => 'Status'],
                ['key' => 'priority', 'label' => 'Priority'],
                ['key' => 'created_at', 'label' => 'Created'],
            ],
            'rows' => $this->finalizeRows($query, $paginate, function ($complaint) {
                return [
                    'reference_no' => $complaint->reference_no,
                    'subject' => $complaint->subject,
                    'customer_name' => $complaint->customer_name,
                    'branch_name' => $complaint->branch?->name ?? 'N/A',
                    'status' => $complaint->status,
                    'priority' => ucfirst($complaint->priority),
                    'created_at' => $complaint->created_at?->format('Y-m-d H:i'),
                ];
            }),
        ];
    }

    private function branchPerformanceReport(array $filters, Authenticatable $user, bool $paginate): array
    {
        $query = $this->branchAggregateQuery($filters, $user)->orderByDesc('total_complaints');

        return [
            'title' => 'Branch Performance Report',
            'columns' => [
                ['key' => 'name', 'label' => 'Branch'],
                ['key' => 'total_complaints', 'label' => 'Complaints'],
                ['key' => 'resolved_count', 'label' => 'Resolved'],
                ['key' => 'escalated_count', 'label' => 'Escalated'],
                ['key' => 'reopened_count', 'label' => 'Reopened'],
                ['key' => 'avg_resolution_days', 'label' => 'Avg Resolution Days'],
            ],
            'rows' => $this->finalizeRows($query, $paginate, fn ($row) => [
                'name' => $row->name,
                'total_complaints' => (int) $row->total_complaints,
                'resolved_count' => (int) $row->resolved_count,
                'escalated_count' => (int) $row->escalated_count,
                'reopened_count' => (int) $row->reopened_count,
                'avg_resolution_days' => round((float) $row->avg_resolution_days, 1),
            ]),
        ];
    }

    private function productServicesReport(array $filters, Authenticatable $user, bool $paginate): array
    {
        $query = $this->productAggregateQuery($filters, $user)->orderByDesc('complaint_count');

        return [
            'title' => 'Product / Service Report',
            'columns' => [
                ['key' => 'name', 'label' => 'Product / Service'],
                ['key' => 'business_type', 'label' => 'Business Type'],
                ['key' => 'complaint_count', 'label' => 'Complaints'],
                ['key' => 'resolved_count', 'label' => 'Resolved'],
                ['key' => 'active_branch_count', 'label' => 'Active Branches'],
            ],
            'rows' => $this->finalizeRows($query, $paginate, fn ($row) => [
                'name' => $row->name,
                'business_type' => $row->business_type ?? 'N/A',
                'complaint_count' => (int) $row->complaint_count,
                'resolved_count' => (int) $row->resolved_count,
                'active_branch_count' => (int) $row->active_branch_count,
            ]),
        ];
    }

    private function slaPerformanceReport(array $filters, Authenticatable $user, bool $paginate): array
    {
        $query = $this->complaintQuery($filters, $user)
            ->with('branch:id,name')
            ->whereNotNull('due_at')
            ->select([
                'id',
                'reference_no',
                'subject',
                'branch_id',
                'status',
                'due_at',
                'closed_at',
                'created_at',
            ])
            ->latest();

        return [
            'title' => 'SLA Performance Report',
            'columns' => [
                ['key' => 'reference_no', 'label' => 'Reference'],
                ['key' => 'subject', 'label' => 'Subject'],
                ['key' => 'branch_name', 'label' => 'Branch'],
                ['key' => 'status', 'label' => 'Status'],
                ['key' => 'sla_state', 'label' => 'SLA State'],
                ['key' => 'due_at', 'label' => 'Due'],
                ['key' => 'closed_at', 'label' => 'Closed'],
            ],
            'rows' => $this->finalizeRows($query, $paginate, function ($complaint) {
                $slaState = 'On Time';

                if (! in_array($complaint->status, ['resolved', 'closed'], true) && $complaint->due_at && $complaint->due_at->isPast()) {
                    $slaState = 'Overdue';
                } elseif ($complaint->closed_at && $complaint->due_at && $complaint->closed_at->gt($complaint->due_at)) {
                    $slaState = 'Breached';
                }

                return [
                    'reference_no' => $complaint->reference_no,
                    'subject' => $complaint->subject,
                    'branch_name' => $complaint->branch?->name ?? 'N/A',
                    'status' => $complaint->status,
                    'sla_state' => $slaState,
                    'due_at' => $complaint->due_at?->format('Y-m-d H:i'),
                    'closed_at' => $complaint->closed_at?->format('Y-m-d H:i') ?? '-',
                ];
            }),
        ];
    }

    private function employeeFeedbackReport(array $filters, Authenticatable $user, bool $paginate): array
    {
        $query = $this->feedbackQuery($filters, $user)
            ->with(['branch:id,name', 'user:id,name'])
            ->where('type', 'internal')
            ->latest();

        return [
            'title' => 'Employee Feedback Report',
            'columns' => [
                ['key' => 'subject', 'label' => 'Subject'],
                ['key' => 'name', 'label' => 'Submitted By'],
                ['key' => 'branch_name', 'label' => 'Branch'],
                ['key' => 'status', 'label' => 'Status'],
                ['key' => 'created_at', 'label' => 'Created'],
            ],
            'rows' => $this->finalizeRows($query, $paginate, fn ($feedback) => [
                'subject' => $feedback->subject,
                'name' => $feedback->name,
                'branch_name' => $feedback->branch?->name ?? 'N/A',
                'status' => $feedback->status,
                'created_at' => $feedback->created_at?->format('Y-m-d H:i'),
            ]),
        ];
    }

    private function complaintTrendReport(array $filters, Authenticatable $user, bool $paginate): array
    {
        $query = $this->complaintQuery($filters, $user)
            ->selectRaw('DATE(created_at) as report_date')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(CASE WHEN status IN ("resolved", "closed") THEN 1 ELSE 0 END) as resolved_count')
            ->selectRaw('SUM(CASE WHEN status = "reopened" THEN 1 ELSE 0 END) as reopened_count')
            ->selectRaw('SUM(CASE WHEN EXISTS (SELECT 1 FROM sla_escalations WHERE sla_escalations.complaint_id = complaints.id) THEN 1 ELSE 0 END) as escalated_count')
            ->groupBy('report_date')
            ->orderByDesc('report_date');

        return [
            'title' => 'Complaint Trend Report',
            'columns' => [
                ['key' => 'report_date', 'label' => 'Date'],
                ['key' => 'total', 'label' => 'Total'],
                ['key' => 'resolved_count', 'label' => 'Resolved'],
                ['key' => 'reopened_count', 'label' => 'Reopened'],
                ['key' => 'escalated_count', 'label' => 'Escalated'],
            ],
            'rows' => $this->finalizeRows($query, $paginate, fn ($row) => [
                'report_date' => $row->report_date,
                'total' => (int) $row->total,
                'resolved_count' => (int) $row->resolved_count,
                'reopened_count' => (int) $row->reopened_count,
                'escalated_count' => (int) $row->escalated_count,
            ]),
        ];
    }

    private function reopenedComplaintsReport(array $filters, Authenticatable $user, bool $paginate): array
    {
        $query = $this->complaintQuery($filters, $user)
            ->with('branch:id,name')
            ->where(function ($builder) {
                $builder->where('status', 'reopened')
                    ->orWhereExists(function ($subquery) {
                        $subquery->selectRaw('1')
                            ->from('complaint_status_histories')
                            ->whereColumn('complaint_status_histories.complaint_id', 'complaints.id')
                            ->where('complaint_status_histories.status', 'reopened');
                    });
            })
            ->latest();

        return [
            'title' => 'Reopened Complaint Report',
            'columns' => [
                ['key' => 'reference_no', 'label' => 'Reference'],
                ['key' => 'subject', 'label' => 'Subject'],
                ['key' => 'branch_name', 'label' => 'Branch'],
                ['key' => 'status', 'label' => 'Current Status'],
                ['key' => 'created_at', 'label' => 'Created'],
            ],
            'rows' => $this->finalizeRows($query, $paginate, fn ($complaint) => [
                'reference_no' => $complaint->reference_no,
                'subject' => $complaint->subject,
                'branch_name' => $complaint->branch?->name ?? 'N/A',
                'status' => $complaint->status,
                'created_at' => $complaint->created_at?->format('Y-m-d H:i'),
            ]),
        ];
    }

    private function escalationReport(array $filters, Authenticatable $user, bool $paginate): array
    {
        $query = SlaEscalation::query()
            ->join('complaints', 'complaints.id', '=', 'sla_escalations.complaint_id')
            ->join('branches', 'branches.id', '=', 'complaints.branch_id')
            ->select([
                'sla_escalations.id',
                'sla_escalations.old_status',
                'sla_escalations.new_status',
                'sla_escalations.note',
                'sla_escalations.notified',
                'sla_escalations.created_at',
                'complaints.reference_no',
                'complaints.subject',
                'branches.name as branch_name',
            ]);

        if (! empty($filters['branch'])) {
            $query->where('complaints.branch_id', $filters['branch']);
        }

        if (! empty($filters['status'])) {
            $query->where('complaints.status', $filters['status']);
        }

        if (! empty($filters['category'])) {
            $query->where('complaints.category', $filters['category']);
        }

        if (! empty($filters['from_date'])) {
            $query->whereDate('sla_escalations.created_at', '>=', $filters['from_date']);
        }

        if (! empty($filters['to_date'])) {
            $query->whereDate('sla_escalations.created_at', '<=', $filters['to_date']);
        }

        if (method_exists($user, 'isDepartmentScopedReviewer') && $user->isDepartmentScopedReviewer()) {
            $query->where('complaints.department_id', $user->department_id);
        } elseif (method_exists($user, 'hasRole') && $user->hasRole('Supervisor') && isset($user->branch_id)) {
            $query->where('complaints.branch_id', $user->branch_id);
        } elseif (method_exists($user, 'hasRole') && ($user->hasRole('Employee') || $user->hasRole('Customer'))) {
            $query->where(function ($builder) use ($user): void {
                $builder->where('complaints.submitted_by_user_id', $user->id)
                    ->orWhere('complaints.assigned_to_user_id', $user->id);
            });
        }

        return [
            'title' => 'Escalation Report',
            'columns' => [
                ['key' => 'reference_no', 'label' => 'Reference'],
                ['key' => 'subject', 'label' => 'Subject'],
                ['key' => 'branch_name', 'label' => 'Branch'],
                ['key' => 'old_status', 'label' => 'From'],
                ['key' => 'new_status', 'label' => 'To'],
                ['key' => 'created_at', 'label' => 'Escalated At'],
            ],
            'rows' => $this->finalizeRows($query->orderByDesc('sla_escalations.created_at'), $paginate, fn ($row) => [
                'reference_no' => $row->reference_no,
                'subject' => $row->subject,
                'branch_name' => $row->branch_name,
                'old_status' => $row->old_status,
                'new_status' => $row->new_status,
                'created_at' => Carbon::parse($row->created_at)->format('Y-m-d H:i'),
            ]),
        ];
    }

    private function branchRankingReport(array $filters, Authenticatable $user, bool $paginate): array
    {
        $rows = $this->finalizeRows($this->branchAggregateQuery($filters, $user)->orderByDesc('resolved_count'), $paginate, fn ($row) => [
            'name' => $row->name,
            'total_complaints' => (int) $row->total_complaints,
            'resolved_count' => (int) $row->resolved_count,
            'resolution_rate' => (int) $row->total_complaints > 0 ? round(($row->resolved_count / $row->total_complaints) * 100, 1) : 0,
            'sla_hit_rate' => $this->branchSlaRate($row->branch_id, $filters, $user),
        ]);

        $rankedRows = $this->applyRanking($rows);

        return [
            'title' => 'Branch Rankings Report',
            'columns' => [
                ['key' => 'rank', 'label' => 'Rank'],
                ['key' => 'name', 'label' => 'Branch'],
                ['key' => 'total_complaints', 'label' => 'Complaints'],
                ['key' => 'resolved_count', 'label' => 'Resolved'],
                ['key' => 'resolution_rate', 'label' => 'Resolution Rate'],
                ['key' => 'sla_hit_rate', 'label' => 'SLA Hit Rate'],
            ],
            'rows' => $rankedRows,
        ];
    }

    private function complaintQuery(array $filters, Authenticatable $user): Builder
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

        return $this->applyComplaintScope($query, $user);
    }

    private function feedbackQuery(array $filters, Authenticatable $user): Builder
    {
        $query = Feedback::query();

        if (! empty($filters['branch'])) {
            $query->where('branch_id', $filters['branch']);
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['from_date'])) {
            $query->whereDate('created_at', '>=', $filters['from_date']);
        }

        if (! empty($filters['to_date'])) {
            $query->whereDate('created_at', '<=', $filters['to_date']);
        }

        if (method_exists($user, 'isDepartmentScopedReviewer') && $user->isDepartmentScopedReviewer()) {
            $query->where('department_id', $user->department_id);
        } elseif (method_exists($user, 'hasRole') && $user->hasRole('Supervisor') && isset($user->branch_id)) {
            $query->where('branch_id', $user->branch_id);
        } elseif (method_exists($user, 'hasRole') && ($user->hasRole('Employee') || $user->hasRole('Customer'))) {
            $query->where('user_id', $user->id);
        }

        return $query;
    }

    private function applyComplaintScope(Builder $query, Authenticatable $user): Builder
    {
        if (method_exists($user, 'isDepartmentScopedReviewer') && $user->isDepartmentScopedReviewer()) {
            $query->where('department_id', $user->department_id);
        } elseif (method_exists($user, 'hasRole') && $user->hasRole('Supervisor') && isset($user->branch_id)) {
            $query->where('branch_id', $user->branch_id);
        } elseif (method_exists($user, 'hasRole') && ($user->hasRole('Employee') || $user->hasRole('Customer'))) {
            $query->where(function (Builder $scope) use ($user): void {
                $scope->where('submitted_by_user_id', $user->id)
                    ->orWhere('assigned_to_user_id', $user->id);
            });
        }

        return $query;
    }

    private function branchAggregateQuery(array $filters, Authenticatable $user)
    {
        $query = DB::table('branches')
            ->join('complaints', 'branches.id', '=', 'complaints.branch_id')
            ->leftJoinSub($this->escalatedComplaintsSubquery(), 'escalated_complaints', function ($join): void {
                $join->on('escalated_complaints.complaint_id', '=', 'complaints.id');
            })
            ->selectRaw('branches.id as branch_id, branches.name')
            ->selectRaw('COUNT(complaints.id) as total_complaints')
            ->selectRaw('SUM(CASE WHEN complaints.status IN ("resolved", "closed") THEN 1 ELSE 0 END) as resolved_count')
            ->selectRaw('COUNT(DISTINCT escalated_complaints.complaint_id) as escalated_count')
            ->selectRaw('SUM(CASE WHEN complaints.status = "reopened" THEN 1 ELSE 0 END) as reopened_count')
            ->selectRaw('AVG(CASE WHEN complaints.closed_at IS NOT NULL THEN '.$this->resolutionDaysExpression().' ELSE NULL END) as avg_resolution_days')
            ->groupBy('branches.id', 'branches.name');

        $this->applyComplaintAggregateFilters($query, $filters, $user);

        return $query;
    }

    private function productAggregateQuery(array $filters, Authenticatable $user)
    {
        $query = DB::table('products')
            ->join('branch_products', 'products.id', '=', 'branch_products.product_id')
            ->join('complaints', 'branch_products.branch_id', '=', 'complaints.branch_id')
            ->selectRaw('products.id, products.name')
            ->selectRaw('COUNT(DISTINCT complaints.id) as complaint_count')
            ->selectRaw('SUM(CASE WHEN complaints.status IN ("resolved", "closed") THEN 1 ELSE 0 END) as resolved_count')
            ->selectRaw('COUNT(DISTINCT CASE WHEN branch_products.status = "active" THEN branch_products.branch_id END) as active_branch_count')
            ->groupBy('products.id', 'products.name');

        if (! empty($filters['product'])) {
            $query->where('products.id', $filters['product']);
        }

        $this->applyComplaintAggregateFilters($query, $filters, $user);

        return $query;
    }

    private function applyComplaintAggregateFilters($query, array $filters, Authenticatable $user): void
    {
        if (! empty($filters['branch'])) {
            $query->where('complaints.branch_id', $filters['branch']);
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

        if (method_exists($user, 'isDepartmentScopedReviewer') && $user->isDepartmentScopedReviewer()) {
            $query->where('complaints.department_id', $user->department_id);
        } elseif (method_exists($user, 'hasRole') && $user->hasRole('Supervisor') && isset($user->branch_id)) {
            $query->where('complaints.branch_id', $user->branch_id);
        } elseif (method_exists($user, 'hasRole') && ($user->hasRole('Employee') || $user->hasRole('Customer'))) {
            $query->where(function ($builder) use ($user): void {
                $builder->where('complaints.submitted_by_user_id', $user->id)
                    ->orWhere('complaints.assigned_to_user_id', $user->id);
            });
        }
    }

    private function finalizeRows($query, bool $paginate, callable $transform)
    {
        if ($paginate) {
            /** @var LengthAwarePaginator $paginator */
            $paginator = $query->paginate(12)->withQueryString();

            return new Paginator(
                $paginator->getCollection()->map($transform),
                $paginator->total(),
                $paginator->perPage(),
                $paginator->currentPage(),
                ['path' => request()->url(), 'query' => request()->query()]
            );
        }

        return $query->get()->map($transform)->values();
    }

    private function branchSlaRate(int $branchId, array $filters, Authenticatable $user): float
    {
        $closed = $this->complaintQuery([...$filters, 'branch' => $branchId], $user)
            ->whereIn('status', ['resolved', 'closed'])
            ->whereNotNull('due_at')
            ->whereNotNull('closed_at');

        $resolvedCount = (clone $closed)->count();
        $hit = (clone $closed)->whereColumn('closed_at', '<=', 'due_at')->count();

        return $resolvedCount > 0 ? round(($hit / $resolvedCount) * 100, 1) : 0;
    }

    private function slaHitRate(Builder $query): float
    {
        $closed = (clone $query)
            ->whereIn('status', ['resolved', 'closed'])
            ->whereNotNull('due_at')
            ->whereNotNull('closed_at');

        $resolvedCount = (clone $closed)->count();
        $hit = (clone $closed)->whereColumn('closed_at', '<=', 'due_at')->count();

        return $resolvedCount > 0 ? round(($hit / $resolvedCount) * 100, 1) : 0;
    }

    private function applyRanking($rows)
    {
        if ($rows instanceof LengthAwarePaginator) {
            $ranked = $rows->getCollection()->values()->map(function ($row, $index) {
                $row['rank'] = $index + 1;
                return $row;
            });

            return new Paginator(
                $ranked,
                $rows->total(),
                $rows->perPage(),
                $rows->currentPage(),
                ['path' => request()->url(), 'query' => request()->query()]
            );
        }

        return $rows->values()->map(function ($row, $index) {
            $row['rank'] = $index + 1;

            return $row;
        });
    }

    private function escalatedComplaintCount(Builder $query): int
    {
        return (clone $query)
            ->whereExists(function ($subquery) {
                $subquery->selectRaw('1')
                    ->from('sla_escalations')
                    ->whereColumn('sla_escalations.complaint_id', 'complaints.id');
            })
            ->count();
    }

    private function escalatedComplaintsSubquery()
    {
        return DB::table('sla_escalations')
            ->select('complaint_id')
            ->distinct();
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
