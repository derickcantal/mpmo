<x-app-layout>
    @include('layouts.home.navigation')
    <div class="gradient-bg min-h-screen dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg py-2 flex-1 pb-16 sm:pb-safe">
        <div class="mx-auto sm:px-6 lg:px-8">
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
                        <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">Modify</span>
                    </div>
                    </li>
                    <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="rtl:rotate-180  w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">Show</span>
                    </div>
                    </li>
                    <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="rtl:rotate-180  w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">{{ $user->username }}</span>
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
                        User Profile Information
                    </h3>
                </div>
                <!-- Modal body -->
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <!-- Referral Link -->
                        <div class="col-span-2 sm:col-span-1 px-4">
                            <div class="form-group mt-4">
                                <x-input-label for="reflink" :value="__('Referral Link')" />
                                <a id="reflink" href="{{ url('/ref/'.$user->referral_code) }}" class="text-lg font-semibold text-blue-900 dark:text-white">
                                    {{  url('/ref/'.$user->referral_code)  }}
                                </a>
                                <!-- 2. The copy‐to‐clipboard button -->
                                <button type="button" class="ml-2 p-1 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200" aria-label="Copy label text"
                                    onclick="navigator.clipboard.writeText(
                                    document.getElementById('reflink').innerText.trim()
                                    )">
                                    <!-- Heroicon: Document Duplicate (clipboard) -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h6m4 4h2a2 2 0 012 2v8a2 2 0 01-2 2h-8m4-4V4m0 0H8m4 0h4" />
                                    </svg>
                                </button>
                            </div>
                        </div>
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
                        <!-- fullname -->
                        <div class="col-span-2 sm:col-span-1 p-4">
                            <div class="form-group">
                                <x-input-label for="fullname" :value="__('Full Name')" />
                                <h5 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $user->fullname }}
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
                        <a href="{{ route('manageuser.index') }}" class="py-2 px-3 ms-3 flex items-center text-sm font-medium text-center text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
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
