<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('device_uuid');
            $table->string('device_type')->default('desktop'); // mobile, desktop
            $table->string('device_name')->nullable();
            $table->string('ip_address')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_active_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'device_uuid']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
