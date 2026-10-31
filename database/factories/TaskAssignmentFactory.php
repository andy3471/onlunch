<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Task;
use App\Models\TaskAssignment;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TaskAssignment>
 */
class TaskAssignmentFactory extends Factory
{
    protected $model = TaskAssignment::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'team_id' => Team::factory(),
            'user_id' => User::factory(),
            'task_id' => null,
        ];
    }

    public function configure(): static
    {
        return $this
            ->afterMaking(function (TaskAssignment $assignment): void {
                if ($assignment->task_id !== null || $assignment->team_id === null) {
                    return;
                }

                $task                = Task::factory()->create(['team_id' => $assignment->team_id]);
                $assignment->task_id = $task->id;
            })
            ->afterCreating(function (TaskAssignment $assignment): void {
                if ($assignment->timeBlock()->exists()) {
                    return;
                }

                $assignment->timeBlock()->create([
                    'team_id'    => $assignment->team_id,
                    'user_id'    => $assignment->user_id,
                    'date'       => now()->toDateString(),
                    'start_time' => '09:00:00',
                    'end_time'   => '17:00:00',
                ]);
            });
    }

    public function away(): static
    {
        return $this->state(fn (array $attributes): array => [
            'task_id' => null,
        ])->afterMaking(function (TaskAssignment $assignment): void {
            $assignment->task_id = null;
        });
    }
}
