@php
    $typeClasses = [
        'success' => [
            'bg' => 'bg-green-100 dark:bg-green-800',
            'text' => 'text-green-500 dark:text-green-200',
            'icon' => '<path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>'
        ],
        'error' => [
            'bg' => 'bg-red-100 dark:bg-red-800',
            'text' => 'text-red-500 dark:text-red-200',
            'icon' => '<path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414-1.414L10 8.586l1.793-1.793a1 1 0 0 1 1.414 1.414L11.414 10l1.793 1.793Z"/>'
        ],
        'warning' => [
            'bg' => 'bg-yellow-100 dark:bg-yellow-800',
            'text' => 'text-yellow-500 dark:text-yellow-200',
            'icon' => '<path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 0 1 1 2.817V9a1.5 1.5 0 0 1-3 0V6.817A1.5 1.5 0 0 1 9.5 4Zm.5 10a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3Z"/>'
        ],
        'info' => [
            'bg' => 'bg-blue-100 dark:bg-blue-800',
            'text' => 'text-blue-500 dark:text-blue-200',
            'icon' => '<path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 0 1 1 2.817V9a1.5 1.5 0 0 1-3 0V6.817A1.5 1.5 0 0 1 9.5 4Zm.5 10a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3Z"/>'
        ]
    ];

    $currentType = $typeClasses[$type] ?? $typeClasses['info'];
    $toastId = 'toast-' . $type . '-' . uniqid();
@endphp
<div class="toast-container fixed top-4 right-4 z-50 flex flex-col items-end space-y-4">
    <!-- Toast -->
    <div id="{{$toastId}}"
        class="flex items-center w-full max-w-xs p-4 text-gray-500 {{ $currentType['text'] }} {{ $currentType['bg'] }} rounded-lg shadow-sm"
        role="alert">
        <div
            class="inline-flex items-center justify-center shrink-0 w-8 h-8 {{ $currentType['text'] }} {{ $currentType['bg'] }} rounded-lg">
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                viewBox="0 0 20 20">
                {!! $currentType['icon'] !!}
            </svg>
            <span class="sr-only">{{ ucfirst($type) }} icon</span>
        </div>
        <div class="ms-3 text-sm font-normal">{{ $message }}</div>
    </div>
</div>