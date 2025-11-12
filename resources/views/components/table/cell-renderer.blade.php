<!-- It is never too late to be what you might have been. - George Eliot -->
@if ($badge)
    <x-badge :label="$badge['label']" />
@else
    {{ $value }}
@endif