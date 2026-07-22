<?php

namespace App\Services;

use App\Models\Coupon;

class CouponService
{
    /**
     * Attempt to apply a coupon code to an order amount.
     * Returns [Coupon, discountAmount] or null if invalid.
     */
    public function apply(string $code, float $amount, ?int $courseId = null): ?array
    {
        $coupon = Coupon::where('code', strtoupper($code))->first();

        if (! $coupon || ! $coupon->isValidFor($courseId, $amount)) {
            return null;
        }

        return [$coupon, $coupon->calculateDiscount($amount)];
    }

    public function redeem(Coupon $coupon): void
    {
        $coupon->increment('used_count');
    }
}
