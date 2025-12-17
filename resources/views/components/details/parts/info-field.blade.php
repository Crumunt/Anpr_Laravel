@props([
    'label' => '',
    'value' => '',
    'span' => 1,
    'name' => '',
    'type' => 'text',
    'options' => [],
    'placeholder' => '',
    'helpText' => '',
    'required' => false,
    'isEditing' => false,
    'formData' => []
])

@php
    $fieldName = $name ?: Str::slug($label);
@endphp

<div
    class="info-field col-span-{{ $span }} transition-all duration-300 ease-in-out"
    :class="{ 'bg-green-50 p-2 rounded-md -m-2': isEditing }"
>
    <div class="text-sm font-medium text-gray-500 mb-1">
        {{ $label }}
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    </div>

    <!-- Display value (visible in view mode) -->
    <div
        x-show="!isEditing"
        x-transition
        class="text-sm text-gray-900 py-1 px-0.5"
    >
        {{ $value }}
    </div>

    <!-- Edit input (visible in edit mode) -->
    <div x-show="isEditing" x-transition>
        <input
            type="{{ $type }}"
            name="{{ $fieldName }}"
            x-model="{{$value}}"
            placeholder="{{ $placeholder }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-20 transition-all duration-150 text-sm"
            {{ $required ? 'required' : '' }}
        >
    </div>
</div>
