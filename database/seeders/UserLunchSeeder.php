<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\LunchBooking;
use App\Models\Team;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;

class UserLunchSeeder extends Seeder
{
    public function run(): void
    {
        $date = Date::now();

        if (! $date->isWeekday()) {
            return;
        }

        $dateString = $date->toDateString();

        Team::all()->each(function (Team $team) use ($dateString): void {
            foreach ($team->members as $user) {
                LunchBooking::createWithTimeBlock(
                    [
                        'team_id' => $team->id,
                        'user_id' => $user->id,
                    ],
                    [
                        'date'       => $dateString,
                        'start_time' => '12:00',
                        'end_time'   => '12:30',
                    ],
                );
            }
        });
    }
}
