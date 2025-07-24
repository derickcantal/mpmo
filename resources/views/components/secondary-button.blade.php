@props(['type' => 'button', 'as' => 'button'])

<{{ $as }} {{ $attributes->merge([
    'type' => $type,
    'class' => 'inline-flex items-center px-4 py-2 bg-gray-700 hover:bg-gray-600 text-gray-200 font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-300 transition'
]) }}>
    {{ $slot }}
</{{ $as }}>