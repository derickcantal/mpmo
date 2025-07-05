<nav class="overflow-x-auto bg-white dark:bg-gray-700">
    <div class="max-w-screen-xl px-4 py-3 mx-auto">
        <div class="flex items-center">
            <ul class="flex flex-row font-medium mt-0 space-x-8 rtl:space-x-reverse text-sm">
                <li>
                    <a href="{{ url('/dashboard') }}" class="text-gray-900 dark:text-white hover:underline" aria-current="page">
                        Home</a>
                </li>
                <li>
                    <a href="{{ route('manageuser.index') }}" class="text-gray-900 dark:text-white hover:underline">
                        Users</a>
                </li>
                <li>
                    <a href="{{ route('managetempusers.index') }}" class="text-gray-900 dark:text-white hover:underline">
                        TUsers</a>
                </li>
                <li>
                    <a href="{{ route('managewallet.index') }}" class="text-gray-900 dark:text-white hover:underline">
                        Wallet</a>
                </li>
                <li>
                    <a href="#" class="text-gray-900 dark:text-white hover:underline">
                        Deposits</a>
                </li>
                <li>
                    <a href="#" class="text-gray-900 dark:text-white hover:underline">
                        Withdrawal</a>
                </li>
            </ul>
        </div>
    </div>
</nav>