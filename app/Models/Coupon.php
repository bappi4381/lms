<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'type',
        'value',
        'max_uses',
        'used_count',
        'min_order_amount',
        'starts_at',
        'expires_at',
        'is_active',
        'applicable_to',
        'course_ids',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'course_ids' => 'array',
    ];

    public function isValidFor(?int $courseId, float $amount): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->starts_at && $this->starts_at->isFuture()) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        if ($this->max_uses && $this->used_count >= $this->max_uses) {
            return false;
        }

        if ($this->min_order_amount && $amount < (float) $this->min_order_amount) {
            return false;
        }

        if ($this->applicable_to === 'specific_courses' && $courseId) {
            $ids = $this->course_ids ?? [];

            return in_array($courseId, $ids, false) || in_array((string) $courseId, $ids, true);
        }

        return true;
    }

    public function calculateDiscount(float $amount): float
    {
        $discount = $this->type === 'percent'
            ? $amount * ((float) $this->value / 100)
            : (float) $this->value;

        return round(min($discount, $amount), 2);
    }
}
