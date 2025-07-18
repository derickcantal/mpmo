<x-app-layout>
    @include('layouts.home.navigation')
    <div class="gradient-bg dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg py-4 flex-1 pb-16 sm:pb-safe">
        <div class="mx-auto sm:px-6 lg:px-8">
            <!-- Error & Success Notification -->
            @include('layouts.notifications') 
            {{-- Content --}}
            <p class="lg:text-5xl sm:text-3xl font-bold text-pink mb-8 p-4">Welcome, {{ Auth::user()->fullname }}!</p>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 px-4">
                <!-- Card 1 -->
                <div class="bg-white rounded-lg shadow p-5">
                    <header class="text-center mb-10">
                        <h5 class="text-2xl font-bold text-orange-600">Account Information</h5>
                    </header>
                    @php
                        $qrdep = optional($wallets->first())->qrcwaddress;
                    @endphp
                    <div class="flex flex-col items-center justify-center">
                        @if($qrdep)
                            <img class="mx-auto w-32 h-32 mb-4" src="{{ asset("/storage/$qrdep") }}" alt="QR" />
                            <dd class="text-gray-500 dark:text-gray-400">Deposit Address:</dd>
                            <dt class="mb-2 text-1xl font-extrabold">{{ $qrdep }}</dt>
                        @else
                            <p>No deposit wallet configured yet.</p>
                        @endif
                    </div>
                </div>
                <!-- Card 1 -->
                <div class="bg-white rounded-lg shadow p-5">
                    <header class="text-center mb-10">
                        <img src="{{ asset("storage/img/logo.png") }}" alt="MPMO Logo" class="mx-auto w-32 h-32 mb-4">
                        <h1 class="text-4xl font-bold text-orange-600">MPMO</h1>
                    </header>
                    <div class="flex flex-col items-center justify-center">
                        <dt class="mb-2 text-3xl text-yellow-500 font-extrabold">{{ number_format(Auth::user()->mpmo_balance, 2) }}</dt>
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
                        <dt class="mb-2 text-3xl text-pink-500 font-extrabold">{{ number_format(Auth::user()->trx_balance, 2) }}</dt>
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
                        <dt class="mb-2 text-3xl font-extrabold">0.00</dt>
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
</x-app-layout>