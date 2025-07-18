<x-app-layout>
    @include('layouts.home.navigation')
    <div class="gradient-bg min-h-screen dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg py-2 flex-1 pb-16 sm:pb-safe">
        <div class="mx-auto sm:px-6 lg:px-8 ">
            <form action="{{ route('managetxn.storedeposit') }}" enctype="multipart/form-data" method="POST">
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
                                Deposit</span>
                        </div>
                        </li>
                    </ol>
                </nav>
                <!-- Error & Success Notification -->
                @include('layouts.notifications') 
                <!--  content -->
                <div class="bg-transparent min-h-screen py-10">
                    <div class="max-w-md mx-auto bg-white/80 backdrop-blur-md rounded-2xl shadow-lg p-8">
                        <h1 class="text-3xl font-bold text-gray-800 mb-4">Deposit Information</h1>
                            <!-- TXN HASH -->
                            <div>
                                <label for="txnhash" class="block text-gray-700 font-semibold mb-1">TXN HASH</label>
                                <input
                                    id="txnhash"
                                    name="txnhash"
                                    type="text"
                                    value="{{ old('txnhash') }}"
                                    required
                                    class="w-full px-4 py-2 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-pink-400"
                                />
                                <x-input-error :messages="$errors->get('txnhash')" class="mt-1 text-red-500 text-sm"/>
                            </div>

                            <!-- Token -->
                            <div>
                                <label for="tokenname" class="block text-gray-700 font-semibold mb-1">Token</label>
                                <select
                                    id="tokenname"
                                    name="tokenname"
                                    class="w-full px-4 py-2 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-pink-400"
                                >
                                    <option value="TRX" {{ old('tokenname')=='TRX' ? 'selected' : '' }}>TRX</option>
                                </select>
                                <x-input-error :messages="$errors->get('tokenname')" class="mt-1 text-red-500 text-sm"/>
                            </div>

                            <!-- Amount -->
                            <div>
                                <label for="amount" class="block text-gray-700 font-semibold mb-1">Amount</label>
                                <input
                                    id="amount"
                                    name="amount"
                                    type="number"
                                    step="0.01"
                                    value="{{ old('amount') }}"
                                    required
                                    class="w-full px-4 py-2 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-pink-400"
                                />
                                <x-input-error :messages="$errors->get('amount')" class="mt-1 text-red-500 text-sm"/>
                            </div>

                            <!-- Wallet Address (Source) -->
                            <div>
                                <label for="walletsource" class="block text-gray-700 font-semibold mb-1">Wallet Address (Source)</label>
                                <input
                                    id="walletsource"
                                    name="walletsource"
                                    type="text"
                                    value="{{ old('walletsource') }}"
                                    required
                                    class="w-full px-4 py-2 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-pink-400"
                                />
                                <x-input-error :messages="$errors->get('walletsource')" class="mt-1 text-red-500 text-sm"/>
                            </div>

                            <!-- Screenshot Proof -->
                            <div>
                                <label for="imgproof" class="block text-gray-700 font-semibold mb-1">Upload Screenshot</label>
                                <input
                                    id="imgproof"
                                    name="imgproof"
                                    type="file"
                                    required
                                    class="w-full px-4 py-2 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-pink-400"
                                />
                                <x-input-error :messages="$errors->get('imgproof')" class="mt-1 text-red-500 text-sm"/>
                            </div>

                            <!-- Actions -->
                            <div class="space-y-3">
                                <button
                                    type="submit"
                                    class="w-full px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-full shadow transition"
                                >
                                    Deposit
                                </button>
                                <a
                                    href="{{ route('managetxn.index') }}"
                                    class="w-full block text-center px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-full shadow transition"
                                >
                                    Cancel
                                </a>
                            </div>
                    </div>
                </div>

                
            </form>
        </div>
    </div>
</x-app-layout>
