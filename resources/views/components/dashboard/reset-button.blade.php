{{--resources/views/components/reset-button.blade.php --}}
@props([
    'id' => 'resetBtn',
    'text' => 'Reset'
])
<button
    type="button"
    id="{{ $id }}"
    {{ $attributes->merge(['class' => 'inline-flex items-center justify-center whitespace-nowrap font-medium rounded-lg text-sm h-11 px-5 gap-2 transition-all duration-300 bg-white border border-gray-300 text-gray-700 shadow-sm hover:border-emerald-300 hover:bg-emerald-50 hover:text-emerald-700 hover:shadow focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-300 active:scale-[0.98] disabled:opacity-50 disabled:pointer-events-none transform hover:-translate-y-0.5 focus:-translate-y-0.5']) }}
>
    <i class="fas fa-sync-alt h-4 w-4"></i>
    <span class="hidden sm:inline">{{ $text }}</span>
</button>