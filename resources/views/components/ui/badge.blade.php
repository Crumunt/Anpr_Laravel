@props([
    'badge',
])

<div class="inline-flex items-center rounded-full px-3 py-1.5 text-xs font-medium transition-all duration-300 transform hover:scale-[1.02] shadow-md {{ $badge['badge_class'] }}">
    {!! $badge['badge_label'] !!}
</div>
