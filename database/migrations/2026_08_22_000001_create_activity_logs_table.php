<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type');           // 'created', 'updated', 'deleted', 'login', 'logout'
            $table->string('description');    // 'Created project "E-commerce Platform"'
            $table->string('subject_type')->nullable(); // 'App\Models\Project'
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->json('properties')->nullable(); // Old/new values, IP, etc.
            $table->timestamps();

            $table->index(['subject_type', 'subject_id']);
            $table->index('type');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
