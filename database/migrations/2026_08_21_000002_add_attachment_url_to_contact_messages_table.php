<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->string('attachment_url')->nullable()->after('mensaje');
            $table->string('attachment_name')->nullable()->after('attachment_url');
            $table->string('attachment_public_id')->nullable()->after('attachment_name');
        });
    }

    public function down(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->dropColumn(['attachment_url', 'attachment_name', 'attachment_public_id']);
        });
    }
};
