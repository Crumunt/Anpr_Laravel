@props([
    'variant' => 'default', // default, success, warning, danger, info
    'size' => 'md', // sm, md, lg
    'with_dot' => false
])

@php
    $baseClasses = 'inline-flex items-center font-medium rounded-full';
    
    $variantClasses = [
        'default' => 'bg-gray-100 text-gray-800',
        'success' => 'bg-green-100 text-green-800',
        'warning' => 'bg-amber-100 text-amber-800',
        'danger' => 'bg-red-100 text-red-800',
        'info' => 'bg-blue-100 text-blue-800',
        'purple' => 'bg-purple-100 text-purple-800'
    ];
    
    $sizeClasses = [
        'sm' => 'px-2 py-0.5 text-xs',
        'md' => 'px-2.5 py-1 text-xs',
        'lg' => 'px-3 py-1.5 text-sm'
    ];
    
    $classes = $baseClasses . ' ' . $variantClasses[$variant] . ' ' . $sizeClasses[$size];
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    @if($with_dot)
        <span class="w-1.5 h-1.5 bg-current rounded-full mr-1.5 mt-1"></span>
    @endif
    {{ $slot }}
</span> 