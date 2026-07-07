<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Site;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantSubdomainTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_view_home_page(): void
    {
        $this->createSiteWithTeam(['slug' => 'acme']);

        $this->get('http://acme.localhost/')
            ->assertRedirect('/login');
    }

    /** A valid tenant subdomain resolves the site. */
    public function test_valid_subdomain_resolves_team(): void
    {
        [$site, $team] = $this->createSiteWithTeam(['slug' => 'acme']);
        $user          = User::factory()->create();
        $this->attachOnboardedMember($site, $team, $user);

        $response = $this->actingAs($user)->get('http://acme.localhost/');

        $response->assertStatus(200);
    }

    /** An invalid subdomain returns 404. */
    public function test_invalid_subdomain_returns_404(): void
    {
        $response = $this->get('http://nonexistent.localhost/');

        $response->assertStatus(404);
    }

    /** Login page loads on a tenant subdomain. */
    public function test_login_page_loads_on_tenant_subdomain(): void
    {
        $this->createSiteWithTeam(['slug' => 'demo']);

        $this->get('http://demo.localhost/login')
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Auth/Login')
                ->where('currentSite.slug', 'demo')
            );
    }

    /** Users can authenticate on a tenant subdomain. */
    public function test_user_can_authenticate_on_tenant_subdomain(): void
    {
        [$site, $team] = $this->createSiteWithTeam(['slug' => 'acme']);
        $user          = User::factory()->create();
        $this->attachSiteMember($site, $team, $user);

        $this->post('http://acme.localhost/login', [
            'email'    => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
    }

    /** The home page shows tenant-scoped data. */
    public function test_home_page_shows_tenant_scoped_data(): void
    {
        [$site, $team] = $this->createSiteWithTeam(['slug' => 'acme']);
        $user          = User::factory()->create();
        $this->attachOnboardedMember($site, $team, $user);

        $response = $this->actingAs($user)->get('http://acme.localhost/');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Home')
            ->has('timeBlocks')
            ->has('workingHours')
            ->has('selectedDate')
            ->has('tasks')
            ->has('myTaskAssignments')
        );
    }
}
