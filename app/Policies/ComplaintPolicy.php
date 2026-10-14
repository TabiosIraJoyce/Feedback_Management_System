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
        if ($user->can('manage complaints') && ! $user->isScopedReviewer()) {
            return true;
        }

        if ($user->isScopedReviewer()) {
            return $user->canReviewScopedRecord($complaint);
        }

        return (int) $complaint->submitted_by_user_id === (int) $user->id
            || (int) $complaint->assigned_to_user_id === (int) $user->id;
    }

    public function create(User $user): bool
    {
        return $user->can('submit complaints') || $user->can('manage complaints');
    }

    public function update(User $user, Complaint $complaint): bool
    {
        if ($user->can('manage complaints') && ! $user->isScopedReviewer()) {
            return true;
        }

        if ($user->isScopedReviewer()) {
            return $user->canReviewScopedRecord($complaint);
        }

        return (int) $complaint->submitted_by_user_id === (int) $user->id
            && ! in_array($complaint->status, ['closed', 'resolved'], true);
    }

    public function delete(User $user, Complaint $complaint): bool
    {
        return $user->can('manage complaints') && ! $user->isScopedReviewer();
    }

    public function restore(User $user, Complaint $complaint): bool
    {
        return $user->can('manage complaints');
    }

    public function forceDelete(User $user, Complaint $complaint): bool
    {
        return $user->can('hard delete records');
    }
}
