<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Filament\Resources\ReviewResource\RelationManagers;
use App\Models\Book;
use App\Models\Publisher;
use App\Models\Review;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;
    protected static ?string $navigationGroup = 'Feedback';
    protected static ?string $navigationIcon = 'heroicon-o-star';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()->schema([
                    Forms\Components\TextInput::make('comment')
                        ->required(),
                    Forms\Components\MorphToSelect::make('reviewable')
                        ->label('Where to add review')
                        ->types([
                            Forms\Components\MorphToSelect\Type::make(Book::class)
                                ->titleAttribute('name'),
                            Forms\Components\MorphToSelect\Type::make(Publisher::class)
                                ->titleAttribute('name'),
                        ])
                        ->searchable()
                        ->preload()
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reviewable_type'),
                Tables\Columns\TextColumn::make('reviewable_id'),
                Tables\Columns\TextColumn::make('reviewable.name'),
                Tables\Columns\TextColumn::make('comment'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListReviews::route('/'),
            'create' => Pages\CreateReview::route('/create'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }
}
