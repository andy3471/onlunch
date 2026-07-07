<?php

declare(strict_types=1);

namespace App\Actions\LunchBookings;

use App\Actions\Availability\AssertTeamsAvailabilityForUserAction;
use App\Actions\Concerns\AsAction;
use App\Actions\Schedule\AssertNoScheduleOverlapAction;
use App\Models\LunchBooking;
use App\Models\Site;
use App\Models\User;
use App\Support\TimeInput;

class UpdateLunchBookingAction
{
    use AsAction;

    public function __construct(
        private readonly AssertTeamsAvailabilityForUserAction $assertTeamsAvailability,
        private readonly AssertNoScheduleOverlapAction $assertNoScheduleOverlap,
    ) {}

    public function execute(
        Site $site,
        User $user,
        LunchBooking $booking,
        string $startTime,
        string $endTime,
    ): LunchBooking {
        abort_unless($booking->user_id === $user->id, 403);

        $booking->loadMissing('timeBlock');
        $block = $booking->timeBlock;

        abort_if($block === null, 404);

        $this->assertTeamsAvailability->execute(
            $site,
            $user,
            $block->date->toDateString(),
            $startTime,
            $endTime,
        );

        $this->assertNoScheduleOverlap->execute(
            $user,
            $block->date->toDateString(),
            $startTime,
            $endTime,
            $block->id,
        );

        $block->update([
            'start_time' => TimeInput::forStorage($startTime),
            'end_time'   => TimeInput::forStorage($endTime),
        ]);

        return $booking->refresh();
    }
}
