<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationLabel = 'Užsakymas';
    protected static ?string $modelLabel = 'Užsakymas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('car_number')->label('Mašinos numeris')->required(),
                Select::make('status')
                    ->label('Statusas')
                    ->required()
                    ->default('awaiting_payment')
                    ->options([
                        'awaiting_payment' => 'Laukia apmokėjimo',
                        'awaiting_shipment' => 'Laukia išsiuntimo',
                        'shipped' => 'Išsiųstas',
                        'completed' => 'Įvykdytas',
                    ]),
                Forms\Components\FileUpload::make('image')
                    ->label('Vaizdas')
                    ->image()
                    ->directory('orders')
                    ->preserveFilenames()
                    ->required(),

                Repeater::make('products')
                    ->label('Prekės')
                    ->schema([
                        Select::make('product_id')
                            ->label('Prekė')
                            ->options(Product::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required()
                            ->reactive(),
                        TextInput::make('quantity')
                            ->label('Kiekis')
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->required(),
                    ])
                    ->columns(2)
                    ->required()
                    ->afterStateUpdated(function (callable $set, $state) {
                        $total = OrderResource::calculateTotal($state);
                        $set('total_price', $total);
                    })
                    ->afterStateHydrated(function (callable $set, $state) {
                        $total = OrderResource::calculateTotal($state);
                        $set('total_price', $total);
                    })
                    ->default([]), // чтобы не было null


                TextInput::make('total_price')
                    ->label('Bendra kaina')
                    ->disabled()
                    ->dehydrated() // чтобы сохранялось в БД
                    ->numeric(),
            ])
            ->columns(1)
            ->statePath('data');
    }

    public static function calculateTotal(?array $products): float
    {
        if (empty($products)) {
            return 0;
        }

        $ids = collect($products)->pluck('product_id')->unique();
        $prices = Product::whereIn('id', $ids)->pluck('price', 'id');

        return collect($products)->reduce(function ($carry, $item) use ($prices) {
            $price = $prices[$item['product_id']] ?? 0;
            return $carry + ($price * ($item['quantity'] ?? 1));
        }, 0);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('car_number')->label('Mašinos numeris')->sortable(),
                Tables\Columns\TextColumn::make('total_price')->label('Bendra kaina')->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Statusas')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'awaiting_payment' => 'Laukia apmokėjimo',
                        'awaiting_shipment' => 'Laukia išsiuntimo',
                        'shipped' => 'Išsiųstas',
                        'completed' => 'Įvykdytas',
                        default => $state,
                    }),
                Tables\Columns\ImageColumn::make('image')->label('Vaizdas'),
                Tables\Columns\TextColumn::make('created_at')->label('Sukurta')->dateTime()->sortable(),
            ])
            ->bulkActions([
                ForceDeleteBulkAction::make(),
            ]);
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
