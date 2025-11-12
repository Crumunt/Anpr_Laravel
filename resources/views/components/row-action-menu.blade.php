<!-- Nothing in life is to be feared, it is only to be understood. Now is the time to understand more, so that we may fear less. - Marie Curie -->

@props(['index', 'uid', 'type' => 'applicant'])

<div class="row-action-wrapper flex justify-end">
    @if ($type === 'applicant')
        <div class="flex items-center gap-2">
            <button @click.stop="handleRowAction('view', '{{ $uid }}', $event)"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-200 rounded-md hover:bg-green-50 hover:text-green-700 focus:outline-none focus:ring-2 focus:ring-green-500/30">
                <x-icons.view />
                View
            </button>
            <button @click.stop="handleRowAction('approve', '{{ $uid }}', $event)"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-green-700 bg-white border border-green-200 rounded-md hover:bg-green-50 hover:text-green-800 focus:outline-none focus:ring-2 focus:ring-green-500/30">
                <x-icons.approve />
                Approve
            </button>
            <button @click.stop="handleRowAction('delete', '{{ $uid }}', $event)"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-red-600 bg-white border border-red-200 rounded-md hover:bg-red-50 hover:text-red-700 focus:outline-none focus:ring-2 focus:ring-red-500/30">
                <x-icons.delete />
                Delete
            </button>
        </div>
    @else
        <button @click.stop="toggleActionMenu($event, '{{ $uid }}')"
            class="row-action-button inline-flex items-center justify-center rounded-full p-1.5 transition-all duration-300 hover:bg-green-100 hover:text-green-700 focus:outline-none focus:ring-2 focus:ring-green-500/50"
            id="apid-{{ $uid }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2">
                <circle cx="12" cy="5" r="1" />
                <circle cx="12" cy="12" r="1" />
                <circle cx="12" cy="19" r="1" />
            </svg>
        </button>
    @endif
</div>