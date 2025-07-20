@props([
    'type' => 'button',
    'text' => '',
    'icon' => false,
    'variant' => 'primary', // primary, secondary, danger, cancel
    'size' => 'md', // sm, md, lg
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-semibold rounded-lg transition duration-300 shadow-sm hover:shadow-md transform hover:-translate-y-0.5 focus:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-1 active:scale-[0.98]';
    
    $variantClasses = [
        'primary' => 'bg-green-600 hover:bg-green-700 focus:bg-green-700 text-white focus:ring-green-500',
        'secondary' => 'bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 text-white focus:ring-blue-500',
        'danger' => 'bg-red-600 hover:bg-red-700 focus:bg-red-700 text-white focus:ring-red-500',
        'cancel' => 'bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 text-gray-800 focus:ring-gray-400'
    ];
    
    $sizeClasses = [
        'sm' => 'h-9 px-4 text-xs',
        'md' => 'h-11 px-5 text-sm',
        'lg' => 'h-12 px-6 text-base'
    ];
    
    $classes = $baseClasses . ' ' . ($variantClasses[$variant] ?? $variantClasses['primary']) . ' ' . ($sizeClasses[$size] ?? $sizeClasses['md']);
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
    @if($icon && !($slot->isNotEmpty() && isset($slots['icon'])))
        <span class="mr-2 flex-shrink-0">
            @if($variant === 'cancel')
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true" role="img" focusable="false">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            @else
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true" role="img" focusable="false">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            @endif
        </span>
    @endif

    @if($slot->isNotEmpty())
        {{ $slot }}
    @elseif($text)
        <span>{{ $text }}</span>
    @endif
</button>
