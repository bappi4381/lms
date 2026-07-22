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
            $table->decimal('discount_price', 10, 2)->nullable()->after('price');
            $table->string('video_url')->nullable()->after('thumbnail');
            $table->json('key_features')->nullable();
            $table->json('projects')->nullable();
            $table->json('tools')->nullable();
            $table->json('career_opportunities')->nullable();
            $table->json('faqs')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn([
                'discount_price',
                'video_url',
                'key_features',
                'projects',
                'tools',
                'career_opportunities',
                'faqs',
            ]);
        });
    }
};
