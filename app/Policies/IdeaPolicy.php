<?php

namespace App\Policies;

use App\Models\Idea;
use App\Models\User;

class IdeaPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Idea $idea): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function vote(User $user, Idea $idea): bool
    {
        return true;
    }

    public function update(User $user, Idea $idea): bool
    {
        return $user->id === $idea->user_id;
    }

    public function delete(User $user, Idea $idea): bool
    {
        return $user->id === $idea->user_id;
    }

    public function restore(User $user, Idea $idea): bool
    {
        return $user->id === $idea->user_id;
    }

    public function forceDelete(User $user, Idea $idea): bool
    {
        return $user->id === $idea->user_id;
    }
}
