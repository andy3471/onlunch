<?php

declare(strict_types=1);

use App\Models\LunchBooking;
use App\Models\User;

it('rejects login for users who are not members of the site', function (): void {
    [$site]   = $this->createSiteWithTeam(['slug' => 'acme']);
    $outsider = User::factory()->create([
        'email'    => 'outsider@example.com',
        'password' => bcrypt('password'),
    ]);

    $response = $this->post('http://acme.localhost/login', [
        'email'    => 'outsider@example.com',
        'password' => 'password',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

it('detaches a site member instead of deleting the global user', function (): void {
    [$site, $team] = $this->createSiteWithTeam(['slug' => 'acme']);
    $admin         = User::factory()->create();
    $member        = User::factory()->create();
    $this->attachOnboardedMember($site, $team, $admin, ['is_site_admin' => true]);
    $this->attachOnboardedMember($site, $team, $member);

    $response = $this->actingAs($admin)->delete(route('manage.users.destroy', [
        'tenant' => 'acme',
        'user'   => $member->id,
    ]));

    $response->assertRedirect(route('manage.users.index', ['tenant' => 'acme']));
    $this->assertDatabaseMissing('site_user', [
        'site_id' => $site->id,
        'user_id' => $member->id,
    ]);
    $this->assertDatabaseHas('users', [
        'id'         => $member->id,
        'deleted_at' => null,
    ]);
});

it('returns 404 when updating another site lunch booking by id', function (): void {
    [$siteA, $teamA] = $this->createSiteWithTeam(['slug' => 'alpha']);
    [$siteB, $teamB] = $this->createSiteWithTeam(['slug' => 'beta']);

    $userA = User::factory()->create();
    $userB = User::factory()->create();
    $this->attachOnboardedMember($siteA, $teamA, $userA);
    $this->attachOnboardedMember($siteB, $teamB, $userB);

    $bookingB = LunchBooking::createWithTimeBlock(
        ['team_id' => $teamB->id, 'user_id' => $userB->id],
        ['date' => now()->toDateString(), 'start_time' => '12:00', 'end_time' => '13:00'],
    );

    $response = $this->actingAs($userA)->put(route('lunch-booking.update', [
        'tenant'       => 'alpha',
        'lunchBooking' => $bookingB->id,
    ]), [
        'start_time' => '12:30',
        'end_time'   => '13:30',
    ]);

    $response->assertNotFound();
});

it('returns 404 when deleting another site task assignment by id', function (): void {
    [$siteA, $teamA] = $this->createSiteWithTeam(['slug' => 'alpha']);
    [$siteB, $teamB] = $this->createSiteWithTeam(['slug' => 'beta']);

    $userA = User::factory()->create();
    $userB = User::factory()->create();
    $this->attachOnboardedMember($siteA, $teamA, $userA);
    $this->attachOnboardedMember($siteB, $teamB, $userB);

    $assignmentB = App\Models\TaskAssignment::factory()->create([
        'team_id' => $teamB->id,
        'user_id' => $userB->id,
    ]);

    $this->actingAs($userA)
        ->delete(route('task-assignments.destroy', [
            'tenant'         => 'alpha',
            'taskAssignment' => $assignmentB->id,
        ]))
        ->assertNotFound();
});

it('denies authenticated non-members on another site subdomain', function (): void {
    [$siteA, $teamA] = $this->createSiteWithTeam(['slug' => 'alpha']);
    [$siteB]         = $this->createSiteWithTeam(['slug' => 'beta']);

    $userA = User::factory()->create();
    $this->attachOnboardedMember($siteA, $teamA, $userA);

    $this->actingAs($userA)
        ->get('http://beta.localhost/')
        ->assertRedirect('/login')
        ->assertSessionHasErrors('email');

    $this->assertGuest();
});
