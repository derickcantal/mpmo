<x-app-layout>
    @include('layouts.home.navigation')
    <div class="gradient-bg min-h-screen dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg py-2 flex-1 pb-16 sm:pb-safe">
        <div class="mx-auto sm:px-6 lg:px-8">
            <x-slot name="header">Transaction History</x-slot>
            <div class="p-6">
                @foreach($wallets as $wallet)
                    <h2 class="font-semibold mt-6">{{ $wallet->address }}</h2>
                    <table class="table-auto w-full text-left mt-2 border-collapse border border-gray-200">
                        <thead>
                            <tr>
                                <th class="border border-gray-300 px-2 py-1">To Address</th>
                                <th class="border border-gray-300 px-2 py-1">Amount (TRX)</th>
                                <th class="border border-gray-300 px-2 py-1">Transaction Hash</th>
                                <th class="border border-gray-300 px-2 py-1">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($wallet->transactions as $tx)
                                <tr>
                                    <td class="border border-gray-300 px-2 py-1">{{ $tx->to_address }}</td>
                                    <td class="border border-gray-300 px-2 py-1">{{ $tx->amount }}</td>
                                    <td class="border border-gray-300 px-2 py-1">{{ $tx->tx_hash ?? '-' }}</td>
                                    <td class="border border-gray-300 px-2 py-1">{{ $tx->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
