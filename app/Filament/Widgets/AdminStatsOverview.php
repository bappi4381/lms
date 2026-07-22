<?php

namespace App\Filament\Widgets;

use App\Models\Device;
use App\Models\Enrollment;
use App\Models\Order;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalRevenue = Order::where('payment_status', 'paid')->get()
            ->sum(fn (Order $order) => $order->totalPayable());

        $thisMonthRevenue = Order::where('payment_status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->get()
            ->sum(fn (Order $order) => $order->totalPayable());

        return [
            Stat::make('মোট আয়', '৳'.number_format($totalRevenue, 0))
                ->description('৳'.number_format($thisMonthRevenue, 0).' এই মাসে')
                ->color('success')
                ->icon('heroicon-o-banknotes'),

            Stat::make('মোট ইউজার', User::count())
                ->description(User::role('student')->count().' জন স্টুডেন্ট')
                ->color('info')
                ->icon('heroicon-o-users'),

            Stat::make('সক্রিয় এনরোলমেন্ট', Enrollment::where('payment_status', 'paid')->count())
                ->description(Enrollment::where('payment_status', 'pending')->count().' পেন্ডিং')
                ->color('primary')
                ->icon('heroicon-o-academic-cap'),

            Stat::make('সক্রিয় ডিভাইস', Device::where('is_active', true)->count())
                ->description(Device::where('device_type', 'mobile')->count().' মোবাইল')
                ->color('warning')
                ->icon('heroicon-o-device-phone-mobile'),

            Stat::make('রিফান্ড রিকোয়েস্ট', Order::where('payment_status', 'refund_requested')->count())
                ->color('danger')
                ->icon('heroicon-o-arrow-uturn-left'),
        ];
    }
}
