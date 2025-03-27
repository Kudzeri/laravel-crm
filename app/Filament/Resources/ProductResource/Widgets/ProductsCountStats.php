<?php

namespace App\Filament\Resources\ProductResource\Widgets;

use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class ProductsCountStats extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Visos prekės', Product::count()),
        ];
    }
}
