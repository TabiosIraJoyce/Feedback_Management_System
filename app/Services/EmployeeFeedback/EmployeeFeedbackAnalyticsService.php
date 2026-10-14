<?php

namespace App\Services\EmployeeFeedback;

use App\Models\EmployeeFeedback;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class EmployeeFeedbackAnalyticsService
{
    public function filters(): array
    {
        return [
            'status' => request('status'),
            'category' => request('category'),
            'priority' => request('priority'),
            'branch_id' => request('branch_id'),
            'search' => request('search'),
        ];
    }

    public function overview(User $user, array $filters): array
    {
        $query = $this->query($user, $filters);
        $total = (clone $query)->count();
        $pending = (clone $query)->where('status', 'pending')->count();
        $inProgress = (clone $query)->where('status', 'in_progress')->count();
        $resolved = (clone $query)->where('status', 'resolved')->count();
        $incidents = (clone $query)->where('category', 'incident_report')->count();

        return [
            'total' => $total,
            'pending' => $pending,
            'in_progress' => $inProgress,
            'resolved' => $resolved,
            'incidents' => $incidents,
        ];
    }

    public function statusDistribution(User $user, array $filters): array
    {
        return $this->query($user, $filters)
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($row) => ['status' => $row->status, 'total' => (int) $row->total])
            ->all();
    }

    public function categoryDistribution(User $user, array $filters): array
    {
        return $this->query($user, $filters)
            ->select('category', DB::raw('COUNT(*) as total'))
            ->groupBy('category')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($row) => ['category' => $row->category, 'total' => (int) $row->total])
            ->all();
    }

    public function query(User $user, array $filters): Builder
    {
        return EmployeeFeedback::query()
            ->with(['department.branch', 'user'])
            ->visibleTo($user)
            ->when($filters['status'] ?? null, fn ($query, $value) => $query->where('status', $value))
            ->when($filters['category'] ?? null, fn ($query, $value) => $query->where('category', $value))
            ->when($filters['priority'] ?? null, fn ($query, $value) => $query->where('priority', $value))
            ->when($filters['branch_id'] ?? null, fn ($query, $value) => $query->where('branch_id', $value))
            ->when($filters['search'] ?? null, function ($query, $value): void {
                $query->where(function ($query) use ($value): void {
                    $query->where('reference_no', 'like', "%{$value}%")
                        ->orWhere('subject', 'like', "%{$value}%")
                        ->orWhere('description', 'like', "%{$value}%");
                });
            });
    }
}
