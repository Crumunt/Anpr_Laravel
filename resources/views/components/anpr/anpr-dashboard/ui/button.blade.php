@props([
    'type' => 'button',
    'variant' => 'primary', // primary, secondary, danger, warning, success
    'size' => 'md', // sm, md, lg
    'disabled' => false,
    'icon' => null,
    'icon_position' => 'left' // left, right
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2';
    
    $variantClasses = [
        'primary' => 'bg-green-600 hover:bg-green-700 text-white focus:ring-green-500',
        'secondary' => 'bg-gray-600 hover:bg-gray-700 text-white focus:ring-gray-500',
        'danger' => 'bg-red-600 hover:bg-red-700 text-white focus:ring-red-500',
        'warning' => 'bg-amber-600 hover:bg-amber-700 text-white focus:ring-amber-500',
        'success' => 'bg-emerald-600 hover:bg-emerald-700 text-white focus:ring-emerald-500',
        'outline' => 'border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 focus:ring-green-500',
        'ghost' => 'bg-transparent hover:bg-gray-100 text-gray-700 focus:ring-green-500'
    ];
    
    $sizeClasses = [
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-6 py-3 text-base'
    ];
    
    $classes = $baseClasses . ' ' . $variantClasses[$variant] . ' ' . $sizeClasses[$size];
    
    if ($disabled) {
        $classes .= ' opacity-50 cursor-not-allowed';
    }
@endphp

<button 
    type="{{ $type }}" 
    {{ $disabled ? 'disabled' : '' }}
    {{ $attributes->merge(['class' => $classes]) }}
>
    @if($icon && $icon_position === 'left')
        <i class="{{ $icon }} mr-2" aria-hidden="true"></i>
    @endif
    
    {{ $slot }}
    
    @if($icon && $icon_position === 'right')
        <i class="{{ $icon }} ml-2" aria-hidden="true"></i>
    @endif
</button> 