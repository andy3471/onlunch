<?php

declare(strict_types=1);

namespace App\Actions\Users;

use App\Actions\Concerns\AsAction;
use App\Models\Site;
use App\Models\User;

class UpdateTeamMemberAction
{
    use AsAction;

    public function __construct(
        private readonly SyncUserTeamMembershipsAction $syncTeamMemberships,
        private readonly SyncSiteMemberSettingsAction $syncSiteMemberSettings,
    ) {}

    /** @param  array<string, mixed>  $data */
    public function execute(Site $site, User $user, array $data): User
    {
        $this->syncSiteMemberSettings->execute($site, $user, $data);
        $this->syncTeamMemberships->execute($site, $user, $data['team_memberships']);

        return $user->refresh();
    }
}
