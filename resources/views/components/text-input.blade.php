@props([
  'id'    => null,
  'name'  => null,
  'type'  => 'text',
  'value' => null,
])

@php
    // Determine field name & ID
    $field   = $name ?? $id;
    $inputId = $id   ?? $name;

    // Get old input or default
    $val = old($field, $value);

    // First validation message
    $error = $errors->first($field);
@endphp

<input
    id="{{ $inputId }}"
    name="{{ $name ?? $inputId }}"
    type="{{ $type }}"
    {{-- Only spit out a value attr if it's a string --}}
    @if(!is_array($val))
      value="{{ $val }}"
    @endif
    {{ $attributes->merge([
      'class' => 'w-full px-4 py-2 bg-gray-700 text-gray-200 placeholder-gray-500
                  border border-gray-600 rounded-lg focus:outline-none
                  focus:ring-indigo-300 focus:border-indigo-300'
    ]) }}
/>

@if($error)
  <p class="text-red-500 text-sm mt-1">{{ $error }}</p>
@endif
