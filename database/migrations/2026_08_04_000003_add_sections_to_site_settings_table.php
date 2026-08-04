<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            // Hero Section (BN / EN)
            $table->string('hero_eyebrow_en')->nullable()->after('header_links');
            $table->string('hero_eyebrow_bn')->nullable()->after('hero_eyebrow_en');
            $table->string('hero_title_en')->nullable()->after('hero_eyebrow_bn');
            $table->string('hero_title_bn')->nullable()->after('hero_title_en');
            $table->string('hero_highlight_en')->nullable()->after('hero_title_bn');
            $table->string('hero_highlight_bn')->nullable()->after('hero_highlight_en');
            $table->text('hero_description_en')->nullable()->after('hero_highlight_bn');
            $table->text('hero_description_bn')->nullable()->after('hero_description_en');
            $table->string('hero_btn_primary_en')->nullable()->after('hero_description_bn');
            $table->string('hero_btn_primary_bn')->nullable()->after('hero_btn_primary_en');
            $table->string('hero_btn_secondary_en')->nullable()->after('hero_btn_primary_bn');
            $table->string('hero_btn_secondary_bn')->nullable()->after('hero_btn_secondary_en');

            // About Section (BN / EN)
            $table->string('about_eyebrow_en')->nullable()->after('hero_btn_secondary_bn');
            $table->string('about_eyebrow_bn')->nullable()->after('about_eyebrow_en');
            $table->string('about_title_en')->nullable()->after('about_eyebrow_bn');
            $table->string('about_title_bn')->nullable()->after('about_title_en');
            $table->text('about_description_en')->nullable()->after('about_title_bn');
            $table->text('about_description_bn')->nullable()->after('about_description_en');
            $table->string('about_btn_en')->nullable()->after('about_description_bn');
            $table->string('about_btn_bn')->nullable()->after('about_btn_en');

            // Why Choose Us Section (BN / EN)
            $table->string('whyus_eyebrow_en')->nullable()->after('about_btn_bn');
            $table->string('whyus_eyebrow_bn')->nullable()->after('whyus_eyebrow_en');
            $table->string('whyus_title_en')->nullable()->after('whyus_eyebrow_bn');
            $table->string('whyus_title_bn')->nullable()->after('whyus_title_en');
            $table->json('whyus_cards')->nullable()->after('whyus_title_bn');

            // Pricing Section (BN / EN)
            $table->string('pricing_eyebrow_en')->nullable()->after('whyus_cards');
            $table->string('pricing_eyebrow_bn')->nullable()->after('pricing_eyebrow_en');
            $table->string('pricing_title_en')->nullable()->after('pricing_eyebrow_bn');
            $table->string('pricing_title_bn')->nullable()->after('pricing_title_en');

            // Testimonials Section (BN / EN)
            $table->string('testi_eyebrow_en')->nullable()->after('pricing_title_bn');
            $table->string('testi_eyebrow_bn')->nullable()->after('testi_eyebrow_en');
            $table->string('testi_title_en')->nullable()->after('testi_eyebrow_bn');
            $table->string('testi_title_bn')->nullable()->after('testi_title_en');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'hero_eyebrow_en', 'hero_eyebrow_bn',
                'hero_title_en', 'hero_title_bn',
                'hero_highlight_en', 'hero_highlight_bn',
                'hero_description_en', 'hero_description_bn',
                'hero_btn_primary_en', 'hero_btn_primary_bn',
                'hero_btn_secondary_en', 'hero_btn_secondary_bn',
                'about_eyebrow_en', 'about_eyebrow_bn',
                'about_title_en', 'about_title_bn',
                'about_description_en', 'about_description_bn',
                'about_btn_en', 'about_btn_bn',
                'whyus_eyebrow_en', 'whyus_eyebrow_bn',
                'whyus_title_en', 'whyus_title_bn',
                'whyus_cards',
                'pricing_eyebrow_en', 'pricing_eyebrow_bn',
                'pricing_title_en', 'pricing_title_bn',
                'testi_eyebrow_en', 'testi_eyebrow_bn',
                'testi_title_en', 'testi_title_bn',
            ]);
        });
    }
};
