@props([
    'name',
    'label',
    'value' => null,
    'placeholder' => 'YYYY-MM-DD',
    'wrapperClass' => 'mb-3 p-3 bg-blue-50 border border-blue-200 rounded-lg',
])

@php
    $fieldId = $attributes->get('id') ?? $name . '-field';
@endphp

<div class="{{ $wrapperClass }}">
    <label for="{{ $fieldId }}" class="block font-medium text-gray-700 mb-1">{{ $label }}</label>
    <div class="relative">
        <input
            id="{{ $fieldId }}"
            type="date"
            name="{{ $name }}"
            value="{{ $value }}"
            placeholder="{{ $placeholder }}"
            {{ $attributes->class([
                'w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition placeholder-gray-400 pl-10 pr-3 py-2',
                'border-red-500 focus:ring-red-400 focus:border-red-500' => $errors->has($name),
            ]) }}
        >
        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-blue-400 pointer-events-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        </span>
    </div>
    @error($name)
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>
