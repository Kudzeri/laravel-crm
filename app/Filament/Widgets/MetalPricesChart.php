<?php

namespace App\Filament\Widgets;

use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use App\Services\MetalsApiService;
use Illuminate\Support\Carbon;

class MetalPricesChart extends ApexChartWidget
{
    protected static ?string $heading = 'Metals-API metalo kainos';
    protected static ?string $chartId = 'metaluKainuGrafikas';
    protected int | string | array $columnSpan = 'full';

    // 🔁 ВКЛ/ВЫКЛ заглушки вручную здесь:
    protected bool $useFakeData = true;

    protected function getOptions(): array
    {
        if ($this->useFakeData) {
            return $this->getFakeChartData();
        }

        $rates = app(MetalsApiService::class)->getWeeklyRates();

        if (empty($rates)) {
            return $this->getFakeChartData();
        }

        $dates = array_keys($rates);

        return [
            'chart' => [
                'type' => 'area',
                'height' => 350,
                'toolbar' => ['show' => false],
            ],
            'xaxis' => [
                'categories' => $dates,
            ],
            'series' => [
                [
                    'name' => 'Platina',
                    'data' => array_map(fn($d) => $rates[$d]['XPT'] ?? null, $dates),
                ],
                [
                    'name' => 'Paladis',
                    'data' => array_map(fn($d) => $rates[$d]['XPD'] ?? null, $dates),
                ],
            ],
        ];
    }

    private function getFakeChartData(): array
    {
        $dates = $this->getLast7Dates();

        return [
            'chart' => [
                'type' => 'area',
                'height' => 350,
                'toolbar' => ['show' => false],
            ],
            'xaxis' => [
                'categories' => $dates,
            ],
            'series' => [
                [
                    'name' => 'Platina',
                    'data' => [965, 967, 968, 970, 972, 973, 972],
                ],
                [
                    'name' => 'Paladis',
                    'data' => [930, 931, 934, 936, 938, 940, 938],
                ],
            ],
        ];
    }

    private function getLast7Dates(): array
    {
        return collect(range(0, 6))
            ->map(fn($i) => Carbon::now()->subDays(6 - $i)->format('Y-m-d'))
            ->toArray();
    }
}


