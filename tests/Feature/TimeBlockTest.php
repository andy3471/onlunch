<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Task;
use App\Models\TaskAssignment;
use App\Models\Team;
use App\Models\TimeBlock;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class TimeBlockTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_assignment_creates_morphed_time_block(): void
    {
        $team = Team::factory()->create();
        $user = User::factory()->create();
        $task = Task::create(['name' => 'In Office', 'team_id' => $team->id]);

        $assignment = TaskAssignment::createWithTimeBlock(
            ['team_id' => $team->id, 'user_id' => $user->id, 'task_id' => $task->id],
            ['date' => '2026-07-03', 'start_time' => '09:00', 'end_time' => '17:00'],
        );

        $this->assertDatabaseHas('time_blocks', [
            'team_id'        => $team->id,
            'user_id'        => $user->id,
            'blockable_type' => TaskAssignment::class,
            'blockable_id'   => $assignment->id,
        ]);

        $this->assertSame('09:00', $assignment->timeBlock->start_time);
        $this->assertSame('17:00', $assignment->timeBlock->end_time);
    }

    public function test_start_must_be_before_end(): void
    {
        $team = Team::factory()->create();
        $user = User::factory()->create();

        $this->expectException(InvalidArgumentException::class);

        TaskAssignment::createWithTimeBlock(
            ['team_id' => $team->id, 'user_id' => $user->id, 'task_id' => null],
            ['date' => '2026-07-03', 'start_time' => '17:00', 'end_time' => '09:00'],
        );
    }

    public function test_deleting_parent_deletes_time_block(): void
    {
        $team = Team::factory()->create();
        $user = User::factory()->create();

        $assignment = TaskAssignment::createWithTimeBlock(
            ['team_id' => $team->id, 'user_id' => $user->id, 'task_id' => null],
            ['date' => '2026-07-03', 'start_time' => '09:00', 'end_time' => '12:00'],
        );

        $blockId = $assignment->timeBlock->id;
        $assignment->delete();

        $this->assertDatabaseMissing('time_blocks', ['id' => $blockId]);
    }

    public function test_overlapping_entries_are_rejected(): void
    {
        $team = Team::factory()->create();
        $user = User::factory()->create();

        TaskAssignment::createWithTimeBlock(
            ['team_id' => $team->id, 'user_id' => $user->id, 'task_id' => null],
            ['date' => '2026-07-03', 'start_time' => '09:00', 'end_time' => '12:00'],
        );

        $this->expectException(\Illuminate\Validation\ValidationException::class);

        resolve(\App\Actions\TaskAssignments\StoreTaskAssignmentAction::class)->execute(
            $team,
            $user,
            '2026-07-03',
            '10:00',
            '11:00',
            null,
        );
    }

    public function test_adjacent_entries_are_allowed(): void
    {
        $team = Team::factory()->create();
        $user = User::factory()->create();

        resolve(\App\Actions\TaskAssignments\StoreTaskAssignmentAction::class)->execute(
            $team,
            $user,
            '2026-07-03',
            '09:00',
            '12:00',
            null,
        );

        resolve(\App\Actions\TaskAssignments\StoreTaskAssignmentAction::class)->execute(
            $team,
            $user,
            '2026-07-03',
            '12:00',
            '13:00',
            null,
        );

        $this->assertSame(2, TimeBlock::where('user_id', $user->id)->count());
    }
}
