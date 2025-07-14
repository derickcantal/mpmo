<x-app-layout>
    @include('layouts.home.navigation')
    <div class="gradient-bg min-h-screen dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg py-2 flex-1 pb-16 sm:pb-safe">
        <div class="mx-auto sm:px-6 lg:px-8">
            <table class="min-w-full">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>TRX</th>
                        <th>Gross</th>
                        <th>Fee</th>
                        <th>Net</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($txs as $tx)
                    <tr>
                        <td>{{ $tx->created_at }}</td>
                        <td>{{ ucfirst($tx->type) }}</td>
                        <td>{{ $tx->trx_amount }}</td>
                        <td>{{ $tx->mpmo_gross }}</td>
                        <td>{{ $tx->mpmo_fee }}</td>
                        <td>{{ $tx->mpmo_net }}</td></tr>
                    @endforeach
                </tbody>
            </table>
            <div>{{ $txs->links() }}</div>
        </div>
    </div>
</x-app-layout>