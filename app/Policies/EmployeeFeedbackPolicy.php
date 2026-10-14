<?php

namespace App\Policies;

use App\Models\EmployeeFeedback;
use App\Models\User;

class EmployeeFeedbackPolicy
{
    public function viewAny(User $user): bool
    {
        return ($user->can('manage feedback') || $user->can('submit feedback'))
            && ! $user->hasRole('Customer');
    }

    public function view(User $user, EmployeeFeedback $feedback): bool
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
        return ($user->can('submit feedback') || $user->can('manage feedback'))
            && ! $user->hasRole('Customer');
    }

    public function update(User $user, EmployeeFeedback $feedback): bool
    {
        if ($user->can('manage feedback') && ! $user->isScopedReviewer()) {
            return true;
        }

        if ($user->isScopedReviewer()) {
            return $user->canReviewScopedRecord($feedback);
        }

        return (int) $feedback->user_id === (int) $user->id
            && in_array($feedback->status, ['pending', 'under_review'], true);
    }

    public function review(User $user, EmployeeFeedback $feedback): bool
    {
        if (! $user->can('manage feedback')) {
            return false;
        }

        if ($user->isScopedReviewer()) {
            return $user->canReviewScopedRecord($feedback);
        }

        return true;
    }

    public function reopen(User $user, EmployeeFeedback $feedback): bool
    {
        return $this->view($user, $feedback);
    }

    public function close(User $user, EmployeeFeedback $feedback): bool
    {
        return $this->review($user, $feedback);
    }

    public function delete(User $user, EmployeeFeedback $feedback): bool
    {
        return $user->can('manage feedback') || ((int) $feedback->user_id === (int) $user->id && $feedback->status === 'pending');
    }
}
