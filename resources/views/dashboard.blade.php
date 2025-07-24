<x-app-layout>
    <div class="flex h-screen bg-gray-900 text-gray-200">
        <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-10 hidden md:hidden"></div>
        {{-- Main Content --}}
        <main class="flex-1 overflow-y-auto p-6 ml-0 md:ml-64">

            {{-- Header --}}
            <header class="flex items-center justify-between mb-8">
                <h1 class="text-4xl font-extrabold text-indigo-300">Welcome, {{ Auth::user()->fullname }}!</h1>
                <div class="flex items-center space-x-4">
                    <span class="px-3 py-1 bg-gray-800 rounded-full text-sm">{{ Auth::user()->accesstype }}</span>
                    <img src="{{ asset('storage/' . (auth()->user()->avatar ?? 'img/avatar-default.png')) }}"
                         alt="Avatar"
                         class="w-10 h-10 rounded-full border-2 border-indigo-400" />
                </div>
            </header>

            {{-- Metrics Cards --}}
            <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                @foreach ([
                    ['label'=>'MPMO Balance','value'=>number_format(Auth::user()->mpmo_balance,2),'icon'=>'heroicon-o-currency-dollar','color'=>'text-yellow-400'],
                    ['label'=>'TRX Balance','value'=>number_format(Auth::user()->trx_balance,2),'icon'=>'heroicon-o-currency-dollar','color'=>'text-pink-400'],
                    ['label'=>'USDT Balance','value'=>'0.00','icon'=>'heroicon-o-currency-dollar','color'=>'text-green-400'],
                    ['label'=>'Pets','value'=>'0','icon'=>'heroicon-o-sparkles','color'=>'text-purple-400'],
                ] as $card)
                    <div class="bg-gray-800 rounded-2xl shadow-xl p-6 border-2 border-transparent hover:border-indigo-500 transition">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-xl font-semibold">{{ $card['label'] }}</span>
                            <x-dynamic-component :component="$card['icon']" class="w-6 h-6 {{ $card['color'] }}" />
                        </div>
                        <div class="text-3xl font-bold {{ $card['color'] }}">{{ $card['value'] }}</div>
                    </div>
                @endforeach
            </section>

            {{-- Quick Actions --}}
            <section class="mb-10">
                <h2 class="text-2xl font-bold text-indigo-300 mb-4">Quick Actions</h2>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-6">
                    @foreach ([
                        ['route'=>'managetxn.deposit','icon'=>'heroicon-o-qr-code','label'=>'Deposit'],
                        ['route'=>'managetxn.deposit','icon'=>'heroicon-o-arrow-up-tray','label'=>'Send Money'],
                        ['route'=>'managetxn.deposit','icon'=>'heroicon-o-receipt-percent','label'=>'Pay Bills'],
                        ['route'=>'managetxn.deposit','icon'=>'heroicon-o-phone','label'=>'Buy Load'],
                    ] as $action)
                        <a href="{{ route($action['route']) }}"
                           class="flex flex-col items-center p-4 bg-gray-800 rounded-lg border-2 border-transparent hover:border-pink-500 transition">
                            <x-dynamic-component :component="$action['icon']" class="w-8 h-8 text-indigo-400 mb-2" />
                            <span class="font-medium">{{ $action['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </section>

            {{-- Recent Activity --}}
            <section>
                <h2 class="text-2xl font-bold text-indigo-300 mb-4">Recent Transactions</h2>
                <div class="overflow-x-auto bg-gray-800 rounded-lg shadow-xl">
                    <table class="min-w-full text-left divide-y divide-gray-700">
                        <thead class="bg-gray-900">
                            <tr>
                                <th class="px-4 py-2">Date</th>
                                <th class="px-4 py-2">Type</th>
                                <th class="px-4 py-2">Amount</th>
                                <th class="px-4 py-2">Status</th>
                                <th class="px-4 py-2">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @foreach($recentTxns as $txn)
                                <tr class="hover:bg-gray-700 transition">
                                    <td class="px-4 py-3">{{ $txn->created_at->format('M d, Y') }}</td>
                                    <td class="px-4 py-3">{{ ucfirst($txn->type) }}</td>
                                    <td class="px-4 py-3">{{ number_format($txn->amount,2) }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 rounded-full text-sm 
                                            {{ $txn->status=='success' ? 'bg-green-600' : 'bg-yellow-600' }}">
                                            {{ ucfirst($txn->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <a href="{{ route('admin.transactions.show',$txn) }}"
                                           class="text-indigo-400 hover:underline">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

        </main>
    </div>
</x-app-layout>
