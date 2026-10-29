<?php

namespace App\Policies;

use App\Models\Complaint;
use App\Models\User;

class ComplaintPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('manage complaints')
            || $user->can('track complaints')
            || $user->can('submit complaints');
    }

    public function view(User $user, Complaint $complaint): bool
    {
        if ($user->hasRole(['Admin', 'Super Admin'])) {
            return true;
        }

        if ((int) $complaint->submitted_by_user_id === (int) $user->id) {
            return true;
        }

        if ((int) $complaint->assigned_to_user_id !== (int) $user->id) {
            return false;
        }

        return match ((int) $complaint->escalation_level) {
            0 => $user->hasRole('Supervisor'),
            1 => $user->hasRole('Department Head') && (int) $user->department_id === (int) $complaint->department_id,
            2 => $user->hasRole('Upper Management'),
            default => false,
        };
    }

    public function create(User $user): bool
    {
        return ! $user->hasRole(['Employee', 'Admin', 'Super Admin', 'Upper Management'])
            && $user->can('submit complaints');
    }

    public function update(User $user, Complaint $complaint): bool
    {
        return (int) $complaint->assigned_to_user_id === (int) $user->id
            && match ((int) $complaint->escalation_level) {
                0 => $user->hasRole('Supervisor'),
                1 => $user->hasRole('Department Head') && (int) $user->department_id === (int) $complaint->department_id,
                2 => $user->hasRole('Upper Management'),
                default => false,
            }
            && ! in_array($complaint->status, ['closed', 'resolved'], true);
    }

    public function delete(User $user, Complaint $complaint): bool
    {
        return $user->hasRole(['Admin', 'Super Admin']);
    }

    public function restore(User $user, Complaint $complaint): bool
    {
        return $user->hasRole(['Admin', 'Super Admin']);
    }

    public function forceDelete(User $user, Complaint $complaint): bool
    {
        return $user->can('hard delete records');
    }
}
