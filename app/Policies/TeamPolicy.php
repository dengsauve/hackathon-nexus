<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;

class TeamPolicy
{
    public function manage(User $user, Team $team): bool
    {
        return $team->isManagedBy($user);
    }
}
