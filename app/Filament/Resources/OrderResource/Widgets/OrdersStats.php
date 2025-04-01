<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;

class OrdersStats extends Widget
{
    protected static string $view = 'filament.widgets.orders-stats';

    protected int | string | array $columnSpan = 'full';

    public $monthlyRevenue;
    public $weeklyRevenue;
    public $monthlyOrders;
    public $weeklyOrders;

    public function mount(): void
    {
        $this->monthlyRevenue = Order::whereMonth('created_at', now()->month)
            ->sum('total_price');

        $this->weeklyRevenue = Order::whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek(),
        ])->sum('total_price');

        $this->monthlyOrders = Order::whereMonth('created_at', now()->month)->count();

        $this->weeklyOrders = Order::whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek(),
        ])->count();
    }
}
