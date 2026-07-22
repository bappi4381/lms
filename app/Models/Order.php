<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'course_id',
        'subscription_plan_id',
        'coupon_id',
        'amount',
        'discount_amount',
        'emi_instalments',
        'transaction_id',
        'payment_status',
        'payment_method',
        'payment_details',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'payment_details' => 'array',
    ];

    public const TYPE_COURSE = 'course';

    public const TYPE_SUBSCRIPTION = 'subscription';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function subscriptionPlan()
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function totalPayable(): float
    {
        return max(0, (float) $this->amount - (float) $this->discount_amount);
    }
}
