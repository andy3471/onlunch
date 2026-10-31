<?php

declare(strict_types=1);

use App\Models\User;

it('forbids guests from admin routes', function (): void {
    $this->createSiteWithTeam(['slug' => 'acme']);

    $this->get(route('manage.users.index', ['tenant' => 'acme']))
        ->assertRedirect('/login');
});

it('forbids non-admin members from admin user management', function (): void {
    [$site, $team] = $this->createSiteWithTeam(['slug' => 'acme']);
    $member        = User::factory()->create();
    $this->attachOnboardedMember($site, $team, $member);

    $this->actingAs($member)
        ->get(route('manage.users.index', ['tenant' => 'acme']))
        ->assertForbidden();
});

it('allows site admins to access admin user management', function (): void {
    [$site, $team] = $this->createSiteWithTeam(['slug' => 'acme']);
    $admin         = User::factory()->create();
    $this->attachOnboardedMember($site, $team, $admin, ['is_site_admin' => true]);

    $this->actingAs($admin)
        ->get(route('manage.users.index', ['tenant' => 'acme']))
        ->assertOk();
});

it('forbids members without approver rights from time-off management', function (): void {
    [$site, $team] = $this->createSiteWithTeam(['slug' => 'acme']);
    $member        = User::factory()->create();
    $this->attachOnboardedMember($site, $team, $member);

    $this->actingAs($member)
        ->get(route('manage.time-off-requests.index', ['tenant' => 'acme']))
        ->assertForbidden();
});

it('allows time-off approvers to access time-off management', function (): void {
    [$site, $team] = $this->createSiteWithTeam(['slug' => 'acme']);
    $approver      = User::factory()->create();
    $this->attachOnboardedMember($site, $team, $approver, ['is_time_off_approver' => true]);

    $this->actingAs($approver)
        ->get(route('manage.time-off-requests.index', ['tenant' => 'acme']))
        ->assertOk();
});

it('throttles login attempts', function (): void {
    [$site, $team] = $this->createSiteWithTeam(['slug' => 'acme']);
    $user          = User::factory()->create([
        'email'    => 'member@example.com',
        'password' => bcrypt('password'),
    ]);
    $this->attachOnboardedMember($site, $team, $user);

    for ($i = 0; $i < 5; $i++) {
        $this->post('http://acme.localhost/login', [
            'email'    => 'member@example.com',
            'password' => 'wrong-password',
        ]);
    }

    $this->post('http://acme.localhost/login', [
        'email'    => 'member@example.com',
        'password' => 'wrong-password',
    ])->assertStatus(429);
});
