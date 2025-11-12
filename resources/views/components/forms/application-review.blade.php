@props(['title' => 'Review Your Application'])

<div class="rounded-xl border bg-card text-card-foreground shadow p-6 transition-all duration-300 hover:shadow-md">
    <h3 class="text-lg font-medium mb-4">{{ $title }}</h3>
    
    <div class="space-y-6">
        {{ $slot }}
    </div>
</div>