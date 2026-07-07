<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OnboardingTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_member_is_redirected_to_onboarding(): void
    {
        [$site, $team] = $this->createSiteWithTeam(['slug' => 'acme']);

        $user = User::factory()->create();
        $this->attachSiteMember($site, $team, $user);

        $this->actingAs($user)
            ->get('http://acme.localhost/')
            ->assertRedirect('/onboarding');
    }

    public function test_member_can_complete_onboarding(): void
    {
        [$site, $team] = $this->createSiteWithTeam(['slug' => 'acme']);
        $preset        = $site->workingHourPresets()->first();

        $user = User::factory()->create();
        $this->attachSiteMember($site, $team, $user);

        $this->actingAs($user)
            ->post('http://acme.localhost/onboarding', [
                'working_hour_preset_id' => $preset->id,
            ])
            ->assertRedirect('/');

        $this->assertTrue($user->fresh()->hasCompletedOnboardingFor($site));
    }
}
