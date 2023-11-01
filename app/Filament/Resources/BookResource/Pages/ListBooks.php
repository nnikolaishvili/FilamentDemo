<?php

namespace App\Filament\Resources\BookResource\Pages;

use App\Filament\Resources\BookResource;
use App\Models\Book;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListBooks extends ListRecords
{
    protected static string $resource = BookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'All' => Tab::make()
                ->badge(Book::query()->count()),
            'Available' => Tab::make()
                ->badge(Book::query()->where('is_available', true)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_available', true)),
            'Sold' => Tab::make()
                ->badge(Book::query()->where('is_available', false)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_available', false)),
        ];
    }

    public function getDefaultActiveTab(): string
    {
        return 'Available';
    }
}
