<?php

declare(strict_types=1);

namespace App\Actions\Availability;

use App\Actions\Concerns\AsAction;
use App\Models\Site;
use App\Models\User;

class AssertTeamsAvailabilityForUserAction
{
    use AsAction;

    public function __construct(
        private readonly AssertMinimumAvailabilityAction $assertMinimumAvailability,
    ) {}

    public function execute(
        Site $site,
        User $user,
        string $date,
        string $startTime,
        string $endTime,
    ): void {
        foreach ($user->teamsForSite($site) as $team) {
            if (! $team->hasMinimumAvailabilityRule()) {
                continue;
            }

            $this->assertMinimumAvailability->execute(
                $team,
                $date,
                $startTime,
                $endTime,
                $user->id,
            );
        }
    }
}
