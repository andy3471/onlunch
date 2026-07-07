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

class StoreTaskAssignmentAction
{
    use AsAction;

    public function __construct(
        private readonly AssertMinimumAvailabilityAction $assertMinimumAvailability,
        private readonly AssertNoScheduleOverlapAction $assertNoScheduleOverlap,
    ) {}

    public function execute(
        Team $team,
        User $user,
        string $date,
        string $startTime,
        string $endTime,
        ?string $taskId,
    ): TaskAssignment {
        if ($taskId === null) {
            $this->assertMinimumAvailability->execute(
                $team,
                $date,
                $startTime,
                $endTime,
                $user->id,
            );
        }

        $this->assertNoScheduleOverlap->execute($user, $date, $startTime, $endTime);

        return TaskAssignment::createWithTimeBlock(
            [
                'team_id' => $team->id,
                'user_id' => $user->id,
                'task_id' => $taskId,
            ],
            [
                'date'       => $date,
                'start_time' => TimeInput::forStorage($startTime),
                'end_time'   => TimeInput::forStorage($endTime),
            ],
        );
    }
}
