<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class SalesChart extends ChartWidget
{
    protected static ?string $heading = 'مخطط مبيعات الأسبوع';

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $labels = [];
        $data = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('d M');
            $labels[] = $date;

            $total = Order::whereDate('created_at', now()->subDays($i))->whereIn('status', ['confirmed','shipped','delivered'])
                ->get()
                ->sum(function ($order) {
                    return ($order->product ? $order->product->price : 0) * $order->quantity;
                });

            $data[] = round($total);
        }

        return [
            'datasets' => [
                [
                    'label' => 'إيرادات (ج.م)',
                    'backgroundColor' => 'rgba(59,130,246,0.2)',
                    'borderColor' => 'rgba(59,130,246,1)',
                    'data' => $data,
                ],
            ],
            'labels' => $labels,
        ];
    }

    public function getType(): string
    {
        return 'line';
    }
}
