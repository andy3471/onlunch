<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Actions\TimeOffRequests\RequestTimeOffAction;
use App\Enums\TimeOffRequestStatus;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TimeOffApproverTest extends TestCase
{
    use RefreshDatabase;

    public function test_team_approver_can_approve_pending_request_for_their_team(): void
    {
        [$site, $team] = $this->createSiteWithTeam(['slug' => 'acme'], [
            'time_off_auto_approve' => false,
        ]);
        $member   = User::factory()->create();
        $approver = User::factory()->create();

        $this->attachOnboardedMember($site, $team, $member);
        $this->attachOnboardedMember($site, $team, $approver, [
            'is_time_off_approver' => true,
        ]);

        $request = resolve(RequestTimeOffAction::class)->execute(
            $site,
            $team,
            $member,
            '2026-07-10',
            '14:00',
            '16:00',
        );

        $this->actingAs($approver)
            ->post("http://acme.localhost/manage/time-off-requests/{$request->id}/approve")
            ->assertRedirect('/manage/time-off-requests');

        $this->assertSame(TimeOffRequestStatus::Approved, $request->refresh()->status);
        $this->assertSame($approver->id, $request->reviewed_by);
    }

    public function test_non_approver_cannot_approve_request(): void
    {
        [$site, $team] = $this->createSiteWithTeam(['slug' => 'acme'], [
            'time_off_auto_approve' => false,
        ]);
        $member   = User::factory()->create();
        $observer = User::factory()->create();

        $this->attachOnboardedMember($site, $team, $member);
        $this->attachOnboardedMember($site, $team, $observer);

        $request = resolve(RequestTimeOffAction::class)->execute(
            $site,
            $team,
            $member,
            '2026-07-10',
            '14:00',
            '16:00',
        );

        $this->actingAs($observer)
            ->post("http://acme.localhost/manage/time-off-requests/{$request->id}/approve")
            ->assertForbidden();

        $this->assertSame(TimeOffRequestStatus::Pending, $request->refresh()->status);
    }

    public function test_team_approver_only_sees_requests_for_teams_they_approve(): void
    {
        [$site, $support] = $this->createSiteWithTeam(['slug' => 'acme'], ['name' => 'Support']);
        $dev              = Team::factory()->for($site)->create(['name' => 'Development', 'time_off_auto_approve' => false]);

        $supportMember = User::factory()->create(['name' => 'Support Member']);
        $devMember     = User::factory()->create(['name' => 'Dev Member']);
        $supportLead   = User::factory()->create(['name' => 'Support Lead']);

        $this->attachOnboardedMember($site, $support, $supportMember);
        $this->attachOnboardedMember($site, $dev, $devMember);
        $this->attachOnboardedMember($site, $support, $supportLead, [
            'is_time_off_approver' => true,
        ]);

        $supportRequest = resolve(RequestTimeOffAction::class)->execute(
            $site,
            $support,
            $supportMember,
            '2026-07-10',
            '14:00',
            '16:00',
        );

        resolve(RequestTimeOffAction::class)->execute(
            $site,
            $dev,
            $devMember,
            '2026-07-11',
            '10:00',
            '12:00',
        );

        $this->actingAs($supportLead)
            ->get('http://acme.localhost/manage/time-off-requests')
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('requests', 1)
                ->where('requests.0.id', $supportRequest->id)
                ->where('requests.0.teamName', 'Support')
                ->where('isSiteAdmin', false));
    }
}
