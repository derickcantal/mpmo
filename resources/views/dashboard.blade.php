<x-app-layout>
    @include('layouts.home.navigation')

	<div class="mx-auto sm:px-6 lg:px-8 py-8">
		<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg py-8">
            <div class="mx-auto sm:px-6 lg:px-8">
                <!-- Breadcrumb -->
                <nav class="flex px-5 py-3 text-gray-700 bg-white dark:bg-gray-800 dark:border-gray-700" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                        <li class="inline-flex items-center">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                            <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                            </svg>
                            Home
                        </a>
                        </li>
                    </ol>
                </nav>
                <!-- Error & Success Notification -->
                @include('layouts.notifications') 
                {{-- Content --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Card 1 -->
                    <div class="bg-white rounded-lg shadow p-5">
                        <header class="text-center mb-10">
                            <img src="{{ asset("storage/img/logo.png") }}" alt="MPMO Logo" class="mx-auto w-32 h-32 mb-4">
                            <h1 class="text-4xl font-bold text-orange-600">MPMO Token</h1>
                        </header>
                        <div class="flex flex-col items-center justify-center">
                            <dt class="mb-2 text-3xl font-extrabold">{{ number_format(auth()->user()->mpmobal, 2) }}</dt>
                            <dd class="text-gray-500 dark:text-gray-400">$MPMO Balance</dd>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="bg-white rounded-lg shadow p-5">
                        <h2 class="text-2xl font-bold mb-4 text-center text-orange-500">Your Wallet</h2>
                        <div class="space-y-4">
                            <button onclick="connectWallet()" class="w-full bg-orange-500 text-white py-3 rounded-xl hover:bg-orange-600 transition">Connect Wallet</button>

                            <div class="mt-4">
                                <p class="text-gray-700">Wallet Address:</p>
                                <p id="walletAddress" class="font-mono text-gray-900 break-all">Not connected</p>
                            </div>

                            <div class="mt-4">
                                <p class="text-gray-700">BNB Balance:</p>
                                <p id="bnbBalance" class="font-mono text-gray-900">-</p>
                            </div>

                            <div class="mt-4">
                                <p class="text-gray-700">MPMO Token Balance:</p>
                                <p id="mpmoBalance" class="font-mono text-gray-900">-</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3 with Chart -->
                    <div class="bg-white rounded-lg shadow p-5">
                    <h5 class="text-xl font-bold mb-4">Chart Example</h5>
                    <canvas id="myChart" height="150"></canvas>
                    </div>
                </div>
                
                <div class="w-full bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                    <ul class="text-sm font-medium text-center text-gray-500 divide-x divide-gray-200 rounded-lg sm:flex dark:divide-gray-600 dark:text-gray-400 rtl:divide-x-reverse" id="fullWidthTab" data-tabs-toggle="#fullWidthTabContent" role="tablist">
                        <li class="w-full">
                            <button id="stats-tab" data-tabs-target="#stats" type="button" role="tab" aria-controls="stats" aria-selected="true" class="inline-block w-full p-4 rounded-ss-lg bg-gray-50 hover:bg-gray-100 focus:outline-none dark:bg-gray-700 dark:hover:bg-gray-600">Statistics</button>
                        </li>
                    </ul>
                    <div class=" p-4 bg-white rounded-lg md:p-8 dark:bg-gray-800" >
                        <dl class="grid max-w-screen-xl grid-cols-2 gap-8 p-4 mx-auto text-gray-900 sm:grid-cols-3 xl:grid-cols-6 dark:text-white sm:p-8">
                            <div class="flex flex-col items-center justify-center">
                                <dt class="mb-2 text-3xl font-extrabold">{{ number_format(auth()->user()->mpmobal, 2) }}</dt>
                                <dd class="text-gray-500 dark:text-gray-400">$MPMO Balance</dd>
                            </div>
                            <div class="flex flex-col items-center justify-center">
                                <dt class="mb-2 text-3xl font-extrabold">{{ number_format(auth()->user()->trxbal, 2) }}</dt>
                                <dd class="text-gray-500 dark:text-gray-400">$TRX Balance</dd>
                            </div>
                            <div class="flex flex-col items-center justify-center">
                                <dt class="mb-2 text-3xl font-extrabold">{{ number_format(auth()->user()->usdtbal, 2) }}</dt>
                                <dd class="text-gray-500 dark:text-gray-400">$USDT Balance</dd>
                            </div>
                            <div class="flex flex-col items-center justify-center">
                                <dt class="mb-2 text-3xl font-extrabold">0</dt>
                                <dd class="text-gray-500 dark:text-gray-400">Pets</dd>
                            </div>
                            <div class="flex flex-col items-center justify-center">
                                <dt class="mb-2 text-3xl font-extrabold">0</dt>
                                <dd class="text-gray-500 dark:text-gray-400">Daily Income</dd>
                            </div>
                            <div class="flex flex-col items-center justify-center">
                                <dt class="mb-2 text-3xl font-extrabold">0</dt>
                                <dd class="text-gray-500 dark:text-gray-400">Available Balance</dd>
                            </div>
                        </dl>
                    </div>
                </div>
                
             
            </div>
        </div>
    </div>

      
</x-app-layout>
