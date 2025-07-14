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
                            {{ $user->lastname }}, {{ $user->firstname }} {{ $user->middlename }}
                        </span>
                    </div>
                    </li>
                </ol>
            </nav>

            <!-- submenu -->
            <div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-700">
                <ul class="flex flex-wrap -mb-px">
                    <li class="me-2">
                        <a href="{{ route('managemyprofile.index') }}" class="inline-block p-4 text-gray-600 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">
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
                        <a href="{{ route('managemyprofile.signature') }}" class="inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500" aria-current="page">
                            Withdrawal</a>
                    </li>
                </ul>
            </div>

            <form action="{{ route('managemyprofile.savesignature',$user->userid) }}" method="POST" enctype="multipart/form-data" class="py-2">
                @csrf
                @method('PATCH')  
                <!-- Error & Success Notification -->
                @include('layouts.notifications') 
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg p-4 dark:bg-gray-800">
                <header>
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 px-4">
                        {{ __('Withdrawal Address') }}
                    </h2>

                    <p class="text-sm text-gray-600 dark:text-gray-400 px-4">
                        {{ __('Ensure your that the withdrawal Address and QR Code is correct. Wrong Information may result in lost of funds.') }}
                    </p>
                </header>
                    @if(!empty($user->ownerqrcwaddress))
                        <img class="h-auto max-w-sm rounded-lg shadow-xs dark:shadow-gray-800 mt-4 p-4" src="{{ asset("/storage/$user->ownerqrcwaddress") }}" alt="QR" />
                    @endif
                    <!-- Modal body -->
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <!-- s -->
                        <div class="col-span-2 sm:col-span-1 px-4">
                            <div class="form-group mt-4">
                                <x-input-label for="owneraddress" :value="__('Owner Address')" />
                                <x-text-input id="owneraddress" name="owneraddress"  class="block mt-1 w-full" type="text" :value="old('owneraddress', $user->ownercwaddress)" required autofocus />
                                <x-input-error :messages="$errors->get('owneraddress')" class="mt-2" />
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
