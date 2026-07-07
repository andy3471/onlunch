<?php

declare(strict_types=1);

namespace App\Actions\LunchBookings;

use App\Actions\Concerns\AsAction;
use App\Models\LunchBooking;
use App\Models\Site;
use App\Models\User;

class DeleteLunchBookingAction
{
    use AsAction;

    public function execute(Site $site, User $user, string $date): void
    {
        $team = $user->primaryTeamFor($site);

        if ($team === null) {
            return;
        }

        LunchBooking::query()
            ->where('team_id', $team->id)
            ->where('user_id', $user->id)
            ->whereHas('timeBlock', fn ($query) => $query->where('date', $date))
            ->each(fn (LunchBooking $booking) => $booking->delete());
    }
}
