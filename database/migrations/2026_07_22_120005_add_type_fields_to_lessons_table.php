<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->string('type')->default('video')->after('title'); // video, pdf, quiz, assignment
            $table->string('pdf_url')->nullable()->after('video_id');
            $table->text('content')->nullable()->after('pdf_url'); // free-text notes/description
        });
    }

    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn(['type', 'pdf_url', 'content']);
        });
    }
};
