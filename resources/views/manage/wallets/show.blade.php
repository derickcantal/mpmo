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
                        Wallet Information
                    </h3>
                </div>
                <!-- Modal body -->
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <!-- username -->
                        <div class="col-span-2 sm:col-span-1 p-4">
                            <div class="form-group">
                                <x-input-label for="username" :value="__('Address')" />
                                <h5 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $wallet->cwaddress }}
                                </h5>
                            </div>
                        </div>
                        <!-- QR Address -->
                        <div class="col-span-2 sm:col-span-1 p-4">
                            <div class="form-group">
                                <x-input-label for="email" :value="__('QR Address')" />
                                <img data-modal-target="qraddress" data-modal-toggle="qraddress" width="100" height="100" class="rounded-lg mt-4 p-1" src="{{ asset("/storage/$wallet->qrcwaddress") }}" alt="Image" />
                            </div>
                        </div>
                        <!-- Email Address -->
                        <div class="col-span-2 sm:col-span-1 p-4">
                            <div class="form-group">
                                <x-input-label for="email" :value="__('Code')" />
                                <h5 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $privatekey }}
                                </h5>
                            </div>
                        </div>
                        <!-- QR Address -->
                        <div class="col-span-2 sm:col-span-1 p-4">
                            <div class="form-group">
                                <x-input-label for="email" :value="__('QR Address')" />
                                <img data-modal-target="qrcode" data-modal-toggle="qrcode" width="100" height="100" class="rounded-lg mt-4 p-1" src="{{ asset("/storage/$wallet->qrwallcode") }}" alt="Image" />
                            </div>
                        </div>
                        <!-- status -->
                        <div class="col-span-2 sm:col-span-1 p-4">
                            <div class="form-group">
                                <x-input-label for="status" :value="__('Wallet Status')" />
                                <h5 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $wallet->walletstatus }}
                                </h5>
                            </div>
                        </div>
                        <!-- status -->
                        <div class="col-span-2 sm:col-span-1 p-4">
                            <div class="form-group">
                                <x-input-label for="status" :value="__('Status')" />
                                <h5 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $wallet->status }}
                                </h5>
                            </div>
                        </div>
                        
                        
                    </div>
                    <div class="flex items-center justify-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                        <a href="{{ route('managewallet.index') }}" class="py-2 px-3 ms-3 flex items-center text-sm font-medium text-center text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                            <svg class="w-4 h-4 mr-2 -ml-0.5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/>
                            </svg>
                            Close
                        </a>
                    </div>
            </div>
            <!-- qraddress -->
            <div id="qraddress" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                <div class="relative w-full max-w-md max-h-full">
                    <!-- Modal content -->
                    <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                            <h3 class="text-xl font-medium text-gray-900 dark:text-white">
                                Address QR Image
                            </h3>
                            <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="qraddress">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-4 md:p-5 space-y-4">
                            <img class="h-auto max-w-lg mx-auto rounded-lg" src="{{ asset("/storage/$wallet->qrcwaddress") }}" alt="Image" />

                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                            <button data-modal-hide="qraddress" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- qrcode -->
            <div id="qrcode" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                <div class="relative w-full max-w-md max-h-full">
                    <!-- Modal content -->
                    <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                            <h3 class="text-xl font-medium text-gray-900 dark:text-white">
                                Code QR Image
                            </h3>
                            <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="qrcode">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-4 md:p-5 space-y-4">
                            <img class="h-auto max-w-lg mx-auto rounded-lg" src="{{ asset("/storage/$wallet->qrwallcode") }}" alt="Image" />

                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                            <button data-modal-hide="qrcode" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
