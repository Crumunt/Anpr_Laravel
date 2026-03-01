@props(['value', 'index', 'isBold', 'rowCount'])

<td class="px-4 py-4 align-middle {{ $isBold ? 'font-semibold text-gray-900' : 'text-gray-700' }}">
    @if(is_array($value) && array_key_exists('tooltip', $value))
        @php
            $pendingCount = $value['tooltip']['pending'] ?? 0;
            $hasPending = $pendingCount > 0;
        @endphp
        <div class="inline-flex items-center gap-2">
            <!-- Overview Text -->
            <span class="font-medium text-gray-900">
                {{ $value['overview'] }}
            </span>

            @if($hasPending)
            <!-- Pending Indicator Badge -->
            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200 animate-pulse" title="{{ $pendingCount }} pending application(s)">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                {{ $pendingCount }} pending
            </span>
            @endif
        </div>
    @elseif(is_array($value) && array_key_exists('badge_label', $value))
        <x-ui.badge :label="$value['badge_label']" />
    @elseif(filter_var($value, FILTER_VALIDATE_URL))
        <a href="{{ $value }}"
           target="_blank"
           class="text-green-600 hover:text-green-700 hover:underline inline-flex items-center gap-1">
            View
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
            </svg>
        </a>
    @elseif(is_bool($value))
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $value ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
            {{ $value ? 'Yes' : 'No' }}
        </span>
    @else
        <span class="line-clamp-2">{{ $value }}</span>
    @endif
</td>
