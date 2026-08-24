<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'site_name' => 'NUWESOFT',
            'tagline' => 'Brutal Engineering',
            'email' => 'hello@nuwesoft.com',
            'phone' => '',
            'address' => 'Remote First / Global',
            'social_facebook' => '',
            'social_twitter' => '',
            'social_linkedin' => '',
            'social_github' => '',
            'social_youtube' => '',
            'social_tiktok' => '',
            'logo_url' => '',
        ];

        foreach ($defaults as $key => $value) {
            Setting::firstOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }
}
