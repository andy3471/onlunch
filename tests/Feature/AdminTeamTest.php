<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTeamTest extends TestCase
{
    use RefreshDatabase;

    public function test_site_admin_can_create_team_with_minimum_staff_rule(): void
    {
        [$site, $team] = $this->createSiteWithTeam(['slug' => 'acme']);
        $admin         = User::factory()->create();
        $this->attachOnboardedMember($site, $team, $admin, ['is_site_admin' => true]);

        $response = $this->actingAs($admin)->post('http://acme.localhost/manage/teams', [
            'name'                    => 'Development',
            'minimum_available_staff' => 2,
        ]);

        $response->assertRedirect('/manage/teams');

        $this->assertDatabaseHas(Team::class, [
            'site_id'                 => $site->id,
            'name'                    => 'Development',
            'minimum_available_staff' => 2,
        ]);
    }

    public function test_site_admin_can_update_team_minimum_staff_rule(): void
    {
        [$site, $team] = $this->createSiteWithTeam(['slug' => 'acme']);
        $admin         = User::factory()->create();
        $this->attachOnboardedMember($site, $team, $admin, ['is_site_admin' => true]);

        $devTeam = Team::factory()->for($site)->create([
            'name'                    => 'Development',
            'minimum_available_staff' => null,
        ]);

        $response = $this->actingAs($admin)->put("http://acme.localhost/manage/teams/{$devTeam->id}", [
            'name'                    => 'Development',
            'minimum_available_staff' => 3,
        ]);

        $response->assertRedirect('/manage/teams');

        $this->assertDatabaseHas(Team::class, [
            'id'                      => $devTeam->id,
            'minimum_available_staff' => 3,
        ]);
    }

    public function test_site_admin_can_open_edit_team_form(): void
    {
        [$site, $team] = $this->createSiteWithTeam(['slug' => 'acme']);
        $admin         = User::factory()->create();
        $this->attachOnboardedMember($site, $team, $admin, ['is_site_admin' => true]);

        $response = $this->actingAs($admin)->get("http://acme.localhost/manage/teams/{$team->id}/edit");

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Admin/Teams/Form')
            ->where('team.name', $team->name));
    }
}
