<x-app-layout>
    @include('layouts.home.navigation')

    <div class="gradient-bg min-h-screen dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg py-2 flex-1 pb-16 sm:pb-safe">
        <div class="mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex px-5 py-3 text-gray-700 bg-transparent dark:bg-gray-800 dark:border-gray-700" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                    <li class="inline-flex items-center">
                    <a href="{{ route('wallet.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                        <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                        </svg>
                        Manage
                    </a>
                    </li>
                    <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="rtl:rotate-180  w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">
                            Wallet</span>
                    </div>
                    </li>
                </ol>
            </nav>
            <!-- searchbar -->
            <div class="relative bg-white shadow-md dark:bg-gray-800 sm:rounded-lg">
                <div class="flex flex-col md:flex-row items-stretch md:items-center md:space-x-3 space-y-3 md:space-y-0 justify-between mx-4 py-4 dark:border-gray-700 ">
                    <form action="{{ route('wallet.create') }}" method="POST">
                        @csrf
                        <button type="submit" class="flex items-center justify-center text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800">
                            <svg class="h-3.5 w-3.5 mr-1.5 -ml-1" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                            </svg>
                            Create New Wallet
                        </button>
                    </form>
                    
                    <form class="flex items-center" action="{{ route('managewallet.search') }}" method="get">
                        @csrf
                        <div class="flex flex-col items-stretch justify-end flex-shrink-0 w-full space-y-2 md:w-auto md:flex-row md:space-y-0 md:items-center md:space-x-3">
                            
                            <select id="pagerow" name="pagerow" class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg md:w-auto focus:outline-none hover:bg-gray-100 hover:text-green-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700" :value="old('pagerow')">
                                <option value = "10">10</option>    
                                <option value = "25">25</option>    
                                <option value = "50">50</option>
                                <option value = "100">100</option>
                                <option value = "250">250</option>            
                            </select>
                            <select id="orderrow" name="orderrow" class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg md:w-auto focus:outline-none hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700" :value="old('orderrow')">
                                <option value = "asc">A-Z</option>
                                <option value = "desc">Z-A</option>   
                            </select>
                            <div class="w-full md:w-1/2">
                                <label for="simple-search" class="sr-only">Search</label>
                                <div class="relative w-full">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input type="text" name="search" id="search" class="block w-full p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Search" >
                                </div>
                            </div>            
            
                            <button type="submit" class="py-2 px-3 flex items-center text-sm font-medium text-center text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg text-sm px-4 py-2 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                <svg class="w-4 h-4 mr-2 -ml-0.5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/>
                                </svg>
                                Search
                            </button>
                            
                        </div>
                    </form>
                </div>
            </div>
            <!-- Error & Success Notification -->
            @include('layouts.notifications') 
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                @foreach($wallets as $wallet)
                <div class="p-4 bg-white rounded-lg shadow">
                    <img src="{{ asset("storage/$wallet->qrcwaddress") }}" alt="MPMO Logo" class="mx-auto w-32 h-32 mb-4">
                    <h3 class="font-semibold mb-2">{{ $wallet->user->email }}</h3>
                    <h3 class="font-semibold mb-2">{{ $wallet->cwaddress }}</h3>
                    <p>Balance: {{ number_format($wallet->balance['balance_trx'], 2) }} TRX</p>
                    <a href="{{ route('wallet.send', $wallet->cwaddress) }}" class="text-blue-600 hover:underline mt-2 inline-block">Send TRX</a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
