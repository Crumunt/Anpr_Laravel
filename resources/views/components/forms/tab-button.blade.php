@props(['active' => false, 'icon', 'label'])


<button type="button" 
    class="inline-flex items-center justify-center gap-1 px-3 py-1.5 text-sm font-medium transition-all rounded-md whitespace-nowrap"
    :class="$active
        ? 'bg-green-100 text-green-900' 
        : 'text-muted-foreground hover:bg-green-50 hover:text-green-900'"
    role="tab"
    :aria-selected="$active ? 'true' : 'false'"
    {{$attributes}}>
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-{{ $icon }} h-4 w-4">
        @switch($icon)
            @case('user')
                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
                @break
            @case('car')
                <path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"></path>
                <circle cx="7" cy="17" r="2"></circle>
                <path d="M9 17h6"></path>
                <circle cx="17" cy="17" r="2"></circle>
                @break
            @case('file-check')
                <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path>
                <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
                <path d="m9 15 2 2 4-4"></path>
                @break
            @case('arrow-right')
                <path d="M5 12h14"></path>
                <path d="m12 5 7 7-7 7"></path>
                @break
        @endswitch
    </svg>
    <span class="hidden sm:inline">{{ $label }}</span>
</button>