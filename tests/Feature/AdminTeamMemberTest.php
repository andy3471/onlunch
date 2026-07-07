<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTeamMemberTest extends TestCase
{
    use RefreshDatabase;

    private const PASSWORD = 'Xk9#mP2$vL7@nQ4!';

    public function test_site_admin_can_open_edit_member_form(): void
    {
        [$site, $team] = $this->createSiteWithTeam(['slug' => 'acme']);
        $admin         = User::factory()->create();
        $member        = User::factory()->create();

        $this->attachOnboardedMember($site, $team, $admin, ['is_site_admin' => true]);
        $this->attachOnboardedMember($site, $team, $member);

        $response = $this->actingAs($admin)->get("http://acme.localhost/manage/users/{$member->id}/edit");

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Admin/Users/Form')
            ->where('user.email', $member->email)
            ->has('presets', 1)
            ->has('teamMemberships', 1));
    }

    public function test_site_admin_can_create_user_on_specific_team(): void
    {
        [$site, $everyone] = $this->createSiteWithTeam(['slug' => 'acme'], ['name' => 'Everyone']);
        $dev               = Team::factory()->for($site)->create(['name' => 'Development']);
        $admin             = User::factory()->create();
        $preset            = $site->workingHourPresets()->first();

        $this->attachOnboardedMember($site, $everyone, $admin, ['is_site_admin' => true]);
        $this->attachToTeam($dev, $admin);

        $response = $this->actingAs($admin)->post('http://acme.localhost/manage/users', [
            'name'                    => 'New Dev',
            'email'                   => 'newdev@example.com',
            'password'                => self::PASSWORD,
            'password_confirmation'   => self::PASSWORD,
            'is_admin'                => false,
            'working_hour_preset_id'  => $preset?->id,
            'time_off_approval'       => 'site_default',
            'team_memberships'        => [
                [
                    'team_id'              => $dev->id,
                    'is_scheduled'         => true,
                    'schedule_visibility'  => 'everyone',
                    'is_time_off_approver' => false,
                ],
            ],
        ]);

        $response->assertRedirect('/manage/users');

        $user = User::query()->where('email', 'newdev@example.com')->first();

        $this->assertNotNull($user);
        $this->assertTrue($dev->members()->whereKey($user->id)->exists());
        $this->assertFalse($everyone->members()->whereKey($user->id)->exists());
    }

    public function test_site_admin_can_assign_existing_user_to_additional_team(): void
    {
        [$site, $everyone] = $this->createSiteWithTeam(['slug' => 'acme'], ['name' => 'Everyone']);
        $dev               = Team::factory()->for($site)->create(['name' => 'Development']);
        $admin             = User::factory()->create();
        $member            = User::factory()->create();
        $preset            = $site->workingHourPresets()->first();

        $this->attachOnboardedMember($site, $everyone, $admin, ['is_site_admin' => true]);
        $this->attachOnboardedMember($site, $everyone, $member);
        $this->attachToTeam($dev, $admin);

        $response = $this->actingAs($admin)->put("http://acme.localhost/manage/users/{$member->id}", [
            'name'                   => $member->name,
            'email'                  => $member->email,
            'is_admin'               => false,
            'working_hour_preset_id' => $preset?->id,
            'time_off_approval'      => 'site_default',
            'team_memberships'       => [
                [
                    'team_id'              => $everyone->id,
                    'is_scheduled'         => true,
                    'schedule_visibility'  => 'everyone',
                    'is_time_off_approver' => false,
                ],
                [
                    'team_id'              => $dev->id,
                    'is_scheduled'         => true,
                    'schedule_visibility'  => 'everyone',
                    'is_time_off_approver' => false,
                ],
            ],
        ]);

        $response->assertRedirect('/manage/users');

        $member->refresh();

        $this->assertTrue($everyone->members()->whereKey($member->id)->exists());
        $this->assertTrue($dev->members()->whereKey($member->id)->exists());
    }
}
