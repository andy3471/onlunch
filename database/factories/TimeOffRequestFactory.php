<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\TimeOffRequestStatus;
use App\Models\Team;
use App\Models\TimeOffRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TimeOffRequest>
 */
class TimeOffRequestFactory extends Factory
{
    protected $model = TimeOffRequest::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'team_id' => Team::factory(),
            'user_id' => User::factory(),
            'status'  => TimeOffRequestStatus::Pending,
            'notes'   => null,
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (TimeOffRequest $request): void {
            if ($request->timeBlock()->exists()) {
                return;
            }

            $request->timeBlock()->create([
                'team_id'    => $request->team_id,
                'user_id'    => $request->user_id,
                'date'       => now()->toDateString(),
                'start_time' => '09:00:00',
                'end_time'   => '17:00:00',
            ]);
        });
    }
}
