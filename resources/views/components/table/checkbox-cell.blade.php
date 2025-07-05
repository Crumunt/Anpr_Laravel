<!-- Simplicity is an acquired taste. - Katharine Gerould -->

@props(['index' => '', 'isSelected' => '', 'variant' => 'default'])
@if ($variant === 'default')
    <div class="checkbox-wrapper">
        <button type="button" @click.stop="toggleCheckbox({{ $index }}, $event)"
            class="relative inline-flex h-4 w-4 shrink-0 rounded-sm border border-gray-300 transition-all duration-200 hover:border-green-500 hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-green-500/50"
            :class="{ 'bg-green-50 border-green-500': isSelected({{ $index }}) }">
            <span class="absolute inset-0 m-auto transition-opacity"
                :class="{ 'opacity-100': {{$isSelected}}({{ $index }}), 'opacity-0': !{{$isSelected}}({{ $index }}) }">
                <svg class="h-3.5 w-3.5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
            </span>
        </button>
    </div>
@else
    <button type="button" @click.stop="toggleSelectAll($event)"
        class="relative inline-flex h-4 w-4 shrink-0 rounded-sm border border-gray-300 transition-all duration-200 hover:border-green-500 hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-green-500/50"
        :class="{ 'bg-green-50 border-green-500': selectAll }">
        <span class="absolute inset-0 m-auto transition-opacity"
            :class="{ 'opacity-100': selectAll, 'opacity-0': !selectAll }">
            <svg class="h-3.5 w-3.5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
            </svg>
        </span>
    </button>
@endif