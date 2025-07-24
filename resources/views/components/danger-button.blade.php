@props(['type' => 'button', 'as' => 'button'])

<{{ $as }} {{ $attributes->merge([
    'type' => $type,
    'class' => 'inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-full shadow focus:outline-none focus:ring-2 focus:ring-red-300 transition'
]) }}>
    {{ $slot }}
</{{ $as }}>