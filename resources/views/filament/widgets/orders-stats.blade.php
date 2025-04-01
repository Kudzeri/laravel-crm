<x-filament::widget>
    <x-filament::card>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-center">
            <div class="bg-gray-100 p-4 rounded-xl">
                <h2 class="text-lg font-semibold">📦 Užsakymai per savaitę</h2>
                <p class="text-2xl">{{ $weeklyOrders }}</p>
            </div>
            <div class="p-4 rounded-xl">
                <h2 class="text-lg font-semibold">📦 Užsakymai per mėnesį</h2>
                <p class="text-2xl">{{ $monthlyOrders }}</p>
            </div>
            <div class="p-4 rounded-xl">
                <h2 class="text-lg font-semibold">💰 Pajamos per savaitę</h2>
                <p class="text-2xl">€{{ number_format($weeklyRevenue, 2) }}</p>
            </div>
            <div class="bg-green-100 p-4 rounded-xl">
                <h2 class="text-lg font-semibold">💰 Pajamos per mėnesį</h2>
                <p class="text-2xl">€{{ number_format($monthlyRevenue, 2) }}</p>
            </div>
        </div>
    </x-filament::card>
</x-filament::widget>
