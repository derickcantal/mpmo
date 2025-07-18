<x-app-layout>
    @include('layouts.home.navigation')
    <div class="gradient-bg min-h-screen dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg py-2 flex-1 pb-16 sm:pb-safe">
        <div class="mx-auto sm:px-6 lg:px-8">
            <!-- Error & Success Notification -->
            @include('layouts.notifications') 
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg dark:bg-gray-800">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Transaction Information
                    </h3>
                </div>
                <!-- Modal body -->
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <!-- txnhash -->
                        <div class="col-span-2 sm:col-span-1 p-4">
                            <div class="form-group">
                                <x-input-label for="txnhash" :value="__('Transaction Hash')" />
                                <h5 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $transaction->txnhash }}
                                </h5>
                            </div>
                        </div>
                        <!-- to -->
                        <div class="col-span-2 sm:col-span-1 p-4">
                            <div class="form-group">
                                <x-input-label for="to" :value="__('To')" />
                                <h5 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $transaction->addressreceive }}
                                </h5>
                            </div>
                        </div>
                        <!-- from -->
                        <div class="col-span-2 sm:col-span-1 p-4">
                            <div class="form-group">
                                <x-input-label for="from" :value="__('From')" />
                                <h5 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $transaction->addresssend }}
                                </h5>
                            </div>
                        </div>
                        <!-- txntype -->
                        <div class="col-span-2 sm:col-span-1 p-4">
                            <div class="form-group">
                                <x-input-label for="txntype" :value="__('Type')" />
                                <h5 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $transaction->txntype }}
                                </h5>
                            </div>
                        </div>
                        <!-- trxamount -->
                        <div class="col-span-2 sm:col-span-1 p-4">
                            <div class="form-group">
                                <x-input-label for="trxamount" :value="__('TRX Amount')" />
                                <h5 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $transaction->trx_amount }}
                                </h5>
                            </div>
                        </div>
                        <!-- status -->
                        <div class="col-span-2 sm:col-span-1 p-4">
                            <div class="form-group">
                                <x-input-label for="status" :value="__('Status')" />
                                <h5 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $transaction->status }}
                                </h5>
                            </div>
                        </div>
                        
                        
                    </div>
                    <div class="flex items-center justify-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                        <a href="{{ route('managetxn.index') }}" class="py-2 px-3 ms-3 flex items-center text-sm font-medium text-center text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                            <svg class="w-4 h-4 mr-2 -ml-0.5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/>
                            </svg>
                            Close
                        </a>
                    </div>
            </div>
        </div>
    </div>
</x-app-layout>
