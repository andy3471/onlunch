<?php

declare(strict_types=1);

namespace App\Actions\Users;

use App\Actions\Concerns\AsAction;
use App\Models\Site;
use App\Models\Team;
use App\Models\User;

class BuildUserIndexAction
{
    use AsAction;

    /** @return list<array{id: string, name: string, email: string, isAdmin: bool, teamNames: list<string>, isScheduled: bool}> */
    public function execute(Site $site): array
    {
        return $site->members()
            ->with(['teams' => fn ($query) => $query->where('site_id', $site->id)])
            ->orderBy('name')
            ->get()
            ->map(function (User $user) use ($site): array {
                $teams = $user->teamsForSite($site);

                return [
                    'id'          => $user->id,
                    'name'        => $user->name,
                    'email'       => $user->email,
                    'isAdmin'     => $user->isSiteAdminFor($site),
                    'teamNames'   => $teams->pluck('name')->all(),
                    'isScheduled' => $teams->contains(
                        fn (Team $team): bool => (bool) $user->membershipFor($team)?->is_scheduled,
                    ),
                ];
            })
            ->all();
    }
}
