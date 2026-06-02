<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('technologies')) {
            return;
        }

        if (!Schema::hasColumn('technologies', 'logo_public_id')) {
            Schema::table('technologies', function (Blueprint $table) {
                $table->string('logo_public_id')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('technologies')) {
            return;
        }

        if (Schema::hasColumn('technologies', 'logo_public_id')) {
            Schema::table('technologies', function (Blueprint $table) {
                $table->dropColumn('logo_public_id');
            });
        }
    }
};

