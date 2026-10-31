<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\TaskAssignment;
use App\Models\User;

class TaskAssignmentPolicy
{
    public function update(User $user, TaskAssignment $assignment): bool
    {
        return $user->canManageTaskAssignment($assignment);
    }

    public function delete(User $user, TaskAssignment $assignment): bool
    {
        return $user->canManageTaskAssignment($assignment);
    }
}
