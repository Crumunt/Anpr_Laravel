<!-- It is never too late to be what you might have been. - George Eliot -->
@if ($badge)
    <x-badge :type="$badge['type']" :label="$badge['label']" />
@else
    {{ $value }}
@endif