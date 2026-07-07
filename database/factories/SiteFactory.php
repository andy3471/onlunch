<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Site;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<Site> */
class SiteFactory extends Factory
{
    protected $model = Site::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        $name = fake()->company();

        return [
            'name'                   => $name,
            'slug'                   => Str::slug($name).'-'.fake()->unique()->numberBetween(1, 9999),
            'register_enabled'       => true,
            'reset_password_enabled' => false,
        ];
    }
}
