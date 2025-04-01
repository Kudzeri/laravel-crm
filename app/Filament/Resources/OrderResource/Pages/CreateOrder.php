<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function afterCreate(): void
    {
        $data = $this->form->getState();

        $products = $data['products'] ?? [];

        $syncData = [];

        foreach ($products as $product) {
            if (isset($product['product_id'], $product['quantity'])) {
                $syncData[$product['product_id']] = ['quantity' => $product['quantity']];
            }
        }

        $this->record->products()->sync($syncData);
    }
}
