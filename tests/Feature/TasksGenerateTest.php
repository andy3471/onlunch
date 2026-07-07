<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Site;
use App\Models\Task;
use App\Models\TaskAssignment;
use App\Models\Team;
use App\Models\User;
use App\Models\WorkingHourPreset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

class TasksGenerateTest extends TestCase
{
    use RefreshDatabase;

    public function test_generates_default_full_day_assignment_for_scheduled_users(): void
    {
        $site = Site::factory()->create();
        $team = Team::factory()->for($site)->create([
            'default_task' => 'In Office',
        ]);

        Task::create(['name' => 'In Office', 'team_id' => $team->id]);

        $preset = WorkingHourPreset::create([
            'team_id'    => $team->id,
            'name'       => 'Standard',
            'start_time' => '09:00:00',
            'end_time'   => '17:00:00',
            'sort_order' => 0,
        ]);

        $user = User::factory()->create();
        $team->members()->attach($user->id, [
            'working_hour_preset_id'    => $preset->id,
            'onboarding_completed_at'   => Date::now(),
            'is_scheduled'              => true,
        ]);

        Artisan::call('tasks:generate');

        $assignment = TaskAssignment::where('user_id', $user->id)->first();

        $this->assertNotNull($assignment);
        $this->assertSame('09:00', $assignment->timeBlock->start_time);
        $this->assertSame('17:00', $assignment->timeBlock->end_time);
    }
}
