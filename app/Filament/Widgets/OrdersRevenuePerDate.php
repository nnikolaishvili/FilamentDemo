<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\OrderItem;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Facades\DB;

class OrdersRevenuePerDate extends ChartWidget
{
    protected static ?string $heading = 'Orders Revenue';

    protected function getData(): array
    {
        $orders = Order::query()
            ->addSelect([
                DB::raw('date_format(date, "%Y-%m") as date'),
                'total' => OrderItem::query()
                    ->whereColumn('orders.id', 'order_items.order_id')
                    ->selectRaw('sum(total_price * amount) as total')
            ])->orderBy('date')->get()
            ->groupBy('date')
            ->map(fn($orders) => $orders->sum('total'));

        return [
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => $orders->values()
                ],
            ],
            'labels' => $orders->keys()
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
