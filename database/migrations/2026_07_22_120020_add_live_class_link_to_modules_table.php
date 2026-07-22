<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('modules', function (Blueprint $table) {
            $table->string('live_class_provider')->nullable()->after('order'); // zoom, google_meet, other
            $table->string('live_class_link')->nullable()->after('live_class_provider');
            $table->dateTime('live_class_at')->nullable()->after('live_class_link');
        });
    }

    public function down(): void
    {
        Schema::table('modules', function (Blueprint $table) {
            $table->dropColumn(['live_class_provider', 'live_class_link', 'live_class_at']);
        });
    }
};
