@props(['id', 'rows' => 4, 'value' => '', 'error' => $errors->first($id)])

<textarea
  {{ $attributes->merge([
      'id'    => $id,
      'rows'  => $rows,
      'class' => 'w-full px-4 py-2 bg-gray-700 text-gray-200 placeholder-gray-500 border border-gray-600 rounded-lg focus:outline-none focus:ring-indigo-300 focus:border-indigo-300'
  ]) }}
>{{ old($id, $value) }}</textarea>

@isset($error)
  <p class="text-red-500 text-sm mt-1">{{ $error }}</p>
@endisset
