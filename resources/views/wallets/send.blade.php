<x-app-layout>
    <x-slot name="header">Send TRX</x-slot>

    <div class="p-6 max-w-md mx-auto">
        @if ($errors->has('send_error'))
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">{{ $errors->first('send_error') }}</div>
        @endif

        <form action="{{ route('wallet.send.post') }}" method="POST">
            @csrf
            <input type="hidden" name="address" value="{{ $address }}">

            <label class="block mb-1">Recipient Address</label>
            <input name="to_address" type="text" class="border rounded w-full p-2 mb-4" required>

            <label class="block mb-1">Amount (TRX)</label>
            <input name="amount" type="number" step="0.000001" class="border rounded w-full p-2 mb-4" required>

            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Send TRX</button>
        </form>
    </div>
</x-app-layout>
