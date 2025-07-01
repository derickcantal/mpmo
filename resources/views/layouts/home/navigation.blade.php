<nav class="bg-white dark:bg-gray-700">
    <div class="max-w-screen-xl px-4 py-3 mx-auto">
        <div class="flex items-center">
            <ul class="flex flex-row font-medium mt-0 space-x-8 rtl:space-x-reverse text-sm">
                <li>
                    <a href="{{ url('/dashboard') }}" class="text-gray-900 dark:text-white hover:underline" aria-current="page">
                        Home</a>
                </li>
                <li>
                    <a href="{{ route('manageuser.index') }}" class="text-gray-900 dark:text-white hover:underline">
                        Manage</a>
                </li>
                <li>
                    <a href="#" class="text-gray-900 dark:text-white hover:underline">
                        Temporary Users</a>
                </li>
                <li>
                    <a href="#" class="text-gray-900 dark:text-white hover:underline">
                        My Pets</a>
                </li>
                <li>
                    <a href="#" class="text-gray-900 dark:text-white hover:underline">
                        Records</a>
                </li>
            </ul>
        </div>
    </div>
</nav>