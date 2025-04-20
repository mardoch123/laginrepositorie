@props(['disabled' => false, 'type' => 'text'])

<input {{ $disabled ? 'disabled' : '' }} type="{{ $type }}" {!! $attributes->merge(['class' => 'mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md']) !!}>