<?php

namespace App\Filament\Resources\BookResource\Pages;

use App\Filament\Resources\BookResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Colors\Color;

class EditBook extends EditRecord
{
    protected static string $resource = BookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Make unavailable')->action(function () {
                return $this->record->update([
                    'is_out_of_stock' => true
                ]);
            })->color(Color::Gray)->url(fn (): string => route('filament.admin.resources.books.index')),
            Actions\DeleteAction::make(),
        ];
    }
}
