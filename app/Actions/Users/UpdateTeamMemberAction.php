<?php

declare(strict_types=1);

namespace App\Actions\Users;

use App\Actions\Concerns\AsAction;
use App\Models\Site;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UpdateTeamMemberAction
{
    use AsAction;

    public function __construct(
        private readonly SyncUserTeamMembershipsAction $syncTeamMemberships,
        private readonly SyncSiteMemberSettingsAction $syncSiteMemberSettings,
    ) {}

    public function execute(Site $site, User $user, array $data): User
    {
        $attributes = [
            'name'  => $data['name'],
            'email' => $data['email'],
        ];

        if (! empty($data['password'])) {
            $attributes['password'] = Hash::make($data['password']);
        }

        $user->update($attributes);

        $this->syncSiteMemberSettings->execute($site, $user, $data);
        $this->syncTeamMemberships->execute($site, $user, $data['team_memberships']);

        return $user->refresh();
    }
}
