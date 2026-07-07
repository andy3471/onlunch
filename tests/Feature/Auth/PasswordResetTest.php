<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\Site;
use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    private Site $site;

    private Team $team;

    protected function setUp(): void
    {
        parent::setUp();

        [$this->site, $this->team] = $this->createSiteWithTeam([
            'slug'                   => 'test',
            'reset_password_enabled' => true,
        ]);
    }

    /** Reset password link screen can be rendered when enabled. */
    public function test_reset_password_link_screen_can_be_rendered(): void
    {
        $response = $this->get('http://test.localhost/forgot-password');

        $response->assertStatus(200);
    }

    /** Reset password link can be requested when enabled. */
    public function test_reset_password_link_can_be_requested(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $this->attachSiteMember($this->site, $this->team, $user);

        $this->post('http://test.localhost/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class);
    }

    /** Reset password routes return 404 when feature is disabled. */
    public function test_reset_password_returns_404_when_disabled(): void
    {
        $this->site->update(['reset_password_enabled' => false]);

        $response = $this->get('http://test.localhost/forgot-password');

        $response->assertStatus(404);
    }
}
