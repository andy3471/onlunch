<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\Site;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    private Site $site;

    private Team $team;

    protected function setUp(): void
    {
        parent::setUp();

        [$this->site, $this->team] = $this->createSiteWithTeam(['slug' => 'test']);
    }

    /** Login screen can be rendered on a tenant subdomain. */
    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('http://test.localhost/login');

        $response->assertStatus(200);
    }

    /** Users can authenticate using the login screen. */
    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();
        $this->attachSiteMember($this->site, $this->team, $user);

        $response = $this->post('http://test.localhost/login', [
            'email'    => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/');
    }

    /** Users cannot authenticate with invalid password. */
    public function test_users_cannot_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();
        $this->attachSiteMember($this->site, $this->team, $user);

        $this->post('http://test.localhost/login', [
            'email'    => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    /** Users can logout. */
    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();
        $this->attachSiteMember($this->site, $this->team, $user);

        $response = $this->actingAs($user)->post('http://test.localhost/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
