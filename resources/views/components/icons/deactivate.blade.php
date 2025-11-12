<!-- No surplus words or unnecessary actions. - Marcus Aurelius -->
@props(['variant' => 'default'])

<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 @if ($variant === 'bulk')
    text-orange-600
@endif
    " viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
    stroke-linejoin="round">
    <circle cx="12" cy="12" r="10"></circle>
    <line x1="8" y1="12" x2="16" y2="12"></line>
</svg>