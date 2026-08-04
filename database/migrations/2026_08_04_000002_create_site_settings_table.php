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
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            
            // Header Settings
            $table->json('header_links')->nullable();

            // Footer Brand Details (BN / EN)
            $table->text('brand_description_en')->nullable();
            $table->text('brand_description_bn')->nullable();

            // Footer Navigation Columns (BN / EN Titles & Links)
            $table->json('footer_columns')->nullable();

            // Contact Info
            $table->string('contact_email')->nullable();
            $table->string('contact_phone_en')->nullable();
            $table->string('contact_phone_bn')->nullable();
            $table->string('contact_address_en')->nullable();
            $table->string('contact_address_bn')->nullable();

            // Social Media Links
            $table->json('social_links')->nullable();

            // Copyright Text (BN / EN)
            $table->string('copyright_text_en')->nullable();
            $table->string('copyright_text_bn')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
