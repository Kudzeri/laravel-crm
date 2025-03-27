<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationLabel = 'Administracija';
    protected static ?string $navigationGroup = 'Nustatymai';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')->required()->label(__('Vardas')),
            TextInput::make('email')->email()->required()->label(__('El. paštas')),
            TextInput::make('password')
                ->password()
                ->dehydrateStateUsing(fn ($state) => !empty($state) ? Hash::make($state) : null)
                ->label(__('Slaptažodis'))
                ->required(fn (Page $livewire) => $livewire instanceof CreateRecord)
                ->label(__('Slaptažodis')),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->label(__('Vardas'))->searchable(),
            TextColumn::make('email')->label(__('El. paštas'))->searchable(),
            TextColumn::make('created_at')->dateTime()->label(__('Sukurta')),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
