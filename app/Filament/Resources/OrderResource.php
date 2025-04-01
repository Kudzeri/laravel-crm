<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

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
                Forms\Components\TextInput::make('car_number')->label('Mašinos numeris')->required(),
                Forms\Components\TextInput::make('total_price')->label('Bendra kaina')->numeric()->required(),
                Forms\Components\TextInput::make('status')->label('Statusas')->required(),
                Forms\Components\FileUpload::make('image')->label('Vaizdas')->image(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('car_number')->label('Mašinos numeris')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('total_price')->label('Bendra kaina')->sortable(),
                Forms\Components\Select::make('status')
                    ->label('Statusas')
                    ->required()
                    ->options([
                        'awaiting_payment' => 'Laukia apmokėjimo',
                        'awaiting_shipment' => 'Laukia išsiuntimo',
                        'shipped' => 'Išsiųstas',
                        'completed' => 'Įvykdytas',
                    ])
            ->sortable(),
                Tables\Columns\ImageColumn::make('image')->label('Vaizdas'),
                Tables\Columns\TextColumn::make('created_at')->label('Sukūrimo data')->dateTime()->sortable(),
            ])
            ->filters([]);
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
