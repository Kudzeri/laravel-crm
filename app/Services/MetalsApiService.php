<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Carbon;

class MetalsApiService
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = rtrim(env('METALS_API_URL', 'https://metals-api.com/api/'), '/') . '/';
        $this->apiKey = env('METALS_API_KEY');
    }

    public function getWeeklyRates(): array
    {
        $endDate = Carbon::now()->format('Y-m-d');
        $startDate = Carbon::now()->subDays(6)->format('Y-m-d');

        $response = Http::get($this->baseUrl . 'timeseries', [
            'access_key' => $this->apiKey,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'base' => 'USD',
            'symbols' => 'XPT,XPD',
        ]);

        if (!$response->successful()) {
            return [];
        }

        return $response->json()['rates'] ?? [];
    }
}


