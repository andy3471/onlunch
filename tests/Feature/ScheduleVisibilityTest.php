<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScheduleVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_team_visibility_hides_schedule_from_other_teams(): void
    {
        [$site, $everyone] = $this->createSiteWithTeam(['slug' => 'acme'], ['name' => 'Everyone']);
        $dev               = Team::factory()->for($site)->create(['name' => 'Development']);

        $visibleMember = User::factory()->create(['name' => 'Visible Member']);
        $teammate      = User::factory()->create(['name' => 'Teammate']);
        $outsider      = User::factory()->create(['name' => 'Outsider']);

        $this->attachOnboardedMember($site, $everyone, $visibleMember, [
            'schedule_visibility' => 'team',
        ]);
        $this->attachOnboardedMember($site, $everyone, $teammate, [
            'schedule_visibility' => 'team',
        ]);
        $this->attachOnboardedMember($site, $dev, $outsider, [
            'schedule_visibility' => 'team',
        ]);

        $this->actingAs($teammate)
            ->get('http://acme.localhost/')
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('scheduleUsers', 2)
                ->where('scheduleUsers.0.userName', 'Teammate')
                ->where('scheduleUsers.1.userName', 'Visible Member'));

        $this->actingAs($outsider)
            ->get('http://acme.localhost/')
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('scheduleUsers', 1)
                ->where('scheduleUsers.0.userName', 'Outsider'));
    }

    public function test_private_visibility_hides_schedule_except_from_site_admin(): void
    {
        [$site, $team] = $this->createSiteWithTeam(['slug' => 'acme']);
        $privateMember = User::factory()->create(['name' => 'Private Member']);
        $teammate      = User::factory()->create(['name' => 'Teammate']);
        $admin         = User::factory()->create(['name' => 'Admin']);

        $this->attachOnboardedMember($site, $team, $privateMember, [
            'schedule_visibility' => 'private',
        ]);
        $this->attachOnboardedMember($site, $team, $teammate, [
            'schedule_visibility' => 'private',
        ]);
        $this->attachOnboardedMember($site, $team, $admin, [
            'is_site_admin'       => true,
            'schedule_visibility' => 'private',
        ]);

        $this->actingAs($teammate)
            ->get('http://acme.localhost/')
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('scheduleUsers', 1)
                ->where('scheduleUsers.0.userName', 'Teammate'));

        $this->actingAs($admin)
            ->get('http://acme.localhost/')
            ->assertOk()
            ->assertInertia(fn ($page) => $page->has('scheduleUsers', 3));
    }

    public function test_approver_visibility_shows_schedule_to_team_approvers(): void
    {
        [$site, $team] = $this->createSiteWithTeam(['slug' => 'acme']);
        $member        = User::factory()->create(['name' => 'Member']);
        $teammate      = User::factory()->create(['name' => 'Teammate']);
        $approver      = User::factory()->create(['name' => 'Approver']);

        $this->attachOnboardedMember($site, $team, $member, [
            'schedule_visibility' => 'approvers',
        ]);
        $this->attachOnboardedMember($site, $team, $teammate, [
            'schedule_visibility' => 'private',
        ]);
        $this->attachOnboardedMember($site, $team, $approver, [
            'is_time_off_approver' => true,
            'schedule_visibility'  => 'private',
        ]);

        $this->actingAs($teammate)
            ->get('http://acme.localhost/')
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('scheduleUsers', 1)
                ->where('scheduleUsers.0.userName', 'Teammate'));

        $this->actingAs($approver)
            ->get('http://acme.localhost/')
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('scheduleUsers', 2)
                ->where('scheduleUsers.0.userName', 'Approver')
                ->where('scheduleUsers.1.userName', 'Member'));
    }
}
