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
        Schema::table('courses', function (Blueprint $table) {
            $table->integer('batch_number')->default(1)->after('category_id');
            $table->integer('seats_total')->default(0)->after('batch_number');
            $table->integer('seats_available')->default(0)->after('seats_total');
            $table->dateTime('starts_at')->nullable()->after('seats_available');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['batch_number', 'seats_total', 'seats_available', 'starts_at']);
        });
    }
};
