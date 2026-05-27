<?php

namespace App\Policies;

use App\Models\ProjectEntry;
use App\Models\User;

class ProjectEntryPolicy
{
    public function update(User $user, ProjectEntry $entry): bool
    {
        return $entry->team->isManagedBy($user) && $entry->status === ProjectEntry::DRAFT;
    }
}
