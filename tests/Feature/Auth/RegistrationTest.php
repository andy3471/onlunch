<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\Site;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    private Site $site;

    private Team $team;

    protected function setUp(): void
    {
        parent::setUp();

        [$this->site, $this->team] = $this->createSiteWithTeam(['slug' => 'test']);
    }

    /** Registration screen can be rendered on a tenant subdomain. */
    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('http://test.localhost/register');

        $response->assertStatus(200);
    }

    /** New users can register on a tenant subdomain. */
    public function test_new_users_can_register(): void
    {
        $response = $this->post('http://test.localhost/register', [
            'name'                  => 'Test User',
            'email'                 => 'test@example.com',
            'password'              => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/');

        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
        $this->assertTrue($this->site->members()->where('email', 'test@example.com')->exists());
        $this->assertTrue($this->team->members()->where('email', 'test@example.com')->exists());
    }
}
