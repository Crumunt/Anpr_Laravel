@props([
    'id',
    'name',
    'label',
    'placeholder' => '',
    'required' => false,
    'rows' => 3
])

<div class="space-y-2">
    <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="{{ $id }}">
        {{ $label }}
    </label>
    
    <textarea 
        class="flex w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-sm 
               placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 
               focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 min-h-[80px] 
               transition-all duration-300 focus:border-green-500 focus:ring-2 
               focus:ring-green-200 hover:border-green-300" 
        id="{{ $id }}" 
        name="{{ $name }}" 
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
    >{{ $slot }}</textarea>
</div>