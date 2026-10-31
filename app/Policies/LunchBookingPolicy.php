<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\LunchBooking;
use App\Models\Site;
use App\Models\User;

class LunchBookingPolicy
{
    public function update(User $user, LunchBooking $booking): bool
    {
        return $user->id === $booking->user_id;
    }

    public function delete(User $user, LunchBooking $booking): bool
    {
        return $user->id === $booking->user_id;
    }

    public function create(User $user): bool
    {
        $site = app()->bound(Site::class) ? app(Site::class) : null;

        return $site instanceof Site && $user->siteMembershipFor($site) !== null;
    }
}
