<x-app-layout>
    @include('layouts.home.navigation')
    <div class="gradient-bg min-h-screen dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg py-2 flex-1 pb-16 sm:pb-safe">
        <div class="mx-auto sm:px-6 lg:px-8">
                <form action="{{ route('managetxn.storeconvert') }}" method="POST">
                    @csrf   
                    <!-- Breadcrumb -->
                    <nav class="flex px-5 py-3 text-gray-700 bg-transparent dark:bg-gray-800 dark:border-gray-700" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                            <li class="inline-flex items-center">
                            <a href="{{ route('managetxn.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                                <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                                </svg>
                                Transactions
                            </a>
                            </li>
                            
                            <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="rtl:rotate-180  w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                                <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">
                                    Convert</span>
                            </div>
                            </li>
                        </ol>
                    </nav>
                    <!-- Error & Success Notification -->
                    @include('layouts.notifications') 

                    <div class="bg-transparent min-h-screen py-10">
                        <div class="max-w-md mx-auto bg-white/80 backdrop-blur-md rounded-2xl shadow-lg p-8">
                            <h1 class="text-3xl font-bold text-gray-800 mb-4">Convert TRX to MPMO</h1>
                            <p class="text-gray-600 mb-6">Rate: <span class="font-semibold text-pink-500">1 TRX = 3 MPMO</span></p>

                            <!-- Balance Display from Database -->
                            <div class="grid grid-cols-2 gap-4 text-center mb-6">
                                <div>
                                    <p class="text-gray-600">TRX Balance</p>
                                    <p id="trxBalance" class="text-xl font-bold text-purple-500">{{ number_format($trxBalance, 2) }} TRX</p>
                                </div>
                                <div>
                                    <p class="text-gray-600">Estimated MPMO</p>
                                    <p id="mpmoEstimate" class="text-xl font-bold text-yellow-500">0 MPMO</p>
                                </div>
                            </div>

                            <!-- Fee & Net Display -->
                            <div class="mb-6 space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Conversion Fee (<span id="feeRateLabel">2-5%</span>)</span>
                                    <span name= "feeEstimate" id="feeEstimate" class="font-semibold text-red-500">0 MPMO</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Net MPMO</span>
                                    <span name="netEstimate" id="netEstimate" class="font-semibold text-green-600">0 MPMO</span>
                                </div>
                            </div>

                            <!-- Conversion Form -->
                            <form id="convertForm">
                                <div class="mb-2">
                                    <label for="trx_amount" class="block text-gray-700 font-semibold mb-2">TRX Amount</label>
                                    <input type="number" step="0.01" name="trx_amount" id="trx_amount" class="w-full px-4 py-2 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-pink-400" placeholder="0.00" required />
                                    <p id="inputError" class="text-red-500 text-sm mt-2 hidden">Insufficient TRX balance.</p>
                                </div>
                                <button id="convertBtn" type="submit" disabled class="w-full px-6 py-3 bg-purple-300 text-white font-semibold rounded-full shadow cursor-not-allowed transition">
                                    Convert
                                </button>
                            </form>
                        </div>
                    </div>

                    @push('scripts')
                        <script>
                            const conversionRate = 3;
                            const feeRate = 0.02; // 2% fee
                            const accountBalance = {{ $trxBalance ?? 0 }};

                            document.addEventListener('DOMContentLoaded', () => {
                                const amountInput = document.getElementById('trx_amount');
                                const estimateDisplay = document.getElementById('mpmoEstimate');
                                const feeEstimate = document.getElementById('feeEstimate');
                                const netEstimate = document.getElementById('netEstimate');
                                document.getElementById('feeRateLabel').innerText = (feeRate * 100) + '%';

                                const inputError = document.getElementById('inputError');
                                const convertBtn = document.getElementById('convertBtn');

                                estimateDisplay.innerText = '0 MPMO';
                                feeEstimate.innerText = '0 MPMO';
                                netEstimate.innerText = '0 MPMO';

                                amountInput.addEventListener('input', e => {
                                    const val = parseFloat(e.target.value) || 0;
                                    const gross = val * conversionRate;
                                    const fee = gross * feeRate;
                                    const net = gross - fee;

                                    estimateDisplay.innerText = gross.toFixed(2) + ' MPMO';
                                    feeEstimate.innerText = fee.toFixed(2) + ' MPMO';
                                    netEstimate.innerText = net.toFixed(2) + ' MPMO';

                                    if (val > accountBalance) {
                                        inputError.classList.remove('hidden');
                                        convertBtn.disabled = true;
                                        convertBtn.classList.add('cursor-not-allowed', 'bg-purple-300');
                                    } else {
                                        inputError.classList.add('hidden');
                                        convertBtn.disabled = false;
                                        convertBtn.classList.remove('cursor-not-allowed', 'bg-purple-300');
                                        convertBtn.classList.add('bg-purple-500', 'hover:bg-purple-600');
                                    }
                                });

                                document.getElementById('convertForm').addEventListener('submit', e => {
                                    e.preventDefault();
                                    const amount = parseFloat(amountInput.value);
                                    if (amount > accountBalance) return;
                                    fetch("{{ route('managetxn.storeconvert') }}", {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        },
                                        body: JSON.stringify({ trx_amount: amount })
                                    })
                                    .then(res => res.json())
                                    .then(data => alert(data.message))
                                    .catch(err => console.error(err));
                                });
                            });
                        </script>
                    @endpush
                    
                    
                </form>
            </div>
        </div>
</x-app-layout>
