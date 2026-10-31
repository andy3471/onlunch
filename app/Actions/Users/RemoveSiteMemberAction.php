<?php

declare(strict_types=1);

namespace App\Actions\Users;

use App\Actions\Concerns\AsAction;
use App\Models\Site;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RemoveSiteMemberAction
{
    use AsAction;

    public function execute(Site $site, User $user): void
    {
        DB::transaction(function () use ($site, $user): void {
            $teamIds = $site->teamIds();

            if ($teamIds !== []) {
                $user->teams()->detach($teamIds);
            }

            $site->members()->detach($user->id);
        });
    }
}
