<?php

declare(strict_types=1);

use App\Models\User;

it('logs in on a tenant host', function (): void {
    [$site, $team] = $this->createSiteWithTeam(['slug' => 'acme']);
    $user          = User::factory()->create([
        'email'    => 'member@example.com',
        'password' => bcrypt('password'),
    ]);
    $this->attachOnboardedMember($site, $team, $user);

    $page = visit('http://acme.localhost/login');

    $page->fill('email', 'member@example.com')
        ->fill('password', 'password')
        ->click('Log in')
        ->assertPathIs('/')
        ->assertNoJavascriptErrors();
});
