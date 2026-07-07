<?php

declare(strict_types=1);

namespace App\Actions\Users;

use App\Actions\Concerns\AsAction;
use App\Models\Site;
use App\Models\User;

class SyncUserTeamMembershipsAction
{
    use AsAction;

    /** @param  list<array{team_id: string, is_scheduled: bool, schedule_visibility: string, is_time_off_approver: bool}>  $memberships */
    public function execute(Site $site, User $user, array $memberships): void
    {
        $siteTeamIds     = $site->teamIds();
        $selectedTeamIds = collect($memberships)->pluck('team_id')->all();

        $user->teams()
            ->whereIn('teams.id', $siteTeamIds)
            ->whereNotIn('teams.id', $selectedTeamIds)
            ->detach();

        foreach ($memberships as $membership) {
            $pivot = [
                'is_scheduled'         => $membership['is_scheduled'],
                'schedule_visibility'  => $membership['schedule_visibility'],
                'is_time_off_approver' => $membership['is_time_off_approver'],
            ];

            if ($user->teams()->where('teams.id', $membership['team_id'])->exists()) {
                $user->teams()->updateExistingPivot($membership['team_id'], $pivot);
            } else {
                $user->teams()->attach($membership['team_id'], $pivot);
            }
        }
    }
}
