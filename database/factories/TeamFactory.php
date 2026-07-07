<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Site;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Team> */
class TeamFactory extends Factory
{
    protected $model = Team::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'site_id'                 => Site::factory(),
            'name'                    => fake()->randomElement(['Everyone', 'Development', 'Operations']),
            'tasks_enabled'           => true,
            'time_off_auto_approve'   => false,
            'minimum_available_staff' => null,
            'default_task'            => 'none',
        ];
    }
}
