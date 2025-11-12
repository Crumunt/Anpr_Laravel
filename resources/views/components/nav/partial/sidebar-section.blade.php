<!-- /resources/views/components/nav-section.blade.php -->
@props(['title' => ''])
<div class="my-3 first:mt-0">
    @if($title)
        <h3 class="section-title px-4 text-xs font-semibold text-white/50 uppercase tracking-wider mb-2 ml-1">
            {{ $title }}
        </h3>
    @endif
    <div class="space-y-1">
        {{ $slot }}
    </div>

    <div class="my-3 h-px bg-white/10"></div>
</div>