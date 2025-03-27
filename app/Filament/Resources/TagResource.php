<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TagResource\Pages;
use App\Filament\Resources\TagResource\RelationManagers;
use App\Models\Tag;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TagResource extends Resource
{
    protected static ?string $model = Tag::class;

    protected static ?string $navigationLabel = 'Žymos';
    protected static ?string $navigationGroup = 'Parduotuvė';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')->required()->label(__('Pavadinimas')),
            TextInput::make('slug')->required()->label(__('Nuoroda (slug)')),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->label(__('Pavadinimas'))->searchable(),
            TextColumn::make('slug')->label(__('Nuoroda (slug)'))->searchable(),
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
            'index' => Pages\ListTags::route('/'),
            'create' => Pages\CreateTag::route('/create'),
            'edit' => Pages\EditTag::route('/{record}/edit'),
        ];
    }
}
