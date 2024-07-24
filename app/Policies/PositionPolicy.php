<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class PositionPolicy
{
    use HasBasePolicy;

    public function finish(User $user, Model $model)
    {
        return $this->owner($user, $model);
    }
}
