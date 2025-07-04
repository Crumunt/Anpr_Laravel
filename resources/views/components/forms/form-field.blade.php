@props([
    'label',
    'id',
    'name',
    'type' => 'text',
    'placeholder' => '',
    'required' => false,
    'colspan' => 1,
    'value' => ''
])

<div class="space-y-2 {{ $colspan > 1 ? 'md:col-span-'.$colspan : '' }}">
    <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="{{ $id }}">
        {{ $label }}
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    
    @if($type === 'select')
        <div class="relative">
            <select 
                id="{{ $id }}" 
                name="{{ $name }}" 
                class="appearance-none flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-green-200 transition-all duration-300"
                {{ $required ? 'required' : '' }}
            >
                {{ $slot }}
            </select>
            
            <!-- Dropdown Arrow Icon -->
            <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-50">
                    <path d="M4.93179 5.43179C4.75605 5.60753 4.75605 5.89245 4.93179 6.06819C5.10753 6.24392 5.39245 6.24392 5.56819 6.06819L7.49999 4.13638L9.43179 6.06819C9.60753 6.24392 9.89245 6.24392 10.0682 6.06819C10.2439 5.89245 10.2439 5.60753 10.0682 5.43179L7.81819 3.18179C7.73379 3.0974 7.61933 3.04999 7.49999 3.04999C7.38064 3.04999 7.26618 3.0974 7.18179 3.18179L4.93179 5.43179ZM10.0682 9.56819C10.2439 9.39245 10.2439 9.10753 10.0682 8.93179C9.89245 8.75606 9.60753 8.75606 9.43179 8.93179L7.49999 10.8636L5.56819 8.93179C5.39245 8.75606 5.10753 8.75606 4.93179 8.93179C4.75605 9.10753 4.75605 9.39245 4.93179 9.56819L7.18179 11.8182C7.35753 11.9939 7.64245 11.9939 7.81819 11.8182L10.0682 9.56819Z" fill="currentColor" fill-rule="evenodd" clip-rule="evenodd"></path>
                </svg>
            </div>
        </div>
    @else
        <input 
            type="{{ $type }}" 
            class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring transition-all duration-300 focus:border-green-500 focus:ring-2 focus:ring-green-200 hover:border-green-300" 
            id="{{ $id }}" 
            name="{{ $name }}" 
            value="{{ $value }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
        >
    @endif
</div>