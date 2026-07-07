<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Actions\LunchBookings\StoreLunchBookingAction;
use App\Actions\TaskAssignments\StoreTaskAssignmentAction;
use App\Actions\TimeOffRequests\RequestTimeOffAction;
use App\Enums\TimeOffRequestStatus;
use App\Models\TaskAssignment;
use App\Models\TimeOffRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ScheduleOverlapTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_assignment_rejects_overlap_with_existing_task(): void
    {
        [$site, $team] = $this->createSiteWithTeam();
        $user          = User::factory()->create();
        $this->attachOnboardedMember($site, $team, $user);

        TaskAssignment::createWithTimeBlock(
            ['team_id' => $team->id, 'user_id' => $user->id, 'task_id' => null],
            ['date' => '2026-07-10', 'start_time' => '09:00', 'end_time' => '12:00'],
        );

        $this->expectException(ValidationException::class);

        resolve(StoreTaskAssignmentAction::class)->execute(
            $team,
            $user,
            '2026-07-10',
            '11:00',
            '13:00',
            null,
        );
    }

    public function test_lunch_booking_rejects_overlap_with_task(): void
    {
        [$site, $team] = $this->createSiteWithTeam();
        $user          = User::factory()->create();
        $this->attachOnboardedMember($site, $team, $user);

        TaskAssignment::createWithTimeBlock(
            ['team_id' => $team->id, 'user_id' => $user->id, 'task_id' => null],
            ['date' => now()->toDateString(), 'start_time' => '11:30', 'end_time' => '14:00'],
        );

        $this->expectException(ValidationException::class);

        resolve(StoreLunchBookingAction::class)->execute(
            $site,
            $user,
            now()->toDateString(),
            '12:00',
            '13:00',
        );
    }

    public function test_time_off_request_rejects_overlap_with_task(): void
    {
        [$site, $team] = $this->createSiteWithTeam([], [
            'time_off_auto_approve' => true,
        ]);
        $user          = User::factory()->create();
        $this->attachOnboardedMember($site, $team, $user);

        TaskAssignment::createWithTimeBlock(
            ['team_id' => $team->id, 'user_id' => $user->id, 'task_id' => null],
            ['date' => '2026-07-10', 'start_time' => '09:00', 'end_time' => '17:00'],
        );

        $this->expectException(ValidationException::class);

        resolve(RequestTimeOffAction::class)->execute(
            $site,
            $team,
            $user,
            '2026-07-10',
            '14:00',
            '16:00',
        );
    }

    public function test_rejected_time_off_does_not_block_new_booking(): void
    {
        [$site, $team] = $this->createSiteWithTeam();
        $user          = User::factory()->create();
        $this->attachOnboardedMember($site, $team, $user);

        $date = now()->toDateString();

        TimeOffRequest::createWithTimeBlock(
            [
                'team_id' => $team->id,
                'user_id' => $user->id,
                'status'  => TimeOffRequestStatus::Rejected->value,
            ],
            ['date' => $date, 'start_time' => '12:00', 'end_time' => '13:00'],
        );

        resolve(StoreLunchBookingAction::class)->execute(
            $site,
            $user,
            $date,
            '12:00',
            '13:00',
        );

        $this->assertDatabaseHas('lunch_bookings', [
            'user_id' => $user->id,
            'team_id' => $team->id,
        ]);
    }

    public function test_http_task_assignment_returns_validation_error_on_overlap(): void
    {
        [$site, $team] = $this->createSiteWithTeam(['slug' => 'acme']);
        $user          = User::factory()->create();
        $this->attachOnboardedMember($site, $team, $user);
        $date          = now()->addDay()->toDateString();

        TaskAssignment::createWithTimeBlock(
            ['team_id' => $team->id, 'user_id' => $user->id, 'task_id' => null],
            ['date' => $date, 'start_time' => '09:00', 'end_time' => '12:00'],
        );

        $response = $this->actingAs($user)->post('http://acme.localhost/task-assignments', [
            'date'       => $date,
            'start_time' => '10:00',
            'end_time'   => '11:00',
            'task_name'  => 'Overlapping task',
        ]);

        $response->assertSessionHasErrors('start_time');
    }
}
