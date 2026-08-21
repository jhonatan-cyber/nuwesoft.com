<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TechnologyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word(),
            'logo_url' => null,
            'category' => fake()->randomElement(['languages', 'frontend', 'backend', 'mobile', 'database', 'infrastructure', 'automation', 'tools', 'ui']),
            'is_active' => true,
        ];
    }
}
