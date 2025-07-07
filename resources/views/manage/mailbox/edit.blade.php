<x-app-layout>
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm">
        <div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
            @include('layouts.home.navigation')
        </div>
    </div>
    <div class="py-8 max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="py-8 max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
                <form action="{{ route('managemailbox.update',$mailbox->username) }}" method="POST">
                    @csrf
                    @method('PATCH') 
                    <!-- Breadcrumb -->
                    <nav class="flex px-5 py-3 text-gray-700 bg-white dark:bg-gray-800 dark:border-gray-700" aria-label="Breadcrumb">
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
                                <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">Modify Mail Account</span>
                            </div>
                            </li>
                            <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="rtl:rotate-180  w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                                <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">{{ $mailbox->username }}</span>
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
                                        <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username', $mailbox->username)" required autofocus readonly/>
                                        <x-input-error :messages="$errors->get('username')" class="mt-2" />
                                    </div>
                                </div>
                                <div class="col-span-2 sm:col-span-1 p-4">
                                    <!-- Email Address -->
                                    <div class="form-group">
                                        <x-input-label for="email_other" :value="__('Other Email')" />
                                        <x-text-input id="email_other" class="block mt-1 w-full" type="email" name="email_other" :value="old('email_other', $mailbox->email_other)" required />
                                        <x-input-error :messages="$errors->get('email_other')" class="mt-2" />
                                    </div>
                                </div>
                                <div class="col-span-2 sm:col-span-1 p-4">
                                    <!-- Password -->
                                    <div class="form-group">
                                        <x-input-label for="password" :value="__('Password')" />

                                        <x-text-input id="password" class="block mt-1 w-full"
                                                        type="password"
                                                        name="password"
                                                            />

                                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                    </div>
                                </div>
                                <div class="col-span-2 sm:col-span-1 p-4">
                                    <!-- Confirm Password -->
                                    <div class="form-group">
                                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

                                        <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                                        type="password"
                                                        name="password_confirmation"  />

                                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                    </div>                    
                                </div>
                                <div class="col-span-2 sm:col-span-1 p-4">
                                    <!-- firstname -->
                                    <div class="form-group">
                                        <x-input-label for="name" :value="__('Full Name')" />
                                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $mailbox->name)" required autofocus/>
                                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                    </div>
                                </div>

                                <div class="col-span-2 sm:col-span-1 p-4">
                                    <!-- firstname -->
                                    <div class="form-group">
                                        <x-input-label for="phone" :value="__('Mobile No.')" />
                                        <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone', $mailbox->phone)" required autofocus/>
                                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                    </div>
                                </div>
                                
                                <div class="col-span-2 sm:col-span-1 p-4">
                                    <!-- status -->
                                    @php
                                        $op1 = '';
                                        $op2 = '';
                                        if ($mailbox->active == '1'):
                                            $op1 = 'selected = "selected"';
                                        elseif ($mailbox->active == '0'):
                                            $op2 = 'selected = "selected"';
                                        endif;
                                    @endphp
                                    <div class="form-group">
                                        <x-input-label for="active" :value="__('Status')" />
                                        <!-- <x-text-input id="status" class="block mt-1 w-full" type="text" name="status" :value="old('status')" required autofocus autocomplete="off" /> -->
                                        <select id="active" name="active" class="form-select mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" :value="old('active', $mailbox->active)">
                                            <option value ="1"  {{ $op1; }}>Active</option>
                                            <option value ="0"  {{ $op2; }}">Inactive</option>
                                        </select>
                                        <x-input-error :messages="$errors->get('active')" class="mt-2" />
                                    </div>
                                </div>
                            </div>
                            <!-- Button -->
                            <div class="flex items-center justify-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                                <button type="submit" class="py-2 px-3 flex items-center text-sm font-medium text-center text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                    <svg class="w-4 h-4 mr-2 -ml-0.5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 0 1 1-1h11.586a1 1 0 0 1 .707.293l2.414 2.414a1 1 0 0 1 .293.707V19a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V5Z"/>
                                        <path stroke="currentColor" stroke-linejoin="round" stroke-width="2" d="M8 4h8v4H8V4Zm7 10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                    </svg>
                                    Update
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
    </div>
</x-app-layout>