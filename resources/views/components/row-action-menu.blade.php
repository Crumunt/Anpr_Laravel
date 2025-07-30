<!-- Nothing in life is to be feared, it is only to be understood. Now is the time to understand more, so that we may fear less. - Marie Curie -->

@props(['index', 'uid'])

<div class="row-action-wrapper flex justify-end">
    <button @click.stop="toggleActionMenu($event, {{ $uid }})"
        class="row-action-button inline-flex items-center justify-center rounded-full p-1.5 transition-all duration-300 hover:bg-green-100 hover:text-green-700 focus:outline-none focus:ring-2 focus:ring-green-500/50"
        id="apid-{{ $uid }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2">
            <circle cx="12" cy="5" r="1" />
            <circle cx="12" cy="12" r="1" />
            <circle cx="12" cy="19" r="1" />
        </svg>
    </button>
</div>