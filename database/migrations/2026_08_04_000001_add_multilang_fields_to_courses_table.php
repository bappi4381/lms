<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add _en / _bn variants for the textual fields that need bilingual
     * content on the course landing page.
     *
     * Strategy (same as categories):
     *  - Add new nullable _en / _bn columns.
     *  - Backfill both columns from the existing single-locale column.
     *  - Leave the old columns in place so existing code keeps working;
     *    the Course model's virtual attribute will resolve the right one.
     */
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            // Title
            $table->string('title_en')->nullable()->after('title');
            $table->string('title_bn')->nullable()->after('title_en');

            // Sub-description (short tagline)
            $table->text('sub_description_en')->nullable()->after('sub_description');
            $table->text('sub_description_bn')->nullable()->after('sub_description_en');

            // Main description (rich HTML)
            $table->longText('description_en')->nullable()->after('description');
            $table->longText('description_bn')->nullable()->after('description_en');

            // JSON fields that may contain bilingual text arrays
            $table->json('key_features_en')->nullable()->after('key_features');
            $table->json('key_features_bn')->nullable()->after('key_features_en');

            $table->json('faqs_en')->nullable()->after('faqs');
            $table->json('faqs_bn')->nullable()->after('faqs_en');

            $table->json('projects_en')->nullable()->after('projects');
            $table->json('projects_bn')->nullable()->after('projects_en');

            $table->json('tools_en')->nullable()->after('tools');
            $table->json('tools_bn')->nullable()->after('tools_en');
        });

        // Backfill: existing content becomes the English version;
        // BN starts as a copy (admins can update via panel).
        DB::table('courses')->select('id', 'title', 'sub_description', 'description', 'key_features', 'faqs', 'projects', 'tools')->get()->each(function ($course) {
            DB::table('courses')->where('id', $course->id)->update([
                'title_en'           => $course->title,
                'title_bn'           => $course->title,
                'sub_description_en' => $course->sub_description,
                'sub_description_bn' => $course->sub_description,
                'description_en'     => $course->description,
                'description_bn'     => $course->description,
                'key_features_en'    => $course->key_features,
                'key_features_bn'    => $course->key_features,
                'faqs_en'            => $course->faqs,
                'faqs_bn'            => $course->faqs,
                'projects_en'        => $course->projects,
                'projects_bn'        => $course->projects,
                'tools_en'           => $course->tools,
                'tools_bn'           => $course->tools,
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn([
                'title_en', 'title_bn',
                'sub_description_en', 'sub_description_bn',
                'description_en', 'description_bn',
                'key_features_en', 'key_features_bn',
                'faqs_en', 'faqs_bn',
                'projects_en', 'projects_bn',
                'tools_en', 'tools_bn',
            ]);
        });
    }
};
