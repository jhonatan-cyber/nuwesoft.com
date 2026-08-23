<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            SettingSeeder::class,
            TechnologySeeder::class,
            ProjectSeeder::class,
            ProjectImageSeeder::class,
            PostSeeder::class,
            TestimonialSeeder::class,
        ]);
    }
}
