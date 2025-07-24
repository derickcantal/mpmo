<x-app-layout>
    <div class="flex h-screen bg-gray-900 text-gray-200">
        <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-10 hidden md:hidden"></div>
            <main class="flex-1 pt-16 md:pl-64 overflow-y-auto p-6">
                <form action="{{ route('manageuser.store') }}" method="POST">
                    @csrf   
                    <!-- Breadcrumb -->
                    <nav class="flex px-5 py-3 text-gray-700 bg-transparent dark:bg-gray-800 dark:border-gray-700" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                            <li class="inline-flex items-center">
                            <a href="{{ route('manageuser.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                                <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                                </svg>
                                Users
                            </a>
                            </li>
                            
                            <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="rtl:rotate-180  w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                                <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">Create New User</span>
                            </div>
                            </li>
                        </ol>
                    </nav>
                    <!-- Error & Success Notification -->
                    @include('layouts.notifications') 
                    <!-- Modal content -->
                    <div class="relative bg-white rounded-lg dark:bg-gray-800 p-4">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-2 md:p-5 border-b rounded-t dark:border-gray-600">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                User Profile Information
                            </h3>
                        </div>
                        <!-- Modal body -->
                        <div class="grid mb-4 grid-cols-2">
                            <div class="col-span-2 sm:col-span-1 p-4">
                                <!-- username -->
                                <div class="form-group">
                                    <x-input-label for="username" :value="__('Username')" />
                                    <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required autofocus autocomplete="username" />
                                    <x-input-error :messages="$errors->get('username')" class="mt-2" />
                                </div>
                            </div>
                            <div class="col-span-2 sm:col-span-1 p-4">
                                <!-- Email Address -->
                                <div class="form-group">
                                    <x-input-label for="email" :value="__('Email')" />
                                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="email" />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>
                            </div>
                            <div class="col-span-2 sm:col-span-1 p-4">
                                <!-- fullname -->
                                <div class="form-group">
                                    <x-input-label for="fullname" :value="__('Full Name')" />
                                    <x-text-input id="fullname" class="block mt-1 w-full" type="text" name="fullname" :value="old('fullname')" required autofocus />
                                    <x-input-error :messages="$errors->get('fullname')" class="mt-2" />
                                </div>
                            </div>
                            
                            <div class="col-span-2 sm:col-span-1 p-4">
                                <!-- birthdate -->
                                <div class="form-group">
                                    <x-input-label for="birthdate" :value="__('Birth Date')" />
                                    <x-text-input id="birthdate" class="block mt-1 w-full" type="date" name="birthdate" :value="old('birthdate')" required autofocus autocomplete="bday" />
                                    <x-input-error :messages="$errors->get('birthdate')" class="mt-2" />
                                </div>
                            </div>
                            <!-- accesstype -->
                            <div class="col-span-2 sm:col-span-1 p-4">
                                <div class="form-group">
                                    <x-input-label for="accesstype" :value="__('Access Type')" />
                                    @php
                                    $roles = [
                                        'member'      => 'Member',
                                        'admin'     => 'Administrator',
                                        'super-admin'=> 'Super Admin',
                                    ];
                                    @endphp

                                    <x-select
                                    id="role"
                                    name="role"
                                    :options="$roles"
                                    selected="{{ old('role', $user->role ?? '') }}"
                                    />
                                    <x-input-error :messages="$errors->get('accesstype')" class="mt-2" />
                                </div>
                            </div>
                        </div>
                        <!-- Button -->
                        <div class="flex items-center justify-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                            <x-button type="submit" class="py-2 px-3 flex items-center text-sm font-medium text-center ">
                                <svg class="w-4 h-4 mr-2 -ml-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 0 1 1-1h11.586a1 1 0 0 1 .707.293l2.414 2.414a1 1 0 0 1 .293.707V19a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V5Z"/>
                                    <path stroke="currentColor" stroke-linejoin="round" stroke-width="2" d="M8 4h8v4H8V4Zm7 10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                </svg>
                                Save
                            </x-button>
                            <a href="{{ route('manageuser.index') }}" class="py-2 px-3 ms-3 flex items-center text-sm font-medium text-center text-gray-900 focus:outline-none bg-white rounded-full border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 rounded-full text-sm px-5 py-2.5 text-center dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                                <svg class="w-4 h-4 mr-2 -ml-0.5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/>
                                </svg>
                                Cancel
                            </a>
                        </div>
                    </div>
                    
                </form>
            </main>
        </div>
    </div>
</x-app-layout>
