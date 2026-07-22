<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class RevenueChartWidget extends ChartWidget
{
    protected ?string $heading = 'গত ৬ মাসের আয় (Revenue)';

    protected function getData(): array
    {
        $months = collect(range(5, 0))->map(fn ($i) => now()->subMonths($i));

        $data = $months->map(function ($month) {
            return Order::where('payment_status', 'paid')
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->get()
                ->sum(fn (Order $order) => $order->totalPayable());
        });

        return [
            'datasets' => [
                [
                    'label' => 'আয় (৳)',
                    'data' => $data->toArray(),
                    'backgroundColor' => '#6366f1',
                    'borderColor' => '#4f46e5',
                ],
            ],
            'labels' => $months->map(fn ($m) => $m->format('M Y'))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
