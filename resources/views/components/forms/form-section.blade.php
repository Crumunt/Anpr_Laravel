@props(['title'])

<div class="rounded-xl border bg-card text-card-foreground shadow p-6 transition-all duration-300 hover:shadow-md">
    <h3 class="text-lg font-medium mb-4">{{ $title }}</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{ $slot }}
    </div>
</div>