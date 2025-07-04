<!-- info-field.blade.php -->
@props([
    'label' => '',
    'value' => '',
    'span' => 1,
    'name' => '',
    'type' => 'text',
    'options' => [],
    'placeholder' => '',
    'helpText' => '',
    'required' => false
])

<div class="info-field col-span-{{ $span }} transition-all duration-300 ease-in-out"><div class="text-sm font-medium text-gray-500 mb-1">
        {{ $label }}
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    </div>
    <!-- Display value (visible in view mode) -->
    <div class="info-value text-sm text-gray-900 py-1 px-0.5">{{ $value }}</div>
    
    <!-- Edit input (hidden by default) -->
    <div class="info-edit hidden">
        @if($type === 'textarea')
            <textarea 
                name="{{ $name ?? Str::slug($label) }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-20 transition-all duration-150 text-sm"
                rows="3"
                placeholder="{{ $placeholder }}"
                {{ $required ? 'required' : '' }}
            >{{ $value }}</textarea>
        @elseif($type === 'select')
            <select 
                name="{{ $name ?? Str::slug($label) }}" 
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-20 transition-all duration-150 text-sm"d ? 'required' : '' }}>
                @foreach($options as $option)
                    <option value="{{ $option['value'] }}" {{ $value == $option['value'] ? 'selected' : '' }}>
                        {{ $option['label'] }}
                    </option>
                @endforeach
            </select>
        @elseif($type === 'date')
            <div class="relative">
                <input 
                    type="date" 
                    name="{{ $name ?? Str::slug($label) }}"
                    value="{{ $value }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-20 transition-all duration-150 text-sm"
                    placeholder="{{ $placeholder }}"
                    {{ $required ? 'required' : '' }}
                >
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
        @elseif($type === 'color')
            <div class="flex mt-1">
                <input 
                    type="color" 
                    name="{{ $name ?? Str::slug($label) }}"
                    value="{{ $value }}"
                    class="h-8 w-8 rounded border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-20"
                    {{ $required ? 'required' : '' }}
                >
                <input
                    type="text"
                    value="{{ $value }}"
                    class="ml-2 flex-1 rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-20 text-sm"
                    data-color-input
                ></div>
        @else
            <input 
                type="{{ $type }}" 
                name="{{ $name ?? Str::slug($label) }}"
                value="{{ $value }}"
                placeholder="{{ $placeholder }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-20 transition-all duration-150 text-sm"
                {{ $required ? 'required' : '' }}
            >
        @endif
        @if($helpText)
            <p class="mt-1 text-xs text-gray-500">{{ $helpText }}</p>
        @endif
    </div>
</div>