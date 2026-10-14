<?php

namespace App\Policies;

use App\Models\Feedback;
use App\Models\User;

class FeedbackPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('manage feedback') || $user->can('submit feedback');
    }

    public function view(User $user, Feedback $feedback): bool
    {
        if ($user->can('manage feedback') && ! $user->isScopedReviewer()) {
            return true;
        }

        if ($user->isScopedReviewer()) {
            return $user->canReviewScopedRecord($feedback);
        }

        return (int) $feedback->user_id === (int) $user->id;
    }

    public function create(User $user): bool
    {
        return $user->can('submit feedback') || $user->can('manage feedback');
    }

    public function update(User $user, Feedback $feedback): bool
    {
        if ($user->can('manage feedback') && ! $user->isScopedReviewer()) {
            return true;
        }

        if ($user->isScopedReviewer()) {
            return $user->canReviewScopedRecord($feedback);
        }

        return (int) $feedback->user_id === (int) $user->id;
    }

    public function delete(User $user, Feedback $feedback): bool
    {
        return $user->can('manage feedback');
    }

    public function restore(User $user, Feedback $feedback): bool
    {
        return $user->can('manage feedback');
    }

    public function forceDelete(User $user, Feedback $feedback): bool
    {
        return $user->can('hard delete records');
    }
}
