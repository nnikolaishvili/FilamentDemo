<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookResource\Pages;
use App\Filament\Resources\BookResource\RelationManagers;
use App\Models\Book;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookResource extends Resource
{
    protected static ?string $model = Book::class;
    protected static ?string $navigationGroup = 'Book Store';
    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Select::make('genre_id')
                        ->relationship('genre', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Forms\Components\TagsInput::make('tags'),
                    Forms\Components\TextInput::make('price')
                        ->required()
                        ->numeric()
                        ->rules([
                            'min:0',
                            'max:10000'
                        ])
                        ->prefix('€'),
                    Forms\Components\Toggle::make('is_best_seller')
                        ->label('Is Best Seller'),
                ])->columnSpan(2),
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('Publication')->schema([
                        Forms\Components\Select::make('publisher_id')
                            ->relationship('publisher', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')->required(),
                                Forms\Components\TextInput::make('location')->required(),
                                Forms\Components\FileUpload::make('logo_url')
                                    ->label('Logo')
                                    ->disk('public')
                                    ->directory('publishers'),
                                Forms\Components\Toggle::make('is_certified')->label('Certified'),
                            ]),
                        Forms\Components\DatePicker::make('publication_date')
                            ->required()
                            ->before('today'),
                    ])->collapsible(),

                    Forms\Components\Section::make('Image')->schema([
                        Forms\Components\FileUpload::make('thumbnail')
                            ->disk('public')
                            ->hiddenLabel()
                            ->directory('books'),
                    ])->collapsible()
                ])->columnSpan(1)
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label('Image'),
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('publisher.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('publication_date')
                    ->label('Published on')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('genre.name')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('is_best_seller')
                    ->label('Best Seller')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('genre_id')
                    ->label('Genres')
                    ->relationship('genre', 'name')
                    ->searchable()
                    ->multiple()
                    ->preload(),
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\TernaryFilter::make('is_best_seller')
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
            RelationManagers\BookDetailRelationManager::make(),
            RelationManagers\AuthorsRelationManager::make()
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBooks::route('/'),
            'create' => Pages\CreateBook::route('/create'),
            'edit' => Pages\EditBook::route('/{record}/edit'),
        ];
    }
}
