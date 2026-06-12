<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_images', function ($table) {
            $table->renameColumn('order', 'order_index');
        });
    }

    public function down(): void
    {
        Schema::table('project_images', function ($table) {
            $table->renameColumn('order_index', 'order');
        });
    }
};
