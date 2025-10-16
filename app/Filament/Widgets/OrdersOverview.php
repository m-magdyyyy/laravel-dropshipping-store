<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrdersOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalOrders = Order::count();
        $todayOrders = Order::whereDate('created_at', today())->count();
        $weekOrders = Order::where('created_at', '>=', now()->startOfWeek())->count();
        $monthOrders = Order::where('created_at', '>=', now()->startOfMonth())->count();
        
        $pendingOrders = Order::where('status', 'pending')->count();
        $confirmedOrders = Order::where('status', 'confirmed')->count();
        $shippedOrders = Order::where('status', 'shipped')->count();
        $deliveredOrders = Order::where('status', 'delivered')->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();
        
        // Calculate total revenue (assuming products have prices)
        $totalRevenue = Order::whereIn('status', ['confirmed', 'shipped', 'delivered'])
            ->whereHas('product')
            ->get()
            ->sum(function ($order) {
                return $order->product->price * $order->quantity;
            });
            
        return [
            Stat::make('إجمالي الطلبات', $totalOrders)
                ->description($todayOrders > 0 ? "+{$todayOrders} طلب اليوم" : 'لا توجد طلبات اليوم')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary')
                ->chart([7, 2, 10, 3, 15, 4, $todayOrders]),
            
            Stat::make('طلبات قيد الانتظار', $pendingOrders)
                ->description('تحتاج مراجعة')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning')
                ->extraAttributes([
                    'class' => $pendingOrders > 0 ? 'animate-pulse' : '',
                ]),
            
            Stat::make('طلبات مؤكدة', $confirmedOrders)
                ->description("{$shippedOrders} قيد الشحن")
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            
            Stat::make('طلبات مُسلمة', $deliveredOrders)
                ->description("{$cancelledOrders} طلب ملغي")
                ->descriptionIcon('heroicon-m-truck')
                ->color('info'),
            
            Stat::make('إيرادات الشهر', number_format($totalRevenue, 0) . ' ج.م')
                ->description("من {$monthOrders} طلب هذا الشهر")
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success')
                ->chart([100, 200, 150, 300, 250, 400, $totalRevenue / 100]),
            
            Stat::make('معدل النجاح', $totalOrders > 0 ? round(($deliveredOrders / $totalOrders) * 100, 1) . '%' : '0%')
                ->description('نسبة الطلبات المُسلمة')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color($totalOrders > 0 && ($deliveredOrders / $totalOrders) > 0.8 ? 'success' : 'warning'),
        ];
    }
}
