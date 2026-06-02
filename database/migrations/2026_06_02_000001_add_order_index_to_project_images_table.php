<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('project_images')) {
            return;
        }

        $hasOrderIndex = Schema::hasColumn('project_images', 'order_index');

        if (!$hasOrderIndex) {
            Schema::table('project_images', function (Blueprint $table) {
                $table->integer('order_index')->default(0);
            });
        }

        if (Schema::hasColumn('project_images', 'order')) {
            $rows = DB::table('project_images')->select('id', 'order')->get();
            foreach ($rows as $row) {
                DB::table('project_images')->where('id', $row->id)->update([
                    'order_index' => $row->order,
                ]);
            }
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('project_images')) {
            return;
        }

        if (Schema::hasColumn('project_images', 'order_index')) {
            Schema::table('project_images', function (Blueprint $table) {
                $table->dropColumn('order_index');
            });
        }
    }
};

