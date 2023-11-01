<?php

namespace App\Filament\Resources\BookResource\RelationManagers;

use App\Enums\Book\Language;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class BookDetailRelationManager extends RelationManager
{
    protected static bool $canCreateAnother = false;
    protected static string $relationship = 'bookDetail';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('pages_count')
                    ->required()
                    ->numeric()
                    ->visibleOn('create'),
                Forms\Components\Select::make('language')
                    ->required()
                    ->options(Language::getCases())
                    ->visibleOn('create')
            ])->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('description')
            ->columns([
                Tables\Columns\TextColumn::make('description'),
                Tables\Columns\TextColumn::make('pages_count'),
                Tables\Columns\SelectColumn::make('language')
                    ->options(Language::getCases())
                    ->selectablePlaceholder(false)
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }
}
