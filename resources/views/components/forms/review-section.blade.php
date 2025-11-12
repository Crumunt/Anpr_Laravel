@props(['title'])

<div>
    <h4 class="font-medium text-green-700 mb-2">{{ $title }}</h4>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
        {{ $slot }}
    </div>
</div>