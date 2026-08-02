<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->enum('main_type', ['academic', 'skills', 'test_prep', 'professional'])
                ->nullable()
                ->after('parent_id');
            $table->string('name_en')->nullable()->after('main_type');
            $table->string('name_bn')->nullable()->after('name_en');
            $table->string('slug_en')->nullable()->after('name_bn');
            $table->string('slug_bn')->nullable()->after('slug_en');
        });

        // Backfill: existing single-locale name/slug become the starting value
        // for both locales. Admins can edit the Bangla side afterwards.
        DB::table('categories')->select('id', 'name', 'slug')->get()->each(function ($category) {
            DB::table('categories')->where('id', $category->id)->update([
                'name_en' => $category->name,
                'name_bn' => $category->name,
                'slug_en' => $category->slug,
                'slug_bn' => $category->slug,
            ]);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['name', 'slug']);
            $table->unique('slug_en');
            $table->unique('slug_bn');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('name')->nullable()->after('parent_id');
            $table->string('slug')->nullable()->after('name');
        });

        DB::table('categories')->select('id', 'name_en', 'slug_en')->get()->each(function ($category) {
            DB::table('categories')->where('id', $category->id)->update([
                'name' => $category->name_en,
                'slug' => $category->slug_en,
            ]);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->unique('slug');
            $table->dropUnique(['slug_en']);
            $table->dropUnique(['slug_bn']);
            $table->dropColumn(['main_type', 'name_en', 'name_bn', 'slug_en', 'slug_bn']);
        });
    }
};
