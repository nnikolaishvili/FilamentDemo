<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'All' => Tab::make()->icon('heroicon-o-clipboard-document-list'),
            'Processing' => Tab::make()
                ->icon('heroicon-o-magnifying-glass-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query->status('processing')),
            'Shipped' => Tab::make()
                ->icon('heroicon-o-check-badge')
                ->modifyQueryUsing(fn (Builder $query) => $query->status('shipped')),
            'Delivered' => Tab::make()
                ->icon('heroicon-o-arrow-down-on-square')
                ->modifyQueryUsing(fn (Builder $query) => $query->status('delivered'))
        ];
    }

    public function getDefaultActiveTab(): string
    {
        return 'Processing';
    }
}
