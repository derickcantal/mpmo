@props(['type' => 'button', 'as' => 'button'])

<{{ $as }} {{ $attributes->merge([
    'type' => $type,
    'class' => 'inline-flex items-center px-5 py-2 bg-indigo-500 hover:bg-indigo-600 text-white font-semibold rounded-full shadow focus:outline-none focus:ring-2 focus:ring-indigo-300 transition'
]) }}>
    {{ $slot }}
</{{ $as }}>