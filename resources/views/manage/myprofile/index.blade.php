<x-app-layout>
    @include('layouts.home.navigation')
    <div class="gradient-bg min-h-screen dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg py-2 flex-1 pb-16 sm:pb-safe">
        <div class="mx-auto sm:px-6 lg:px-8 px-4">
            <!-- Breadcrumb -->
            <nav class="flex px-5 py-3 text-gray-700 bg-transparent dark:bg-gray-800 dark:border-gray-700" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                    <li class="inline-flex items-center">
                    <a href="{{ route('managemyprofile.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                        <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                        </svg>
                        My Profile
                    </a>
                    </li>
                    
                    <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="rtl:rotate-180  w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">
                            My Informations
                        </span>
                    </div>
                    </li>

                    <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="rtl:rotate-180  w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">
                            {{ $user->fullname }}
                        </span>
                    </div>
                    </li>
                </ol>
            </nav>

            <!-- submenu -->
            <div class="text-sm font-medium text-center text-gray-500 border-gray-200 dark:text-gray-400 dark:border-gray-700">
                <ul class="flex flex-wrap -mb-px">
                    <li class="me-2">
                        <a href="{{ route('managemyprofile.index') }}" class="inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500" aria-current="page">
                            Profile</a>
                    </li>
                        <li class="me-2">
                        <a href="{{ route('managemyprofile.myavatar') }}" class="inline-block p-4 text-gray-600 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">
                            Avatar</a>
                    </li>
                    <li class="me-2">
                        <a href="{{ route('managemyprofile.changepassword') }}" class="inline-block p-4 text-gray-600 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">
                            Change Password</a>
                    </li>
                    <li class="me-2">
                        <a href="{{ route('managemyprofile.signature') }}" class="inline-block p-4 text-gray-600 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">
                        Withdrawal</a>
                    </li>
                </ul>
            </div>

            <form action="{{ route('managemyprofile.update',$user->userid) }}" method="POST" class="py-2">
                @csrf
                @method('PATCH')  
                <!-- Error & Success Notification -->
                @include('layouts.notifications') 
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg dark:bg-gray-800 p-4">
                    <!-- Modal body -->
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <!-- Email Address -->
                        <div class="col-span-2 sm:col-span-1 px-4">
                            <div class="form-group mt-4">
                                <x-input-label for="reflink" :value="__('Referral Link')" />
                                <a id="reflink" href="http://mpmo.localhost/ref/{{$user->referral_code}}" class="text-lg font-semibold text-blue-900 dark:text-white">
                                    http://mpmo.localhost/ref/{{ $user->referral_code }}
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
                        <!-- Email Address -->
                        <div class="col-span-2 sm:col-span-1 px-4">
                            <div class="form-group mt-4">
                                <x-input-label for="email" :value="__('Email')" />
                                <h5 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $user->username }}
                                </h5>
                            </div>
                        </div>
                        <!-- Email Address -->
                        <div class="col-span-2 sm:col-span-1 px-4">
                            <div class="form-group mt-4">
                                <x-input-label for="email" :value="__('Email')" />
                                <h5 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $user->email }}
                                </h5>
                            </div>
                        </div>

                        <!-- First  Name -->
                        <div class="col-span-2 sm:col-span-1 px-4">
                            <div class="form-group mt-4">
                                <x-input-label for="firstname" :value="__('First Name')" />
                                <h5 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $user->fullname }}
                                </h5>
                            </div>
                        </div>
                
                        <!-- Birthday -->
                        <div class="col-span-2 sm:col-span-1 px-4">
                            <div class="form-group mt-4">
                                @if(empty($user->birthdate))
                                <div class="form-group">
                                    <x-input-label for="birthdate" :value="__('Birthday')" />
                                    <x-text-input id="birthdate" class="block mt-1 w-full" type="date" name="birthdate" :value="old('birthdate')" required autofocus autocomplete="bday" />
                                    <x-input-error :messages="$errors->get('birthdate')" class="mt-2" />
                                </div>
                                @else
                                <h5 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $user->birthdate }}
                                </h5>
                                @endif
                            </div>
                        </div>

                        <!-- Mobile No -->
                        <div class="col-span-2 sm:col-span-1 px-4">
                            <div class="form-group mt-4">
                                <x-input-label for="mobile_primary" :value="__('Mobile No.')" />
                                <x-text-input id="mobile_primary" class="block mt-1 w-full" type="text" name="mobile_primary" :value="old('mobile_primary', $user->mobile_primary)"  autofocus />
                                <x-input-error :messages="$errors->get('mobile_primary')" class="mt-2" />
                            </div>
                        </div>
                        
                        <!-- Mobile No -->
                            <div class="col-span-2 sm:col-span-1 px-4">
                            <div class="form-group mt-4">
                                <x-input-label for="mobile_secondary" :value="__('Mobile No. (2)')" />
                                <x-text-input id="mobile_secondary" name="mobile_secondary"  class="block mt-1 w-full" type="text" :value="old('mobile_secondary', $user->mobile_secondary)"  autofocus />
                                <x-input-error :messages="$errors->get('mobile_secondary')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Mobile No -->
                        <div class="col-span-2 sm:col-span-1 px-4">
                            <div class="form-group mt-4">
                                <x-input-label for="mobile_secondary" :value="__('Owner Wallet Address')" />
                                <h5 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    @if($user->wallets->isEmpty())
                                        <em>No wallet addresses</em>
                                    @else
                                        @foreach($user->wallets as $cw)
                                        <div class="flex items-center w-full max-w-full">
                                        <!-- 1. The label/span that truncates -->
                                        <span id="copy-label" class="flex-1 block overflow-hidden whitespace-nowrap truncate text-sm font-medium text-gray-700 dark:text-gray-300" >
                                            {{ $cw->cwaddress }}
                                        </span>

                                        <!-- 2. The copy‐to‐clipboard button -->
                                        <button type="button" class="ml-2 p-1 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200" aria-label="Copy label text"
                                            onclick="navigator.clipboard.writeText(
                                            document.getElementById('copy-label').innerText.trim()
                                            )">
                                            <!-- Heroicon: Document Duplicate (clipboard) -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h6m4 4h2a2 2 0 012 2v8a2 2 0 01-2 2h-8m4-4V4m0 0H8m4 0h4" />
                                            </svg>
                                        </button>
                                        </div>
                                        @endforeach
                                    @endif
                                </h5>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                        <button type="submit" class="py-2 px-3 flex items-center text-sm font-medium text-center text-white bg-pink-700 hover:bg-pink-800 focus:ring-4 focus:outline-none focus:ring-pink-300 font-medium rounded-full text-sm px-5 py-2.5 text-center dark:bg-pink-600 dark:hover:bg-pink-700 dark:focus:ring-pink-800">
                            <svg class="w-4 h-4 mr-2 -ml-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 0 1 1-1h11.586a1 1 0 0 1 .707.293l2.414 2.414a1 1 0 0 1 .293.707V19a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V5Z"/>
                                <path stroke="currentColor" stroke-linejoin="round" stroke-width="2" d="M8 4h8v4H8V4Zm7 10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                            </svg>
                            Update
                        </button>
                    </div>

                </div>
                
            </form>
                
        </div>
    </div>
</x-app-layout>
