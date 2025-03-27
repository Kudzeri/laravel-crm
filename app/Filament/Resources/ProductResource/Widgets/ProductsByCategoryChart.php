<?php

namespace App\Filament\Resources\ProductResource\Widgets;

use App\Models\Category;
use Filament\Widgets\ChartWidget;

class ProductsByCategoryChart extends ChartWidget
{
    protected static ?string $heading = 'Prekės pagal kategoriją';

    protected function getData(): array
    {
        $categories = Category::withCount('products')->get();

        return [
            'datasets' => [
                [
                    'label' => 'Prekė',
                    'data' => $categories->pluck('products_count'),
                    'backgroundColor' => [
                        '#f87171', '#60a5fa', '#34d399', '#fbbf24', '#a78bfa',
                    ],
                ],
            ],
            'labels' => $categories->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
