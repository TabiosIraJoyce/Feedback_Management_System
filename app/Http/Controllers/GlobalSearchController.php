<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Complaint;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class GlobalSearchController extends Controller
{
    public function __invoke(Request $request): View
    {
        $query = trim((string) $request->query('query', ''));
        $user = $request->user();

        $complaints = collect();
        $branches = collect();

        if ($query !== '') {
            $complaintsQuery = Complaint::query()
                ->with(['branch', 'assignee'])
                ->where(function ($builder) use ($query): void {
                    $builder->where('reference_no', 'like', "%{$query}%")
                        ->orWhere('subject', 'like', "%{$query}%")
                        ->orWhere('message', 'like', "%{$query}%")
                        ->orWhere('customer_name', 'like', "%{$query}%")
                        ->orWhere('customer_email', 'like', "%{$query}%")
                        ->orWhere('category', 'like', "%{$query}%");
                });

            if ($user->isDepartmentScopedReviewer()) {
                $complaintsQuery->where('department_id', $user->department_id);
            } elseif ($user->hasRole('Supervisor') && $user->branch_id) {
                $complaintsQuery->where('branch_id', $user->branch_id);
            } elseif ($user->hasRole('Employee') || $user->hasRole('Customer')) {
                $complaintsQuery->where(function ($builder) use ($user): void {
                    $builder->where('submitted_by_user_id', $user->id)
                        ->orWhere('assigned_to_user_id', $user->id);
                });
            }

            $complaints = $complaintsQuery
                ->latest()
                ->limit(10)
                ->get();

            $branchesQuery = Branch::query()
                ->where(function ($builder) use ($query): void {
                    $builder->where('name', 'like', "%{$query}%")
                        ->orWhere('code', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%")
                        ->orWhere('address', 'like', "%{$query}%")
                        ->orWhere('email', 'like', "%{$query}%");
                });

            if ($user->isScopedReviewer() && $user->branch_id) {
                $branchesQuery->whereKey($user->branch_id);
            } elseif ($user->hasRole('Employee') || $user->hasRole('Customer')) {
                $branchesQuery->whereKey($user->branch_id ?: 0);
            }

            $branches = $branchesQuery
                ->orderBy('name')
                ->limit(10)
                ->get();
        }

        return view('search.index', [
            'query' => $query,
            'complaints' => $complaints,
            'branches' => $branches,
        ]);
    }
}
