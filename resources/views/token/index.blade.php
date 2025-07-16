{{-- resources/views/token/index.blade.php --}}
<x-app-layout>
    <div class="gradient-bg min-h-screen flex items-center justify-center">
        <div class="max-w-4xl w-full bg-white rounded-2xl shadow-lg p-8">

            <!-- Header -->
            <div class="text-center mb-8">
                <img src="{{ asset('storage/img/logo.png') }}" alt="MPMO Logo" class="mx-auto h-16 w-16 mb-2">
                <h1 class="text-3xl font-bold text-pink-500">MPMO Token Dashboard</h1>
                <p class="text-gray-600 mt-1">Manage your MPMO supply and convert between TRX ↔ MPMO</p>
                {{-- Live Current Price --}}
                <p class="mt-4 text-lg font-semibold text-gray-800 dark:text-gray-200">
                    Current Price: 
                    <span id="live_price" 
                          data-price="{{ $metric->price }}" 
                          class="text-pink-500">
                        {{ number_format($metric->price, 4) }}
                    </span> TRX
                </p>
            </div>

            <!-- Metrics -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach ([
                    ['label' => 'Symbol',       'value' => $metric->symbol],
                    ['label' => 'Max Supply',   'value' => number_format($metric->max_supply)],
                    ['label' => 'Total Supply', 'value' => number_format($metric->total_supply)],
                    ['label' => 'Circulating',  'value' => number_format($metric->circulating_supply)],
                    ['label' => 'Remaining',    'value' => number_format($metric->remaining_supply)],
                    ['label' => 'Treasury',     'value' => number_format($metric->treasury_balance)],
                    ['label' => 'Burned',       'value' => number_format($metric->burned_amount)],
                ] as $item)
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-4 text-center">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">{{ $item['label'] }}</dt>
                        <dd class="mt-1 text-xl font-semibold dark:text-gray-100">{{ $item['value'] }}</dd>
                    </div>
                @endforeach
            </div>

            <!-- Forms -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                <!-- Convert TRX → MPMO -->
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Convert TRX → MPMO</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        Balance: <span class="font-medium">{{ number_format($trxBalance, 4) }} TRX</span>
                    </p>
                    <form action="{{ route('token.convert') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="trx_amount" class="block text-gray-700 dark:text-gray-300 font-semibold">TRX Amount</label>
                            <input
                                id="trx_amount"
                                name="trx_amount"
                                type="number"
                                step="0.0001"
                                value="{{ old('trx_amount') }}"
                                required
                                class="mt-1 block w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-pink-400"
                            />
                            @error('trx_amount')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Live Conversion Preview -->
                        <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg space-y-2">
                            <p class="text-sm dark:text-gray-300">Gross MPMO: <span id="live_gross">0.00</span></p>
                            <p class="text-sm dark:text-gray-300">Fee (5%): <span id="live_fee">0.00</span></p>
                            <p class="text-sm font-semibold dark:text-gray-100">Net MPMO: <span id="live_net">0.00</span></p>
                        </div>

                        <button type="submit" class="w-full bg-pink-500 text-white py-3 rounded-full font-bold hover:bg-pink-600 transition">
                            Convert
                        </button>
                    </form>
                </div>

                <!-- Redeem MPMO → TRX -->
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Redeem MPMO → TRX</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        Balance: <span class="font-medium">{{ number_format($mpmoBalance, 4) }} MPMO</span>
                    </p>
                    <form action="{{ route('token.redeem') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="mpmo_amount" class="block text-gray-700 dark:text-gray-300 font-semibold">MPMO Amount</label>
                            <input
                                id="mpmo_amount"
                                name="mpmo_amount"
                                type="number"
                                step="0.0001"
                                value="{{ old('mpmo_amount') }}"
                                required
                                class="mt-1 block w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-pink-400"
                            />
                            @error('mpmo_amount')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Live Redemption Preview -->
                        <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg space-y-2">
                            <p class="text-sm dark:text-gray-300">Gross TRX: <span id="redeem_live_gross">0.00</span></p>
                            <p class="text-sm dark:text-gray-300">Fee (10%): <span id="redeem_live_fee">0.00</span></p>
                            <p class="text-sm font-semibold dark:text-gray-100">Net TRX: <span id="redeem_live_net">0.00</span></p>
                        </div>

                        <button type="submit" class="w-full bg-pink-500 text-white py-3 rounded-full font-bold hover:bg-pink-600 transition">
                            Redeem
                        </button>
                    </form>
                </div>
            </div>

            <!-- Price Trend Chart -->
            <div class="mt-12 bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Price Trend</h3>
                <canvas id="priceTrendChart" height="100"></canvas>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Live price polling
            const priceEl = document.getElementById('live_price');
            let currentPrice = parseFloat(priceEl.dataset.price);
            const fetchPrice = async () => {
                try {
                    const res = await fetch('{{ route('token.price') }}');
                    const json = await res.json();
                    currentPrice = parseFloat(json.price);
                    priceEl.textContent = currentPrice.toFixed(4);
                } catch (e) {
                    console.error('Price fetch failed', e);
                }
            };
            fetchPrice();
            setInterval(fetchPrice, 15000);

            // Conversion dynamic preview
            const trxInput = document.getElementById('trx_amount');
            const grossEl = document.getElementById('live_gross');
            const feeEl   = document.getElementById('live_fee');
            const netEl   = document.getElementById('live_net');
            const feeRate = 0.05;
            const updateLiveConvert = () => {
                const trx = parseFloat(trxInput.value) || 0;
                const gross = trx * (1 / currentPrice);
                const fee   = gross * feeRate;
                const net   = gross - fee;
                grossEl.textContent = gross.toFixed(4);
                feeEl.textContent   = fee.toFixed(4);
                netEl.textContent   = net.toFixed(4);
            };
            trxInput.addEventListener('input', updateLiveConvert);
            updateLiveConvert();

            // Redemption dynamic preview
            const mpmoInput     = document.getElementById('mpmo_amount');
            const redeemGrossEl = document.getElementById('redeem_live_gross');
            const redeemFeeEl   = document.getElementById('redeem_live_fee');
            const redeemNetEl   = document.getElementById('redeem_live_net');
            const redeemFeeRate = 0.10;
            const updateLiveRedeem = () => {
                const mpmo = parseFloat(mpmoInput.value) || 0;
                const grossTrx = mpmo * currentPrice;
                const feeMpmo  = mpmo * redeemFeeRate;
                const netMpmo  = mpmo - feeMpmo;
                const netTrx   = netMpmo * currentPrice;
                redeemGrossEl.textContent = grossTrx.toFixed(4);
                redeemFeeEl.textContent   = feeMpmo.toFixed(4);
                redeemNetEl.textContent   = netTrx.toFixed(4);
            };
            mpmoInput.addEventListener('input', updateLiveRedeem);
            updateLiveRedeem();

            // Price trend chart
            const ctx = document.getElementById('priceTrendChart').getContext('2d');
            const rawData = @json($priceHistory->map(fn($p) => ['time' => $p->created_at->format('Y-m-d H:i'), 'price' => $p->price]));
            const labels = rawData.map(d => d.time);
            const prices = rawData.map(d => d.price);
            new Chart(ctx, {
                type: 'line',
                data: { labels, datasets: [{ label: 'Price (TRX)', data: prices, borderWidth: 2, fill: false }] },
                options: {
                    responsive: true,
                    plugins: { legend: { display: true } },
                    scales: { x: { title: { display: true, text: 'Time' } }, y: { title: { display: true, text: 'Price (TRX)' }, beginAtZero: false } }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
