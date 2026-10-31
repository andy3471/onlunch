<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Site;
use App\Models\WorkingHourPreset;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WorkingHourPreset>
 */
class WorkingHourPresetFactory extends Factory
{
    protected $model = WorkingHourPreset::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'site_id'    => Site::factory(),
            'name'       => fake()->randomElement(['Standard', 'Early', 'Late']),
            'start_time' => '09:00:00',
            'end_time'   => '17:00:00',
            'sort_order' => 0,
        ];
    }
}
