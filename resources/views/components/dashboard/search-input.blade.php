{{-- resources/views/components/search-input.blade.php --}}
@props([
    'placeholder' => 'Search...',
    'name' => 'search',
    'id' => null,
    'clearButtonId' => null,
    'maxWidth' => 'max-w-[280px]',
    'size' => 'default', // options: 'sm', 'default', 'lg'
    'variant' => 'primary', // options: 'primary', 'secondary', 'minimal'
    'iconLeft' => true,
    'rounded' => 'lg', // options: 'sm', 'md', 'lg', 'full'
    'showSubmitButton' => false
])

@php
    $inputId = $id ?? 'searchInput-' . uniqid();
    $clearBtnId = $clearButtonId ?? 'clearSearchBtn-' . uniqid();
    
    // Size classes
    $sizeClasses = [
        'sm' => 'h-9 text-xs pl-8 pr-8',
        'default' => 'h-11 text-sm pl-10 pr-10',
        'lg' => 'h-12 text-base pl-11 pr-11'
    ][$size] ?? 'h-11 text-sm pl-10 pr-10';
    
    // Icon size classes
    $iconSizeClasses = [
        'sm' => 'h-3 w-3',
        'default' => 'h-4 w-4',
        'lg' => 'h-5 w-5'
    ][$size] ?? 'h-4 w-4';
    
    // Variant classes
    $variantClasses = [
        'primary' => 'bg-gray-50 border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/30 focus:shadow-md',
        'secondary' => 'bg-white border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 focus:shadow-md',
        'minimal' => 'bg-transparent border-gray-200 focus:border-gray-400 focus:ring-1 focus:ring-gray-400/20'
    ][$variant] ?? 'bg-gray-50 border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/30 focus:shadow-md';
    
    // Rounded classes
    $roundedClasses = [
        'sm' => 'rounded',
        'md' => 'rounded-md',
        'lg' => 'rounded-lg',
        'full' => 'rounded-full'
    ][$rounded] ?? 'rounded-lg';
@endphp

<div {{ $attributes->merge(['class' => "relative flex-1 {$maxWidth} group"]) }}>
    @if($iconLeft)
    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 {{ $iconSizeClasses }} text-gray-400 transition-colors duration-200 group-focus-within:text-green-500"></i>
    @endif
    
    <input
        type="text"
        id="{{ $inputId }}"
        name="{{ $name }}"
        class="w-full border px-3 py-2 shadow-sm transition-all duration-300 {{ $sizeClasses }} {{ $variantClasses }} {{ $roundedClasses }} placeholder:text-gray-400 group-hover:border-gray-300"
        placeholder="{{ $placeholder }}"
        value="{{ request($name) }}"
    />
    
    <button
        type="button"
        id="{{ $clearBtnId }}"
        data-input-id="{{ $inputId }}"
        class="clear-search-btn absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-all duration-200 opacity-0 scale-90 {{ request($name) ? 'opacity-100 scale-100' : 'pointer-events-none' }}"
    >
        <i class="fas fa-times {{ $iconSizeClasses }}"></i>
    </button>
    
    @if($showSubmitButton)
    <button
        type="submit"
        class="absolute right-2 top-1/2 transform -translate-y-1/2 h-7 w-7 flex items-center justify-center bg-green-500 text-white {{ $rounded === 'full' ? 'rounded-full' : 'rounded' }} transition-all duration-200 hover:bg-green-600"
    >
        <i class="fas fa-search {{ $size === 'sm' ? 'h-3 w-3' : 'h-3.5 w-3.5' }}"></i>
    </button>
    @endif
</div>

@once
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set up event listeners for search inputs and clear buttons
        document.querySelectorAll('.clear-search-btn').forEach(clearBtn => {
            const searchInputId = clearBtn.dataset.inputId;
            const searchInput = document.getElementById(searchInputId);
            
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    if (this.value) {
                        clearBtn.classList.remove('opacity-0', 'scale-90', 'pointer-events-none');
                        clearBtn.classList.add('opacity-100', 'scale-100');
                    } else {
                        clearBtn.classList.add('opacity-0', 'scale-90', 'pointer-events-none');
                        clearBtn.classList.remove('opacity-100', 'scale-100');
                    }
                });
                
                clearBtn.addEventListener('click', function() {
                    searchInput.value = '';
                    searchInput.focus();
                    this.classList.add('opacity-0', 'scale-90', 'pointer-events-none');
                    this.classList.remove('opacity-100', 'scale-100');
                });
            }
        });
    });
</script>
@endpush
@endonce