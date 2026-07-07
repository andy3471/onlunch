<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Task;
use App\Models\TaskAssignment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskAssignmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_store_task_assignment(): void
    {
        [$site, $team] = $this->createSiteWithTeam(['slug' => 'acme']);
        $user          = User::factory()->create();
        $this->attachOnboardedMember($site, $team, $user);
        Task::create(['name' => 'Deep Work', 'team_id' => $team->id]);

        $response = $this->actingAs($user)->post('http://acme.localhost/task-assignments', [
            'date'       => now()->addDay()->toDateString(),
            'start_time' => '09:00',
            'end_time'   => '12:00',
            'task_name'  => 'Deep Work',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('task_assignments', [
            'user_id' => $user->id,
            'task_id' => Task::query()->where('name', 'Deep Work')->value('id'),
        ]);
    }

    public function test_user_can_store_custom_task_name(): void
    {
        [$site, $team] = $this->createSiteWithTeam(['slug' => 'acme']);
        $user          = User::factory()->create();
        $this->attachOnboardedMember($site, $team, $user);

        $response = $this->actingAs($user)->post('http://acme.localhost/task-assignments', [
            'date'       => now()->addDay()->toDateString(),
            'start_time' => '09:00',
            'end_time'   => '12:00',
            'task_name'  => 'Client call with Acme',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tasks', [
            'team_id' => $team->id,
            'name'    => 'Client call with Acme',
        ]);
    }

    public function test_user_can_delete_task_assignment(): void
    {
        [$site, $team] = $this->createSiteWithTeam(['slug' => 'acme']);
        $user          = User::factory()->create();
        $this->attachOnboardedMember($site, $team, $user);
        $task = Task::create(['name' => 'Deep Work', 'team_id' => $team->id]);

        $assignment = TaskAssignment::createWithTimeBlock(
            ['team_id' => $team->id, 'user_id' => $user->id, 'task_id' => $task->id],
            ['date' => now()->addDay()->toDateString(), 'start_time' => '09:00', 'end_time' => '12:00'],
        );

        $response = $this->actingAs($user)->delete(route('task-assignments.destroy', [
            'tenant'         => 'acme',
            'taskAssignment' => $assignment->id,
        ]));

        $response->assertRedirect();
        $this->assertDatabaseMissing('task_assignments', ['id' => $assignment->id]);
    }

    public function test_user_can_update_task_assignment_times(): void
    {
        [$site, $team] = $this->createSiteWithTeam(['slug' => 'acme']);
        $user          = User::factory()->create();
        $this->attachOnboardedMember($site, $team, $user);
        $task = Task::create(['name' => 'Deep Work', 'team_id' => $team->id]);

        $assignment = TaskAssignment::createWithTimeBlock(
            ['team_id' => $team->id, 'user_id' => $user->id, 'task_id' => $task->id],
            ['date' => now()->addDay()->toDateString(), 'start_time' => '09:00', 'end_time' => '12:00'],
        );

        $response = $this->actingAs($user)->put(route('task-assignments.update', [
            'tenant'         => 'acme',
            'taskAssignment' => $assignment->id,
        ]), [
            'start_time' => '10:00',
            'end_time'   => '11:30',
            'task_name'  => 'Deep Work',
        ]);

        $response->assertRedirect();
        $this->assertSame('10:00', $assignment->refresh()->timeBlock->start_time);
        $this->assertSame('11:30', $assignment->timeBlock->end_time);
    }

    public function test_user_can_rename_task_assignment_to_new_custom_name(): void
    {
        [$site, $team] = $this->createSiteWithTeam(['slug' => 'acme']);
        $user          = User::factory()->create();
        $this->attachOnboardedMember($site, $team, $user);
        $task = Task::create(['name' => 'Deep Work', 'team_id' => $team->id]);

        $assignment = TaskAssignment::createWithTimeBlock(
            ['team_id' => $team->id, 'user_id' => $user->id, 'task_id' => $task->id],
            ['date' => now()->addDay()->toDateString(), 'start_time' => '09:00', 'end_time' => '12:00'],
        );

        $response = $this->actingAs($user)->put(route('task-assignments.update', [
            'tenant'         => 'acme',
            'taskAssignment' => $assignment->id,
        ]), [
            'start_time' => '09:00',
            'end_time'   => '12:00',
            'task_name'  => 'Code review',
        ]);

        $response->assertRedirect();
        $this->assertSame('Code review', $assignment->refresh()->task?->name);
    }

    public function test_admin_can_update_another_users_task_assignment(): void
    {
        [$site, $team] = $this->createSiteWithTeam(['slug' => 'acme']);
        $user          = User::factory()->create();
        $admin         = User::factory()->create();
        $this->attachOnboardedMember($site, $team, $user);
        $this->attachOnboardedMember($site, $team, $admin, ['is_site_admin' => true]);
        $task = Task::create(['name' => 'Deep Work', 'team_id' => $team->id]);

        $assignment = TaskAssignment::createWithTimeBlock(
            ['team_id' => $team->id, 'user_id' => $user->id, 'task_id' => $task->id],
            ['date' => now()->addDay()->toDateString(), 'start_time' => '09:00', 'end_time' => '12:00'],
        );

        $response = $this->actingAs($admin)->put(route('task-assignments.update', [
            'tenant'         => 'acme',
            'taskAssignment' => $assignment->id,
        ]), [
            'start_time' => '14:00',
            'end_time'   => '15:00',
            'task_name'  => 'Deep Work',
        ]);

        $response->assertRedirect();
        $this->assertSame('14:00', $assignment->refresh()->timeBlock->start_time);
    }

    public function test_non_admin_cannot_update_another_users_task_assignment(): void
    {
        [$site, $team] = $this->createSiteWithTeam(['slug' => 'acme']);
        $user          = User::factory()->create();
        $other         = User::factory()->create();
        $this->attachOnboardedMember($site, $team, $user);
        $this->attachOnboardedMember($site, $team, $other);
        $task = Task::create(['name' => 'Deep Work', 'team_id' => $team->id]);

        $assignment = TaskAssignment::createWithTimeBlock(
            ['team_id' => $team->id, 'user_id' => $other->id, 'task_id' => $task->id],
            ['date' => now()->addDay()->toDateString(), 'start_time' => '09:00', 'end_time' => '12:00'],
        );

        $response = $this->actingAs($user)->put(route('task-assignments.update', [
            'tenant'         => 'acme',
            'taskAssignment' => $assignment->id,
        ]), [
            'start_time' => '14:00',
            'end_time'   => '15:00',
            'task_name'  => 'Deep Work',
        ]);

        $response->assertForbidden();
    }

    public function test_admin_can_create_task_for_another_user(): void
    {
        [$site, $team] = $this->createSiteWithTeam(['slug' => 'acme']);
        $user          = User::factory()->create();
        $admin         = User::factory()->create();
        $this->attachOnboardedMember($site, $team, $user);
        $this->attachOnboardedMember($site, $team, $admin, ['is_site_admin' => true]);

        $response = $this->actingAs($admin)->post('http://acme.localhost/task-assignments', [
            'date'       => now()->addDay()->toDateString(),
            'start_time' => '09:00',
            'end_time'   => '10:00',
            'task_name'  => 'Onboarding support',
            'user_id'    => $user->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('task_assignments', [
            'user_id' => $user->id,
            'team_id' => $team->id,
        ]);
    }
}
