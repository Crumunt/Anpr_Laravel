@props([
    'backRoute' => '#',
    'backText' => 'Back',
    'submitText' => 'Submit',
    'confirmMessage' => 'Are you sure you want to submit your application?',
    'successRoute' => '#'
])

<div class="flex justify-between">
    <button 
        type="button"
        class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium 
               focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring 
               disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm 
               hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 transition-all duration-300 
               transform hover:-translate-x-1"
        onclick="window.location.href='{{ $backRoute }}';"
    >
        {{ $backText }}
    </button>

    <button 
        type="button"
        class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium 
               focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring 
               disabled:pointer-events-none disabled:opacity-50 text-white shadow 
               h-9 px-4 py-2 bg-green-700 hover:bg-green-800 transition-all duration-300 
               transform hover:translate-x-1"
        onclick="confirmSubmit('{{ $confirmMessage }}', '{{ $successRoute }}');"
    >
        {{ $submitText }}
    </button>
</div>

@once
    @push('scripts')
    <script>
        function confirmSubmit(message, redirectUrl) {
            if (confirm(message)) {
                window.location.href = redirectUrl;
            }
        }
    </script>
    @endpush
@endonce