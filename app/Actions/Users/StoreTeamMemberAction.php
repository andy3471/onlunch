<?php

declare(strict_types=1);

namespace App\Actions\Users;

use App\Actions\Concerns\AsAction;
use App\Models\Site;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StoreTeamMemberAction
{
    use AsAction;

    public function __construct(
        private readonly SyncUserTeamMembershipsAction $syncTeamMemberships,
        private readonly SyncSiteMemberSettingsAction $syncSiteMemberSettings,
    ) {}

    public function execute(Site $site, array $data): User
    {
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $site->members()->attach($user->id, ['is_site_admin' => $data['is_admin']]);

        $this->syncSiteMemberSettings->execute($site, $user, $data);
        $this->syncTeamMemberships->execute($site, $user, $data['team_memberships']);

        return $user;
    }
}
