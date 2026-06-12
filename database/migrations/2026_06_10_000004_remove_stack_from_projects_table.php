<?php

use App\Models\Project;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Migrate existing stack data to project_technology pivot
        DB::table('projects')->orderBy('id')->chunk(100, function ($projects) {
            foreach ($projects as $project) {
                $stack = json_decode($project->stack ?? '[]', true);
                if (! empty($stack)) {
                    $techIds = DB::table('technologies')
                        ->whereIn('name', $stack)
                        ->pluck('id')
                        ->toArray();
                    if (! empty($techIds)) {
                        $existingPivot = DB::table('project_technology')
                            ->where('project_id', $project->id)
                            ->pluck('technology_id')
                            ->toArray();
                        $newIds = array_diff($techIds, $existingPivot);
                        foreach ($newIds as $techId) {
                            DB::table('project_technology')->insert([
                                'project_id' => $project->id,
                                'technology_id' => $techId,
                            ]);
                        }
                    }
                }
            }
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('stack');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->json('stack')->nullable();
        });
    }
};
