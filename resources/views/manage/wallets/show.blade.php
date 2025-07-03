<x-app-layout>
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm">
        <div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
            @include('layouts.home.navigation')
        </div>
    </div>
    <div class="py-8 max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="py-8 max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
                <!-- Error & Success Notification -->
                @include('layouts.notifications') 
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg dark:bg-gray-800">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            User Profile Information
                        </h3>
                    </div>
                    <!-- Modal body -->
                        <div class="grid gap-4 mb-4 grid-cols-2">
                            <!-- username -->
                            <div class="col-span-2 sm:col-span-1 p-4">
                                <div class="form-group">
                                    <x-input-label for="username" :value="__('Username')" />
                                    <h5 class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ $user->username }}
                                    </h5>
                                </div>
                            </div>
                            <!-- Email Address -->
                            <div class="col-span-2 sm:col-span-1 p-4">
                                <div class="form-group">
                                    <x-input-label for="email" :value="__('Email')" />
                                    <h5 class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ $user->email }}
                                    </h5>
                                </div>
                            </div>
                            <!-- firstname -->
                            <div class="col-span-2 sm:col-span-1 p-4">
                                <div class="form-group">
                                    <x-input-label for="firstname" :value="__('First Name')" />
                                    <h5 class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ $user->firstname }}
                                    </h5>
                                </div>
                            </div>
                            <!-- middlename -->
                            <div class="col-span-2 sm:col-span-1 p-4">
                                <div class="form-group">
                                    <x-input-label for="middlename" :value="__('Middle Name')" />
                                    <h5 class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ $user->middlename }}
                                    </h5>
                                </div>
                            </div>
                            <!-- lastname -->
                            <div class="col-span-2 sm:col-span-1 p-4">
                                <div class="form-group">
                                    <x-input-label for="lastname" :value="__('Last Name')" />
                                    <h5 class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ $user->lastname }}
                                    </h5>
                                </div>
                            </div>
                            <!-- birthdate -->
                            <div class="col-span-2 sm:col-span-1 p-4">
                                <div class="form-group">
                                    <x-input-label for="birthdate" :value="__('Birth Date')" />
                                    <h5 class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ $user->birthdate }}
                                    </h5>
                                </div>
                            </div>
                            <!-- accesstype -->
                            <div class="col-span-2 sm:col-span-1 p-4">
                                <div class="form-group">
                                    <x-input-label for="accesstype" :value="__('Access Type')" />
                                    <h5 class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ $user->accesstype }}
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
                   
            </div>
        </div>
    </div>
</x-app-layout>
