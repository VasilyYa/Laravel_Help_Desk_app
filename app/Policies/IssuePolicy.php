<?php

namespace App\Policies;

use App\Models\Issue;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class IssuePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user)
    {
        return $user->isAdmin() || $user->isSeniorManager();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Issue $issue
     * @return bool
     */
    public function view(User $user, Issue $issue)
    {
        return $user->isAdmin() ||
            $user->isSeniorManager() ||
            ($user->isManager() && !$issue->isAttached()) ||
            $user->isManagerOfIssue($issue) ||
            $user->isClientOfIssue($issue);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->isClient();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Issue $issue
     * @return bool
     */
    public function update(User $user, Issue $issue)
    {
        return $user->isClientOfIssue($issue) || $user->isSeniorManager();
    }

    /**
     * Determine whether the user can attach issue to manager.
     *
     * @param User $user
     * @param Issue $issue
     * @return bool
     */
    public function attach(User $user, Issue $issue)
    {
        return (auth()->user()->isManager() && !$issue->isAttached()) ||
            auth()->user()->isSeniorManager() ||
            auth()->user()->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Issue $issue
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Issue $issue)
    {
        return $user->isClientOfIssue($issue);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Issue $issue
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Issue $issue)
    {
        return $user->isSeniorManager();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Issue $issue
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Issue $issue)
    {
        return $user->isAdmin();
    }
}
