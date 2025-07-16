<button {{ $attributes->merge(['type' => 'submit', 'class' => 'py-2 px-3 flex items-center text-sm font-medium text-center text-white bg-pink-700 hover:bg-pink-800 focus:ring-4 focus:outline-none focus:ring-pink-300 font-medium rounded-full text-sm px-5 py-2.5 text-center dark:bg-pink-600 dark:hover:bg-pink-700 dark:focus:ring-pink-800 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
