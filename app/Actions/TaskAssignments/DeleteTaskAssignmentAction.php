<?php

declare(strict_types=1);

namespace App\Actions\TaskAssignments;

use App\Actions\Concerns\AsAction;
use App\Models\TaskAssignment;
use App\Models\User;

class DeleteTaskAssignmentAction
{
    use AsAction;

    public function execute(User $actor, TaskAssignment $assignment): string
    {
        abort_unless($actor->canManageTaskAssignment($assignment), 403);

        $assignment->loadMissing('timeBlock');
        $date = $assignment->timeBlock?->date->toDateString() ?? today()->toDateString();

        $assignment->delete();

        return $date;
    }
}
