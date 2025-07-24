@props(['type' => 'button', 'as' => 'button', 'color' => 'pink'])

@php
  $colors = [
    'pink'  => 'bg-pink-500 hover:bg-pink-600 focus:ring-pink-300',
    'yellow'=> 'bg-yellow-500 hover:bg-yellow-600 focus:ring-yellow-300',
  ];
  $classes = $colors[$color] ?? $colors['pink'];
@endphp

<{{ $as }} {{ $attributes->merge([
    'type' => $type,
    'class' => "inline-flex items-center px-5 py-2 {$classes} text-white font-semibold rounded-full shadow focus:outline-none focus:ring-2 transition"
]) }}>
    {{ $slot }}
</{{ $as }}>