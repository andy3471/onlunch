<?php

declare(strict_types=1);

namespace App\Http\Controllers\Site;

use App\Actions\LunchBookings\DeleteLunchBookingAction;
use App\Actions\LunchBookings\StoreLunchBookingAction;
use App\Actions\LunchBookings\UpdateLunchBookingAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLunchBookingRequest;
use App\Http\Requests\UpdateLunchBookingRequest;
use App\Models\LunchBooking;
use App\Models\Site;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

class LunchBookingController extends Controller
{
    public function __construct(
        private readonly StoreLunchBookingAction $storeLunchBooking,
        private readonly UpdateLunchBookingAction $updateLunchBooking,
        private readonly DeleteLunchBookingAction $deleteLunchBooking,
    ) {}

    public function store(StoreLunchBookingRequest $request, Site $site): RedirectResponse
    {
        /** @var User $user */
        $user  = $request->user();
        $today = Date::today()->toDateString();

        $this->storeLunchBooking->execute(
            $site,
            $user,
            $today,
            $request->validated('start_time'),
            $request->validated('end_time'),
        );

        return redirect()->route('home')->with('message', 'Lunch booked.');
    }

    public function update(UpdateLunchBookingRequest $request, Site $site): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $booking = LunchBooking::query()
            ->whereIn('team_id', $site->teamIds())
            ->with('timeBlock')
            ->findOrFail((string) $request->route('lunchBooking'));

        $this->authorize('update', $booking);

        $this->updateLunchBooking->execute(
            $site,
            $user,
            $booking,
            $request->validated('start_time'),
            $request->validated('end_time'),
        );

        $date = $booking->timeBlock->date->toDateString();

        return redirect('/?date='.$date)->with('message', 'Lunch updated.');
    }

    public function destroy(Request $request, Site $site): RedirectResponse
    {
        /** @var User $user */
        $user  = $request->user();
        $today = Date::today()->toDateString();

        $this->deleteLunchBooking->execute($site, $user, $today);

        return redirect()->route('home')->with('message', 'Lunch booking removed.');
    }
}
