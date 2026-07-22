<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('type')->default('course')->after('user_id'); // course, subscription
            $table->foreignId('subscription_plan_id')->nullable()->after('course_id')
                ->constrained()->nullOnDelete();
            $table->foreignId('coupon_id')->nullable()->after('subscription_plan_id')
                ->constrained()->nullOnDelete();
            $table->decimal('discount_amount', 10, 2)->default(0)->after('amount');
            $table->unsignedTinyInteger('emi_instalments')->nullable()->after('discount_amount');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('course_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('subscription_plan_id');
            $table->dropConstrainedForeignId('coupon_id');
            $table->dropColumn(['type', 'discount_amount', 'emi_instalments']);
        });
    }
};
