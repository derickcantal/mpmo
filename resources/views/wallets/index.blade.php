<x-app-layout>
    <x-slot name="header">My TRON Wallets</x-slot>

    <div class="p-6">
        <form action="{{ route('wallet.create') }}" method="POST">
            @csrf
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Create New Wallet</button>
        </form>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
            @foreach($wallets as $wallet)
            <div class="p-4 bg-white rounded-lg shadow">
                <h3 class="font-semibold mb-2">{{ $wallet->address }}</h3>
                <p>Balance: {{ $wallet->balance }} TRX</p>
                <a href="{{ route('wallet.send', $wallet->address) }}" class="text-blue-600 hover:underline mt-2 inline-block">Send TRX</a>
            </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
