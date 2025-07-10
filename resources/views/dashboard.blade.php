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
                            Wallet
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
                            <h5 class="text-2xl font-bold text-orange-600">Account Information</h5>
                        </header>
                        @php
                            $qrdep = auth()->user()->qrcwaddress;
                        @endphp
                        <div class="flex flex-col items-center justify-center">
                            <img class="mx-auto w-32 h-32 mb-4" src="{{ asset("/storage/$qrdep") }}" alt="QR" />
                            <dd class="text-gray-500 dark:text-gray-400">Deposit Address:</dd>
                            <dt class="mb-2 text-1xl font-extrabold">{{ auth()->user()->cwaddress }}</dt>
                        </div>
                    </div>
                    <!-- Card 1 -->
                    <div class="bg-white rounded-lg shadow p-5">
                        <header class="text-center mb-10">
                            <img src="{{ asset("storage/img/logo.png") }}" alt="MPMO Logo" class="mx-auto w-32 h-32 mb-4">
                            <h1 class="text-4xl font-bold text-orange-600">MPMO</h1>
                        </header>
                        <div class="flex flex-col items-center justify-center">
                            <dt class="mb-2 text-3xl font-extrabold">{{ number_format(auth()->user()->mpmobal, 2) }}</dt>
                            <dd class="text-gray-500 dark:text-gray-400">$MPMO Balance</dd>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="bg-white rounded-lg shadow p-5">
                        <header class="text-center mb-10">
                            <img src="{{ asset("storage/img/trx-logo.png") }}" alt="TRX Logo" class="mx-auto w-32 h-32 mb-4">
                            <h1 class="text-4xl font-bold text-orange-600">TRX</h1>
                        </header>
                        <div class="flex flex-col items-center justify-center">
                            <dt class="mb-2 text-3xl font-extrabold">{{ number_format(auth()->user()->trxbal, 2) }}</dt>
                            <dd class="text-gray-500 dark:text-gray-400">$TRX Balance</dd>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="bg-white rounded-lg shadow p-5">
                        <header class="text-center mb-10">
                            <img src="{{ asset("storage/img/usdt-logo.png") }}" alt="USDT Logo" class="mx-auto w-32 h-32 mb-4">
                            <h1 class="text-4xl font-bold text-orange-600">USDT</h1>
                        </header>
                        <div class="flex flex-col items-center justify-center">
                            <dt class="mb-2 text-3xl font-extrabold">{{ number_format(auth()->user()->usdtsbal, 2) }}</dt>
                            <dd class="text-gray-500 dark:text-gray-400">$USDT Balance</dd>
                        </div>
                    </div>

                     <!-- Card 3 -->
                     <div class="bg-white rounded-lg shadow p-5">
                        <header class="text-center mb-10">
                            <img src="{{ asset("storage/img/egg-common.webp") }}" alt="Egg Logo" class="mx-auto w-32 h-32 mb-4">
                            <h1 class="text-4xl font-bold text-orange-600">Pets</h1>
                        </header>
                        <div class="flex flex-col items-center justify-center">
                            <dt class="mb-2 text-3xl font-extrabold">0</dt>
                            <dd class="text-gray-500 dark:text-gray-400">Pets</dd>
                        </div>
                    </div>
            </div>
        </div>
    </div>

      
</x-app-layout>
