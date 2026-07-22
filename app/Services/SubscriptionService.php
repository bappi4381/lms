<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Subscription;
use Illuminate\Support\Carbon;

class SubscriptionService
{
    /**
     * Activate (or extend, if already active) a subscription for the
     * given paid order.
     */
    public function activateFromOrder(Order $order): Subscription
    {
        $plan = $order->subscriptionPlan;

        $existing = Subscription::where('user_id', $order->user_id)
            ->where('subscription_plan_id', $plan->id)
            ->where('status', 'active')
            ->where('ends_at', '>=', now())
            ->latest('ends_at')
            ->first();

        $startsAt = $existing ? $existing->ends_at : now();
        $endsAt = $plan->interval === 'yearly'
            ? Carbon::parse($startsAt)->addYear()
            : Carbon::parse($startsAt)->addMonth();

        if ($existing) {
            $existing->update([
                'ends_at' => $endsAt,
                'transaction_id' => $order->transaction_id,
            ]);

            return $existing;
        }

        return Subscription::create([
            'user_id' => $order->user_id,
            'subscription_plan_id' => $plan->id,
            'status' => 'active',
            'starts_at' => now(),
            'ends_at' => $endsAt,
            'transaction_id' => $order->transaction_id,
            'auto_renew' => true,
        ]);
    }
}
