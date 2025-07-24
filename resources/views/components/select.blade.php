{{-- resources/views/components/select.blade.php --}}

@props([
  'id'       => null,
  'name'     => null,
  'options'  => [],      // [ value => label, … ]
  'selected' => null,    // single value or array of values
  'multiple' => false,   // true/false
])

@php
    // Determine field & id
    $field   = $name ?? $id;
    $inputId = $id    ?? $name;

    // Fetch old selection(s)
    $old = old($field, $selected);

    // Normalize to array if multiple
    $oldValues = $multiple
      ? (array) $old
      : [$old];

    // First validation error
    $error = $errors->first($field);
@endphp

<select
    id="{{ $inputId }}"
    name="{{ $field }}{{ $multiple ? '[]' : '' }}"
    {{ $multiple ? 'multiple' : '' }}
    {{ $attributes->merge([
        'class' => 'w-full px-4 py-2 bg-gray-700 text-gray-200 rounded-lg border border-gray-600
                    focus:outline-none focus:ring-indigo-300 focus:border-indigo-300'
    ]) }}
>
    @foreach($options as $value => $label)
        <option value="{{ $value }}"
            @if(in_array((string)$value, array_map('strval', $oldValues), true)) selected @endif
        >
            {{ $label }}
        </option>
    @endforeach
</select>

@if($error)
  <p class="text-red-500 text-sm mt-1">{{ $error }}</p>
@endif
