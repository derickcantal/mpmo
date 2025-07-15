<x-app-layout>
    @include('layouts.home.navigation')
    <div class="gradient-bg min-h-screen dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-4 py-5 flex-1 pb-16 sm:pb-safe">
        <div class="mx-auto bg-white rounded-lg shadow-sm sm:px-6 lg:px-8 p-4">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">MPMO Token Supply Information</h1>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Total Supply -->
                <div class="bg-white rounded-2xl shadow p-6 text-center">
                    <p class="text-gray-600 mb-2">Total Supply</p>
                    <p class="text-2xl font-bold text-purple-500">{{ number_format($token->total_supply, 0) }} MPMO</p>
                </div>

                <!-- Circulating Supply -->
                <div class="bg-white rounded-2xl shadow p-6 text-center">
                    <p class="text-gray-600 mb-2">Circulating Supply</p>
                    <p class="text-2xl font-bold text-pink-500">{{ number_format($token->circulating_supply, 0) }} MPMO</p>
                </div>

                <!-- Burned Supply -->
                <div class="bg-white rounded-2xl shadow p-6 text-center">
                    <p class="text-gray-600 mb-2">Burned Supply</p>
                    <p class="text-2xl font-bold text-yellow-500">{{ number_format($token->burned_supply, 0) }} MPMO</p>
                </div>
            </div>

            <!-- Additional Details -->
            <div class="mt-8 space-y-2 text-center text-gray-700">
                <p><strong>Last Updated:</strong> {{ $token->updated_at->format('M d, Y H:i') }}</p>
                <p><strong>Max Supply:</strong> {{ number_format($token->max_supply, 0) }} MPMO</p>
            </div>
        </div>
    </div>
</x-app-layout>