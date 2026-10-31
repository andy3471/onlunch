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

        $this->deleteForTeamUserDate($team->id, $user->id, $date);
    }

    public function deleteForTeamUserDate(string $teamId, string $userId, string $date): void
    {
        LunchBooking::query()
            ->where('team_id', $teamId)
            ->where('user_id', $userId)
            ->whereHas('timeBlock', fn ($query) => $query->where('date', $date))
            ->each(fn (LunchBooking $booking) => $booking->delete());
    }
}
