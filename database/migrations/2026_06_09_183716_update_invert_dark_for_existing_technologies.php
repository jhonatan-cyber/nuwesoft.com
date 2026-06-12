<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Technology;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Set invert_dark = true for dark/monochrome logos
        Technology::whereIn('name', ['Next.js', 'Django', 'Flask', 'Express'])
            ->update(['invert_dark' => true]);

        // Set invert_dark = false for all other technologies
        Technology::whereNotIn('name', ['Next.js', 'Django', 'Flask', 'Express'])
            ->whereNull('invert_dark')
            ->update(['invert_dark' => false]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert: set all invert_dark back to default
        Technology::query()->update(['invert_dark' => false]);
    }
};
