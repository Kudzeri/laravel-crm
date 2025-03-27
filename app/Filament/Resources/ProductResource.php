<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use App\Services\CurrencyConverter;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationLabel = 'Produktai';
    protected static ?string $navigationGroup = 'Parduotuvė';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')->required()->label(__('Pavadinimas')),
            TextInput::make('slug')->required(),
            TextInput::make('sku')->label(__('SKU')),
            TextInput::make('price')->required()->label(__('Kaina (€)')),
            Toggle::make('on_sale')->label(__('Akcija')),
            Textarea::make('short_description')->label(__('Trumpas aprašymas')),
            RichEditor::make('description')->label(__('Pilnas aprašymas')),
            Select::make('categories')->multiple()
                ->relationship('categories', 'name')
                ->label(__('Kategorijos')),
            Select::make('tags')->multiple()
                ->relationship('tags', 'name')
                ->label(__('Žymos')),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label(__('Pavadinimas'))->searchable(),
                TextColumn::make('price')
                    ->label(__('Kaina €')),
                TextColumn::make('price_usd')
                    ->label(__('Kaina $'))
                    ->getStateUsing(function ($record) {
                        return (new CurrencyConverter())->convert($record->price, 'EUR', 'USD') . ' USD';
                    }),
                ToggleColumn::make('on_sale')->label(__('Akcija')),
            ])
            ->bulkActions([
                ForceDeleteBulkAction::make(),
                BulkAction::make('export_csv')
                    ->label('Eksportuoti CSV')
                    ->action(function ($records) {
                        $filename = 'eksportas.csv';
                        $csv = fopen(storage_path("app/public/{$filename}"), 'w');

                        // Заголовки
                        fputcsv($csv, ['Prekė', 'Nuotrauka', 'ALT']);

                        foreach ($records as $record) {
                            fputcsv($csv, [
                                optional($record->product)->name,
                                $record->image_url,
                                $record->alt,
                            ]);
                        }

                        fclose($csv);

                        return response()->download(storage_path("app/public/{$filename}"))->deleteFileAfterSend();
                    })
                    ->requiresConfirmation()
                    ->icon('heroicon-o-arrow-down-tray')
            ])
            ->filters([
                // можно добавить фильтры по категории, цене и т.д.
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
