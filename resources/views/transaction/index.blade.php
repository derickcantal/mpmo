<x-app-layout>
<table class="min-w-full">
<thead><tr><th>Date</th><th>Type</th><th>TRX</th><th>Gross</th><th>Fee</th><th>Net</th></tr></thead>
<tbody>
@foreach($txs as $tx)
<tr><td>{{ $tx->created_at }}</td><td>{{ ucfirst($tx->type) }}</td><td>{{ $tx->trx_amount }}</td><td>{{ $tx->mpmo_gross }}</td><td>{{ $tx->mpmo_fee }}</td><td>{{ $tx->mpmo_net }}</td></tr>
@endforeach
</tbody>
</table>
<div>{{ $txs->links() }}</div>
</x-app-layout>