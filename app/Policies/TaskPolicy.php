<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Site;
use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function viewAny(User $user): bool
    {
        $site = app()->bound(Site::class) ? app(Site::class) : null;

        return $site instanceof Site && $user->isSiteAdminFor($site);
    }

    public function create(User $user): bool
    {
        return $this->viewAny($user);
    }

    public function update(User $user, Task $task): bool
    {
        $site = app()->bound(Site::class) ? app(Site::class) : null;

        return $site instanceof Site
            && $user->isSiteAdminFor($site)
            && in_array($task->team_id, $site->teamIds(), true);
    }

    public function delete(User $user, Task $task): bool
    {
        return $this->update($user, $task);
    }
}
