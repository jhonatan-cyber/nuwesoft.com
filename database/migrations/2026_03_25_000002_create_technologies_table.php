<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('technologies', function (Blueprint $col) {
            $col->id();
            $col->string('name');
            $col->string('logo_url')->nullable();
            $col->string('category')->default('backend');
            $col->boolean('is_active')->default(true);
            $col->timestamps();
        });

        Schema::create('project_technology', function (Blueprint $col) {
            $col->id();
            $col->foreignId('project_id')->constrained()->onDelete('cascade');
            $col->foreignId('technology_id')->constrained()->onDelete('cascade');
            $col->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_technology');
        Schema::dropIfExists('technologies');
    }
};
