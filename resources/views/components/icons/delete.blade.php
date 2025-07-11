@props(['variant' => 'default'])

<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 @if ($variant === 'bulk')
    text-red-600
@endif 
    " viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
    stroke-linejoin="round">
    <path d="M3 6h18"></path>
    <path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6"></path>
    <path d="M8 6V4a2 2 0 012-2h4a2 2 0 012 2v2"></path>
    <line x1="10" y1="11" x2="10" y2="17"></line>
    <line x1="14" y1="11" x2="14" y1="17"></line>
</svg>