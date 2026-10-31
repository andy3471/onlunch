<?php

declare(strict_types=1);

namespace App\Actions\TaskAssignments;

use App\Actions\Availability\AssertMinimumAvailabilityAction;
use App\Actions\Concerns\AsAction;
use App\Actions\Schedule\AssertNoScheduleOverlapAction;
use App\Models\TaskAssignment;
use App\Models\Team;
use App\Models\User;
use App\Support\TimeInput;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UpdateTaskAssignmentAction
{
    use AsAction;

    public function __construct(
        private readonly AssertMinimumAvailabilityAction $assertMinimumAvailability,
        private readonly AssertNoScheduleOverlapAction $assertNoScheduleOverlap,
    ) {}

    public function execute(
        Team $team,
        User $actor,
        TaskAssignment $assignment,
        string $startTime,
        string $endTime,
        ?string $taskId,
    ): TaskAssignment {
        $assignment->loadMissing('timeBlock');
        $block = $assignment->timeBlock;

        if ($block === null) {
            throw (new ModelNotFoundException)->setModel(TaskAssignment::class, [$assignment->id]);
        }

        if ($taskId === null) {
            $this->assertMinimumAvailability->execute(
                $team,
                $block->date->toDateString(),
                $startTime,
                $endTime,
                $assignment->user_id,
            );
        }

        $this->assertNoScheduleOverlap->execute(
            $assignment->user,
            $block->date->toDateString(),
            $startTime,
            $endTime,
            $block->id,
        );

        $assignment->update(['task_id' => $taskId]);

        $block->update([
            'start_time' => TimeInput::forStorage($startTime),
            'end_time'   => TimeInput::forStorage($endTime),
        ]);

        return $assignment->refresh();
    }
}
