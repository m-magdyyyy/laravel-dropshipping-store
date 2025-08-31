<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class KpiOverview extends StatsOverviewWidget
{
    protected static ?string $heading = 'مؤشرات أداء رئيسية';

    protected function getStats(): array
    {
        $products = Product::count();
        $orders = Order::count();

        $revenue = Order::whereIn('status', ['confirmed','shipped','delivered'])
            ->get()
            ->sum(function ($order) {
                return ($order->product ? $order->product->price : 0) * $order->quantity;
            });

        return [
            Stat::make('عدد المنتجات', $products)
                ->description('مخزون كامل')
                ->color('primary'),

            Stat::make('عدد الطلبات', $orders)
                ->description('جميع الطلبات منذ البداية')
                ->color('info'),

            Stat::make('الإيرادات', number_format($revenue, 0) . ' ج.م')
                ->description('طلبات مؤكدة وشحنت')
                ->color('success'),
        ];
    }
}
