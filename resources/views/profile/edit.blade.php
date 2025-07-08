<x-app-layout>
    @include('layouts.home.navigation')
    <div class="py-8">
        <div class="mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    @include('profile.partials.update-password-form')
            </div>
        </div>
    </div>
</x-app-layout>
