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
        Schema::table('users', function (Blueprint $table) {
            $table->string('designation')->nullable()->after('email');
            $table->string('company')->nullable()->after('designation');
            $table->text('about')->nullable()->after('company');
            $table->string('profile_photo_url')->nullable()->after('about');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'designation',
                'company',
                'about',
                'profile_photo_url',
            ]);
        });
    }
};
