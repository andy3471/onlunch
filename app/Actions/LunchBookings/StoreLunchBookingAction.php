<?php

declare(strict_types=1);

namespace App\Actions\LunchBookings;

use App\Actions\Availability\AssertTeamsAvailabilityForUserAction;
use App\Actions\Concerns\AsAction;
use App\Actions\Schedule\AssertNoScheduleOverlapAction;
use App\Models\LunchBooking;
use App\Models\Site;
use App\Models\User;

class StoreLunchBookingAction
{
    use AsAction;

    public function __construct(
        private readonly AssertTeamsAvailabilityForUserAction $assertTeamsAvailability,
        private readonly AssertNoScheduleOverlapAction $assertNoScheduleOverlap,
    ) {}

    public function execute(
        Site $site,
        User $user,
        string $date,
        string $startTime,
        string $endTime,
    ): LunchBooking {
        $team = $user->primaryTeamFor($site);

        abort_unless($team !== null, 403);

        $this->assertTeamsAvailability->execute(
            $site,
            $user,
            $date,
            $startTime,
            $endTime,
        );

        LunchBooking::query()
            ->where('team_id', $team->id)
            ->where('user_id', $user->id)
            ->whereHas('timeBlock', fn ($query) => $query->where('date', $date))
            ->each(fn (LunchBooking $booking) => $booking->delete());

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
