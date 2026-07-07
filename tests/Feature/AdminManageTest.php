<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Actions\TimeOffRequests\RequestTimeOffAction;
use App\Enums\TimeOffRequestStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminManageTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_redirected_to_login_from_manage_area(): void
    {
        $this->createSiteWithTeam(['slug' => 'acme']);

        $this->get('http://acme.localhost/manage/users')
            ->assertRedirect('/login');
    }

    public function test_non_admin_cannot_access_manage_area(): void
    {
        [$site, $team] = $this->createSiteWithTeam(['slug' => 'acme']);
        $user          = User::factory()->create();
        $this->attachOnboardedMember($site, $team, $user);

        $this->actingAs($user)
            ->get('http://acme.localhost/manage/users')
            ->assertForbidden();
    }

    public function test_admin_can_view_users_index(): void
    {
        [$site, $team] = $this->createSiteWithTeam(['slug' => 'acme']);
        $admin         = User::factory()->create();
        $this->attachOnboardedMember($site, $team, $admin, ['is_site_admin' => true]);

        $this->actingAs($admin)
            ->get('http://acme.localhost/manage/users')
            ->assertOk()
            ->assertInertia(fn ($page) => $page->component('Admin/Users/Index'));
    }

    public function test_admin_can_create_team_member(): void
    {
        [$site, $team] = $this->createSiteWithTeam(['slug' => 'acme']);
        $admin         = User::factory()->create();
        $preset        = $this->attachOnboardedMember($site, $team, $admin, ['is_site_admin' => true]);

        $this->actingAs($admin)
            ->post('http://acme.localhost/manage/users', [
                'name'                   => 'New Member',
                'email'                  => 'member@example.com',
                'password'               => 'Xk9#mNp2$vLq7@wRz',
                'password_confirmation'  => 'Xk9#mNp2$vLq7@wRz',
                'is_admin'               => false,
                'working_hour_preset_id' => $preset->id,
                'time_off_approval'      => 'site_default',
                'team_memberships'       => [
                    [
                        'team_id'              => $team->id,
                        'is_scheduled'         => true,
                        'schedule_visibility'  => 'everyone',
                        'is_time_off_approver' => false,
                    ],
                ],
            ])
            ->assertRedirect('/manage/users');

        $this->assertDatabaseHas('users', [
            'email' => 'member@example.com',
        ]);

        $member = User::where('email', 'member@example.com')->first();
        $this->assertTrue($team->members()->whereKey($member->id)->exists());
        $this->assertTrue($site->members()->whereKey($member->id)->exists());
    }

    public function test_admin_can_approve_pending_time_off_request(): void
    {
        [$site, $team] = $this->createSiteWithTeam([
            'slug' => 'acme',
        ], [
            'time_off_auto_approve' => false,
        ]);
        $user  = User::factory()->create();
        $admin = User::factory()->create();
        $this->attachOnboardedMember($site, $team, $user);
        $this->attachOnboardedMember($site, $team, $admin, ['is_site_admin' => true]);

        $timeOffRequest = resolve(RequestTimeOffAction::class)->execute(
            $site,
            $team,
            $user,
            '2026-07-10',
            '14:00',
            '16:00',
        );

        $this->actingAs($admin)
            ->post("http://acme.localhost/manage/time-off-requests/{$timeOffRequest->id}/approve")
            ->assertRedirect('/manage/time-off-requests');

        $this->assertSame(TimeOffRequestStatus::Approved, $timeOffRequest->refresh()->status);
    }

    public function test_admin_redirect_from_legacy_admin_url(): void
    {
        [$site, $team] = $this->createSiteWithTeam(['slug' => 'acme']);
        $admin         = User::factory()->create();
        $this->attachOnboardedMember($site, $team, $admin, ['is_site_admin' => true]);

        $this->actingAs($admin)
            ->get('http://acme.localhost/admin')
            ->assertRedirect('/manage/users');
    }
}
