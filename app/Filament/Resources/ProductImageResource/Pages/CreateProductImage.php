<?php

namespace App\Filament\Resources\ProductImageResource\Pages;

use App\Filament\Resources\ProductImageResource;
use App\Models\ProductImage;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CreateProductImage extends CreateRecord
{
    protected static string $resource = ProductImageResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $images = $data['image_url'];
        unset($data['image_url']);

        foreach ($images as $imagePath) {
            ProductImage::create([
                'product_id' => $data['product_id'],
                'image_url' => Storage::url($imagePath),
                'thumbnail' => Storage::url($imagePath), // или null
                'alt' => $data['alt'] ?? null,
            ]);
        }

        Notification::make()
            ->title('Nuotraukos įkeltos')
            ->success()
            ->send();

        return ProductImage::latest()->first(); // просто вернуть хоть одну для Filament
    }
}

