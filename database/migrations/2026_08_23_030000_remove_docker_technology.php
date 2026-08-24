<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $technologyIds = DB::table('technologies')
            ->where('name', 'Docker')
            ->pluck('id');

        if (Schema::hasTable('project_technology')) {
            DB::table('project_technology')
                ->whereIn('technology_id', $technologyIds)
                ->delete();
        }

        DB::table('technologies')->whereIn('id', $technologyIds)->delete();
    }

    public function down(): void
    {
        // Docker is intentionally no longer part of the technology catalogue.
    }
};
