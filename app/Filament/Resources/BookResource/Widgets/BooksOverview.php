<?php

namespace App\Filament\Resources\BookResource\Widgets;

use App\Models\Book;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BooksOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $query = Book::query();

        return [
            Stat::make('All', (clone $query)->count()),
            Stat::make('Available',(clone $query)->available()->count()),
            Stat::make('Sold', (clone $query)->outOfStock()->count()),
            Stat::make('Total Sales Revenue ', (clone $query)->outOfStock()->sum('price')),
        ];
    }
}
