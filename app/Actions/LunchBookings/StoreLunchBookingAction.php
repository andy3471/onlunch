<?php

declare(strict_types=1);

namespace App\Actions\LunchBookings;

use App\Actions\Availability\AssertTeamsAvailabilityForUserAction;
use App\Actions\Concerns\AsAction;
use App\Actions\Schedule\AssertNoScheduleOverlapAction;
use App\Models\LunchBooking;
use App\Models\Site;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;

class StoreLunchBookingAction
{
    use AsAction;

    public function __construct(
        private readonly AssertTeamsAvailabilityForUserAction $assertTeamsAvailability,
        private readonly AssertNoScheduleOverlapAction $assertNoScheduleOverlap,
        private readonly DeleteLunchBookingAction $deleteLunchBooking,
    ) {}

    public function execute(
        Site $site,
        User $user,
        string $date,
        string $startTime,
        string $endTime,
    ): LunchBooking {
        $team = $user->primaryTeamFor($site);

        if ($team === null) {
            throw new AuthorizationException('You must belong to a team to book lunch.');
        }

        $this->assertTeamsAvailability->execute(
            $site,
            $user,
            $date,
            $startTime,
            $endTime,
        );

        $this->deleteLunchBooking->deleteForTeamUserDate($team->id, $user->id, $date);

        $this->assertNoScheduleOverlap->execute($user, $date, $startTime, $endTime);

        return LunchBooking::createWithTimeBlock(
            [
                'team_id' => $team->id,
                'user_id' => $user->id,
            ],
            [
                'date'       => $date,
                'start_time' => $startTime,
                'end_time'   => $endTime,
            ],
        );
    }
}
