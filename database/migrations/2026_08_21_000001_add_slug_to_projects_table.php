<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
        });

        // Backfill slugs for existing projects
        $projects = DB::table('projects')->select('id', 'name')->get();
        foreach ($projects as $project) {
            $slug = Str::slug($project->name);
            // Ensure uniqueness
            $baseSlug = $slug;
            $counter = 1;
            while (DB::table('projects')->where('slug', $slug)->where('id', '!=', $project->id)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
            DB::table('projects')->where('id', $project->id)->update(['slug' => $slug]);
        }

        Schema::table('projects', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
