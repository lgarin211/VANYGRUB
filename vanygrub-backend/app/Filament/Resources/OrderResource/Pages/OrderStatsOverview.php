<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $completedOrders = Order::where('status', 'delivered')->count();
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total_amount');
        $todayOrders = Order::whereDate('created_at', today())->count();
        
        return [
            Stat::make('Total Orders', $totalOrders)
                ->description('Semua order yang masuk')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary'),
                
            Stat::make('Pending Orders', $pendingOrders)
                ->description('Order menunggu pembayaran')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
                
            Stat::make('Completed Orders', $completedOrders)
                ->description('Order yang sudah selesai')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
                
            Stat::make('Total Revenue', 'Rp ' . number_format($totalRevenue, 0, ',', '.'))
                ->description('Total pendapatan')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
                
            Stat::make('Today Orders', $todayOrders)
                ->description('Order hari ini')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('info'),
        ];
    }
}