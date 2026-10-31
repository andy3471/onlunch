<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\LunchBooking;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LunchBooking>
 */
class LunchBookingFactory extends Factory
{
    protected $model = LunchBooking::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'team_id' => Team::factory(),
            'user_id' => User::factory(),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (LunchBooking $booking): void {
            if ($booking->timeBlock()->exists()) {
                return;
            }

            $booking->timeBlock()->create([
                'team_id'    => $booking->team_id,
                'user_id'    => $booking->user_id,
                'date'       => now()->toDateString(),
                'start_time' => '12:00:00',
                'end_time'   => '13:00:00',
            ]);
        });
    }
}
