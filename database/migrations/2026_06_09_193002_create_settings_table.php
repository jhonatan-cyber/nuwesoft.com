<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Seed default settings
        $defaults = [
            ['key' => 'site_name', 'value' => 'NUWESOFT'],
            ['key' => 'tagline', 'value' => 'Brutal Engineering'],
            ['key' => 'email', 'value' => 'hello@nuwesoft.com'],
            ['key' => 'phone', 'value' => ''],
            ['key' => 'address', 'value' => 'Remote First / Global'],
            ['key' => 'social_facebook', 'value' => ''],
            ['key' => 'social_twitter', 'value' => ''],
            ['key' => 'social_linkedin', 'value' => ''],
            ['key' => 'social_github', 'value' => ''],
            ['key' => 'social_youtube', 'value' => ''],
            ['key' => 'social_tiktok', 'value' => ''],
            ['key' => 'logo_url', 'value' => ''],
        ];

        DB::table('settings')->insert($defaults);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
