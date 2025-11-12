@props(['click', 'label', 'action', 'variant' => 'default'])
<button @click="{{ $click }}"
    class="flex w-full items-center px-4 py-2.5 text-sm text-gray-700 transition-colors hover:bg-green-50 rounded-md my-0.5">
    <x-dynamic-component :component="'icons.' .$action" :variant="$variant"/>
    {{ $label }}
</button>

