<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'category' => fake()->randomElement(['web', 'mobile', 'design', 'other']),
            'stack' => fake()->randomElements(['Vue.js', 'React', 'Laravel', 'Node.js', 'Tailwind', 'TypeScript', 'Docker', 'PostgreSQL'], rand(2, 5)),
            'desc' => fake()->paragraph(),
            'icon' => 'Briefcase',
            'image_url' => null,
            'project_url' => fake()->url(),
            'is_active' => true,
        ];
    }
}
