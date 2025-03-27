<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductImageResource\Pages;
use App\Models\ProductImage;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Form;
use Filament\Tables\Table;

class ProductImageResource extends Resource
{
    protected static ?string $model = ProductImage::class;

    protected static ?string $navigationGroup = 'Parduotuvė';
    protected static ?string $navigationLabel = 'Prekių nuotraukos';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('product_id')
                ->label('Prekė')
                ->relationship('product', 'name')
                ->required(),

            Forms\Components\FileUpload::make('image_url')
                ->label('Nuotraukos')
                ->image()
                ->multiple()
                ->maxFiles(10)
                ->directory('products')
                ->preserveFilenames()
                ->required(),
            TextInput::make('alt')
                ->label('ALT tekstas')
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            ImageColumn::make('image_url')
                ->label('Nuotrauka')
                ->getStateUsing(fn ($record) => asset($record->image_url)),
            TextColumn::make('product.name')->label('Prekė')->searchable(),
            TextColumn::make('alt')->label('ALT')->searchable(),
        ])
            ->bulkActions([
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductImages::route('/'),
            'create' => Pages\CreateProductImage::route('/create'),
            'edit' => Pages\EditProductImage::route('/{record}/edit'),
        ];
    }
}

