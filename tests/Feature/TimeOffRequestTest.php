<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Actions\TimeOffRequests\ApproveTimeOffRequestAction;
use App\Actions\TimeOffRequests\RequestTimeOffAction;
use App\Enums\TimeOffRequestStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TimeOffRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_auto_approve_team_creates_approved_request_with_block(): void
    {
        [$site, $team] = $this->createSiteWithTeam(['slug' => 'acme'], [
            'time_off_auto_approve' => true,
        ]);
        $user = User::factory()->create();
        $this->attachOnboardedMember($site, $team, $user);

        $request = resolve(RequestTimeOffAction::class)->execute(
            $site,
            $team,
            $user,
            '2026-07-10',
            '14:00',
            '16:00',
            'Doctor',
        );

        $this->assertSame(TimeOffRequestStatus::Approved, $request->status);
        $this->assertNotNull($request->timeBlock);
        $this->assertSame('14:00', $request->timeBlock->start_time);
    }

    public function test_manual_approve_team_keeps_request_pending(): void
    {
        [$site, $team] = $this->createSiteWithTeam(['slug' => 'acme'], [
            'time_off_auto_approve' => false,
        ]);
        $user = User::factory()->create();
        $this->attachOnboardedMember($site, $team, $user);

        $request = resolve(RequestTimeOffAction::class)->execute(
            $site,
            $team,
            $user,
            '2026-07-10',
            '14:00',
            '16:00',
        );

        $this->assertSame(TimeOffRequestStatus::Pending, $request->status);
    }

    public function test_user_override_can_require_approval_even_when_team_auto_approves(): void
    {
        [$site, $team] = $this->createSiteWithTeam(['slug' => 'acme'], [
            'time_off_auto_approve' => true,
        ]);
        $user = User::factory()->create();
        $this->attachOnboardedMember($site, $team, $user, [
            'time_off_requires_approval' => true,
        ]);

        $request = resolve(RequestTimeOffAction::class)->execute(
            $site,
            $team,
            $user,
            '2026-07-10',
            '14:00',
            '16:00',
        );

        $this->assertSame(TimeOffRequestStatus::Pending, $request->status);
    }

    public function test_user_override_can_auto_approve_even_when_team_requires_approval(): void
    {
        [$site, $team] = $this->createSiteWithTeam(['slug' => 'acme'], [
            'time_off_auto_approve' => false,
        ]);
        $user = User::factory()->create();
        $this->attachOnboardedMember($site, $team, $user, [
            'time_off_requires_approval' => false,
        ]);

        $request = resolve(RequestTimeOffAction::class)->execute(
            $site,
            $team,
            $user,
            '2026-07-10',
            '14:00',
            '16:00',
        );

        $this->assertSame(TimeOffRequestStatus::Approved, $request->status);
    }

    public function test_approve_only_updates_status(): void
    {
        [$site, $team] = $this->createSiteWithTeam(['slug' => 'acme']);
        $user          = User::factory()->create();
        $admin         = User::factory()->create();
        $this->attachOnboardedMember($site, $team, $user);
        $this->attachOnboardedMember($site, $team, $admin, ['is_site_admin' => true]);

        $request = resolve(RequestTimeOffAction::class)->execute(
            $site,
            $team,
            $user,
            '2026-07-10',
            '14:00',
            '16:00',
        );

        resolve(ApproveTimeOffRequestAction::class)->execute($request, $admin);

        $this->assertSame(TimeOffRequestStatus::Approved, $request->refresh()->status);
        $this->assertSame($admin->id, $request->reviewed_by);
    }

    public function test_user_can_submit_time_off_request_via_http(): void
    {
        [$site, $team] = $this->createSiteWithTeam(['slug' => 'acme']);
        $user          = User::factory()->create();
        $this->attachOnboardedMember($site, $team, $user);

        $response = $this->actingAs($user)->post('http://acme.localhost/time-off-requests', [
            'date'       => now()->addDay()->toDateString(),
            'start_time' => '10:00',
            'end_time'   => '12:00',
            'notes'      => 'Appointment',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('time_off_requests', [
            'team_id' => $team->id,
            'user_id' => $user->id,
        ]);
    }
}
