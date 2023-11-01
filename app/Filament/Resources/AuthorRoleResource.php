<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuthorRoleResource\Pages;
use App\Filament\Resources\AuthorRoleResource\RelationManagers;
use App\Models\AuthorRole;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AuthorRoleResource extends Resource
{
    protected static ?string $model = AuthorRole::class;
    protected static ?string $navigationGroup = 'Authors';
    protected static ?string $navigationLabel = 'Roles';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
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
            'index' => Pages\ListAuthorRoles::route('/'),
            'create' => Pages\CreateAuthorRole::route('/create'),
            'edit' => Pages\EditAuthorRole::route('/{record}/edit'),
        ];
    }
}
