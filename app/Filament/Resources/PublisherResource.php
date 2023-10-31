<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PublisherResource\Pages;
use App\Filament\Resources\PublisherResource\RelationManagers;
use App\Models\Publisher;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PublisherResource extends Resource
{
    protected static ?string $model = Publisher::class;
    protected static ?string $navigationGroup = 'Book Store';
    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('location')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\FileUpload::make('logo_url')
                        ->label('Logo')
                        ->disk('public')
                        ->directory('publishers'),
                    Forms\Components\Toggle::make('is_certified')
                        ->label('Certified'),
                ])->columnSpan(2),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo_url'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('location')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_certified')
                    ->label('Certified')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Registered on')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('is_certified')
                    ->label('Certified')
                    ->query(function (Builder $query) {
                        $query->where('is_certified', true);
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPublishers::route('/'),
            'create' => Pages\CreatePublisher::route('/create'),
            'edit' => Pages\EditPublisher::route('/{record}/edit'),
        ];
    }
}
