<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentReminderNotification extends Notification
{
    use Queueable;

    public function __construct(protected Order $order)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $itemName = $this->order->type === 'subscription'
            ? $this->order->subscriptionPlan?->name
            : $this->order->course?->title;

        return (new MailMessage)
            ->subject('আপনার পেমেন্ট এখনো সম্পন্ন হয়নি')
            ->greeting("প্রিয় {$notifiable->name},")
            ->line("\"{$itemName}\" এর জন্য আপনার পেমেন্টটি এখনো সম্পন্ন হয়নি।")
            ->line('এখনই সম্পন্ন করে কোর্সে এক্সেস পেয়ে যান।')
            ->action('পেমেন্ট সম্পন্ন করুন', route('profile.payment-history'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'transaction_id' => $this->order->transaction_id,
            'message' => 'আপনার একটি পেমেন্ট পেন্ডিং আছে।',
        ];
    }
}
