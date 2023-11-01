<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Book;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationGroup = 'Orders';
    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make()->schema([
                        Forms\Components\TextInput::make('number')
                            ->label('Order number')
                            ->default(random_int(10000, 99999))
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->required()
                            ->options([
                                'processing' => 'Processing',
                                'shipped' => 'Shipped',
                                'delivered' => 'Delivered'
                            ]),
                        Forms\Components\DatePicker::make('date')
                            ->default(now())
                            ->required()
                    ])->columns(3)
                        ->columnSpan('full'),

                    Forms\Components\Section::make()->schema([
                        Forms\Components\Placeholder::make('Books'),

                        Forms\Components\Repeater::make('orderItems')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('book_id')
                                    ->label('Book')
                                    ->options(Book::query()->pluck('name', 'id'))
                                    ->required()
                                    ->reactive() // for price auto refreshing
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $book = Book::find($state);
                                        if ($book) {
                                            $set('total_price', $book->price);
                                        }
                                    })
                                    ->columnSpan(['md' => 5]),
                                Forms\Components\TextInput::make('amount')
                                    ->numeric()
                                    ->required()
                                    ->default(1)
                                    ->columnSpan(['md' => 2]),
                                Forms\Components\TextInput::make('total_price')
                                    ->numeric()
                                    ->readOnly()
                                    ->columnSpan([
                                        'md' => 3
                                    ]),
                            ])
                            ->columnSpan(2)
                            ->defaultItems(1)
                    ])->columns(3)
                ])
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\SelectColumn::make('status')
                    ->options([
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered'
                    ])
                    ->selectablePlaceholder(false),
                Tables\Columns\TextColumn::make('date')
                    ->date(),
                Tables\Columns\TextColumn::make('order_items_count')
                    ->label('Items Count')
                    ->counts('orderItems')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('orderItems.total_price')
                    ->prefix('€')
                    ->label('Total price')
                    ->getStateUsing(function (Order $order) {
                        $total = 0;
                        $orderItems = $order->orderItems()->select('total_price', 'amount')->get();

                        $orderItems->map(function ($item) use (&$total) {
                            $total += $item->amount * $item->total_price;

                            return $total;
                        });

                        return $total;
                    })
            ])
            ->filters([
                Tables\Filters\Filter::make('date')
                    ->form([
                        DatePicker::make('from')->label('Created from'),
                        DatePicker::make('until')->label('Created to'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
            ])
            ->bulkActions([
                //
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
