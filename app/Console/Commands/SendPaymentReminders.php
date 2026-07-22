<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Notifications\PaymentReminderNotification;
use Illuminate\Console\Command;

class SendPaymentReminders extends Command
{
    protected $signature = 'reminders:send-payment';

    protected $description = 'Send a reminder to users with pending (unpaid) orders older than 1 hour';

    public function handle(): int
    {
        $orders = Order::where('payment_status', 'pending')
            ->where('created_at', '<=', now()->subHour())
            ->where('created_at', '>=', now()->subDays(3))
            ->with('user')
            ->get();

        foreach ($orders as $order) {
            if ($order->user) {
                $order->user->notify(new PaymentReminderNotification($order));
            }
        }

        $this->info("Sent {$orders->count()} payment reminder(s).");

        return self::SUCCESS;
    }
}
