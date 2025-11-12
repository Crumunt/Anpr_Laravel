@props(['prevRoute' => null, 'prevText' => 'Back', 'nextRoute' => null, 'nextText' => 'Next'])

<div class="flex justify-between">
    @if ($prevRoute)
        <a 
            href="{{ $prevRoute }}"
            class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium 
                focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring 
                disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm 
                hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 transition-all duration-300 
                transform hover:-translate-x-1"
        >
            {{ $prevText }}
        </a>
    @else
        <div></div>
    @endif

    @if ($nextRoute)
        <a 
            href="{{ $nextRoute }}"
            class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium 
                focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring 
                disabled:pointer-events-none disabled:opacity-50 text-white shadow 
                h-9 px-4 py-2 bg-green-700 hover:bg-green-800 transition-all duration-300 
                transform hover:translate-x-1"
        >
            {{ $nextText }}
        </a>
    @endif
</div>