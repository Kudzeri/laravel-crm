<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class CurrencyConverter
{
    public function convert(float $amount, string $from = 'EUR', string $to = 'USD'): float
    {
        $rate = $this->getRate($from, $to);
        return round($amount * $rate, 2);
    }

    public function getRate(string $from, string $to): float
    {
        // кешируем каждый курс отдельно на 6 часов
        return Cache::remember("exchange_rate_{$from}_{$to}", now()->addHours(6), function () use ($from, $to) {
            $response = Http::get("https://open.er-api.com/v6/latest/{$from}");

            if ($response->successful()) {
                return $response->json("rates.{$to}", 1.0);
            }

            return 1.0; // fallback rate
        });
    }
}


