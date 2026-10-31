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
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
        $booking->loadMissing('timeBlock');
        $block = $booking->timeBlock;

        if ($block === null) {
            throw (new ModelNotFoundException)->setModel(LunchBooking::class, [$booking->id]);
        }

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
