<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('live_class_schedule')->nullable()->after('starts_at');
            $table->string('support_class_schedule')->nullable()->after('live_class_schedule');
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['live_class_schedule', 'support_class_schedule']);
        });
    }
};
