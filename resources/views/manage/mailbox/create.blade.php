<x-app-layout>
    @include('layouts.home.navigation')
    <div class="gradient-bg min-h-screen dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg py-2 flex-1 pb-16 sm:pb-safe">
        <div class="mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('managemailbox.store') }}" method="POST">
                @csrf   
                <!-- Breadcrumb -->
                <nav class="flex px-5 py-3 text-gray-700 bg-transparent dark:bg-gray-800 dark:border-gray-700" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                        <li class="inline-flex items-center">
                        <a href="{{ route('managemailbox.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                            <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                            </svg>
                            Mailbox 
                        </a>
                        </li>
                        <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="rtl:rotate-180  w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">Create New Mail Account</span>
                        </div>
                        </li>
                        
                    </ol>
                </nav>
                <!-- Error & Success Notification -->
                @include('layouts.notifications') 
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg dark:bg-gray-800">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Mail Account Information
                        </h3>
                    </div>
                    <!-- Modal body -->
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2 sm:col-span-1 p-4">
                            <!-- username -->
                            <div class="form-group">
                                <x-input-label for="username" :value="__('Username')" />
                                <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" value="" required autofocus autocomplete="off"/> 
                                
                                <x-input-error :messages="$errors->get('username')" class="mt-2" />
                            </div>
                        </div>
                        <div class="col-span-2 sm:col-span-1 p-4">
                            <!-- cabname -->
                            <div class="form-group">
                                <x-input-label for="fullname" :value="__('Full Name')" />
                                <x-text-input id="fullname" class="block mt-1 w-full" type="text" name="fullname" value="" required autofocus autocomplete="off"/>
                                <x-input-error :messages="$errors->get('fullname')" class="mt-2" />
                            </div>
                        </div>
                        <div class="col-span-2 sm:col-span-1 p-4">
                            <!-- username -->
                            <div class="form-group">
                                <x-input-label for="password" :value="__('Password')" />
                                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" value="" required autofocus autocomplete="off" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>
                        </div>
                        <div class="col-span-2 sm:col-span-1 p-4">
                            <!-- firstname -->
                            <div class="form-group">
                                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" value="" required autofocus autocomplete="off" />
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>
                        </div>
                        <div class="col-span-2 sm:col-span-1 p-4">
                                <!-- lastname -->
                                <div class="form-group">
                                <x-input-label for="phone" :value="__('Mobile No.')" />
                                <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" value="" required autofocus autocomplete="off" />
                                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                            </div>
                        </div>
                        <div class="col-span-2 sm:col-span-1 p-4">
                                <!-- lastname -->
                                <div class="form-group">
                                <x-input-label for="email_other" :value="__('Other Email')" />
                                <x-text-input id="email_other" class="block mt-1 w-full" type="text" name="email_other" value="" required autofocus autocomplete="off" />
                                <x-input-error :messages="$errors->get('email_other')" class="mt-2" />
                            </div>
                        </div>
                    </div>
                    <!-- Button -->
                    <div class="flex items-center justify-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                        <button type="submit" class="py-2 px-3 flex items-center text-sm font-medium text-center text-white bg-pink-700 hover:bg-pink-800 focus:ring-4 focus:outline-none focus:ring-pink-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-pink-600 dark:hover:bg-pink-700 dark:focus:ring-pink-800">
                            <svg class="w-4 h-4 mr-2 -ml-0.5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 0 1 1-1h11.586a1 1 0 0 1 .707.293l2.414 2.414a1 1 0 0 1 .293.707V19a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V5Z"/>
                                <path stroke="currentColor" stroke-linejoin="round" stroke-width="2" d="M8 4h8v4H8V4Zm7 10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                            </svg>
                            Save
                        </button>
                        <a href="{{ route('managemailbox.index') }}" class="py-2 px-3 ms-3 flex items-center text-sm font-medium text-center text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                            <svg class="w-4 h-4 mr-2 -ml-0.5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/>
                            </svg>
                            Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>