<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Site;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeamRegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** A site can be created with an admin user via the registration form. */
    public function test_team_can_be_registered(): void
    {
        $response = $this->post('http://localhost/register', [
            'team_name'             => 'Test Team',
            'team_slug'             => 'test-team',
            'name'                  => 'Jane Smith',
            'email'                 => 'jane@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('sites', [
            'name' => 'Test Team',
            'slug' => 'test-team',
        ]);

        $this->assertDatabaseHas('users', [
            'name'  => 'Jane Smith',
            'email' => 'jane@example.com',
        ]);

        $site = Site::where('slug', 'test-team')->first();
        $user = User::where('email', 'jane@example.com')->first();
        $team = Team::where('site_id', $site->id)->first();

        $this->assertTrue($site->members->contains($user));
        $this->assertTrue($team->members->contains($user));
        $this->assertTrue($user->isSiteAdminFor($site));
        $this->assertAuthenticated();
    }

    /** Team registration requires all fields. */
    public function test_team_registration_requires_all_fields(): void
    {
        $response = $this->post('http://localhost/register', []);

        $response->assertSessionHasErrors([
            'team_name',
            'team_slug',
            'name',
            'email',
            'password',
        ]);
    }

    /** Team slug must be unique. */
    public function test_team_slug_must_be_unique(): void
    {
        Site::factory()->create(['slug' => 'taken-slug']);

        $response = $this->post('http://localhost/register', [
            'team_name'             => 'Another Team',
            'team_slug'             => 'taken-slug',
            'name'                  => 'John',
            'email'                 => 'john@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('team_slug');
    }

    /** Email must be unique. */
    public function test_email_must_be_unique(): void
    {
        User::factory()->create(['email' => 'taken@example.com']);

        $response = $this->post('http://localhost/register', [
            'team_name'             => 'New Team',
            'team_slug'             => 'new-team',
            'name'                  => 'John',
            'email'                 => 'taken@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    }
}
