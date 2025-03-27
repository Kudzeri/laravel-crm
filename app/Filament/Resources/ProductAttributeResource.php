<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductAttributeResource\Pages;
use App\Filament\Resources\ProductAttributeResource\RelationManagers;
use App\Models\ProductAttribute;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductAttributeResource extends Resource
{
    protected static ?string $model = ProductAttribute::class;

    protected static ?string $navigationLabel = 'Atributai';
    protected static ?string $navigationGroup = 'Parduotuvė';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('product_id')
                ->relationship('product', 'name')
                ->label(__('Produktas'))
                ->required(),

            TextInput::make('name')->required()->label(__('Atributo pavadinimas')),
            TextInput::make('value')->required()->label(__('Reikšmė')),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('product.name')->label(__('Produktas')),
            TextColumn::make('name')->label(__('Atributo pavadinimas'))->searchable(),
            TextColumn::make('value')->label(__('Reikšmė'))->searchable(),
        ])->bulkActions([
            Tables\Actions\ForceDeleteBulkAction::make(),
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
            'index' => Pages\ListProductAttributes::route('/'),
            'create' => Pages\CreateProductAttribute::route('/create'),
            'edit' => Pages\EditProductAttribute::route('/{record}/edit'),
        ];
    }
}
