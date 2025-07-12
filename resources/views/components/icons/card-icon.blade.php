@props(['icon', 'color'])

@switch($icon)
    @case('users')
        <svg xmlns="http://www.w3.org/2000/svg"
            class="h-7 w-7 sm:h-8 sm:w-8 text-{{$color}}-600 transition-colors duration-300 group-hover:text-{{$color}}-700"
            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
            <circle cx="9" cy="7" r="4" />
            <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
        </svg>
        @break

    @case('rfid')
        <svg xmlns="http://www.w3.org/2000/svg"
            class="h-7 w-7 sm:h-8 sm:w-8 text-{{$color}}-600 transition-colors duration-300 group-hover:text-{{$color}}-700"
            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path
                d="M12.586 2.586A2 2 0 0 0 11.172 2H4a2 2 0 0 0-2 2v7.172a2 2 0 0 0 .586 1.414l8.704 8.704a2.426 2.426 0 0 0 3.42 0l6.58-6.58a2.426 2.426 0 0 0 0-3.42z" />
            <circle cx="7.5" cy="7.5" r=".5" fill="currentColor" />
        </svg>
        @break

    @case('car')
        <svg xmlns="http://www.w3.org/2000/svg"
            class="h-7 w-7 sm:h-8 sm:w-8 text-{{$color}}-600 transition-colors duration-300 group-hover:text-{{$color}}-700"
            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path
                d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2" />
            <circle cx="7" cy="17" r="2" />
            <path d="M9 17h6" />
            <circle cx="17" cy="17" r="2" />
        </svg>
        @break

    @case('approval')
        <svg xmlns="http://www.w3.org/2000/svg"
            class="h-7 w-7 sm:h-8 sm:w-8 text-{{$color}}-600 transition-colors duration-300 group-hover:text-{{ $color }}-700"
            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <circle cx="12" cy="12" r="10" />
            <polyline points="12 6 12 12 16 14" />
        </svg>
        @break

    <!-- IMPROVED: Admin icon - Total administrators -->
    @case('admin')
        <svg xmlns="http://www.w3.org/2000/svg"
            class="h-7 w-7 sm:h-8 sm:w-8 text-{{$color}}-600 transition-colors duration-300 group-hover:text-{{$color}}-700"
            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path
                d="M20 6v14a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h1V3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v2h1a1 1 0 0 1 1 1z" />
            <path d="M9 3v2h6V3H9z" />
            <circle cx="12" cy="11" r="3" />
            <path d="M17.5 18.5c-.83-1.87-3.17-3-5.5-3s-4.67 1.13-5.5 3" />
            <rect x="8" y="2" width="8" height="2" rx="1" ry="1" fill="currentColor" opacity="0.2" />
        </svg>
        @break


    <!-- IMPROVED: Check icon - Active admins -->
    @case('check')
        <svg xmlns="http://www.w3.org/2000/svg"
            class="h-7 w-7 sm:h-8 sm:w-8 text-{{$color}}-600 transition-colors duration-300 group-hover:text-{{$color}}-700"
            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <circle cx="12" cy="8" r="4" />
            <path d="M5 21v-2a4 4 0 0 1 4-4h6a4 4 0 0 1 4 4v2" />
            <circle cx="18" cy="12" r="3" fill="none" />
            <path d="M16.5 12l1 1 2-2" stroke-linecap="round" stroke-linejoin="round" />
            <path fill="currentColor" opacity="0.2" d="M18 9a3 3 0 1 0 0 6 3 3 0 0 0 0-6z" />
        </svg>
        @break

    <!-- IMPROVED: Ban icon - Inactive admins -->
    @case('ban')
        <svg xmlns="http://www.w3.org/2000/svg"
            class="h-7 w-7 sm:h-8 sm:w-8 text-{{$color}}-600 transition-colors duration-300 group-hover:text-{{$color}}-700"
            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <circle cx="12" cy="8" r="4" />
            <path d="M5 21v-2a4 4 0 0 1 4-4h6a4 4 0 0 1 4 4v2" />
            <circle cx="18" cy="12" r="3" fill="none" />
            <line x1="15.5" y1="12" x2="20.5" y2="12" stroke-linecap="round" />
            <path fill="currentColor" opacity="0.2" d="M18 9a3 3 0 1 0 0 6 3 3 0 0 0 0-6z" />
        </svg>
        @break


    <!-- IMPROVED: Shield icon - Super admin -->
    @case('shield')
        <svg xmlns="http://www.w3.org/2000/svg"
            class="h-7 w-7 sm:h-8 sm:w-8 text-{{$color}}-600 transition-colors duration-300 group-hover:text-{{$color}}-700"
            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <circle cx="12" cy="8" r="4" />
            <path d="M5 21v-2a4 4 0 0 1 4-4h6a4 4 0 0 1 4 4v2" />
            <path d="M12 3l5 2v4c0 3.09-2.66 6.69-5 8-2.34-1.31-5-4.91-5-8V5l5-2z" transform="translate(9, 7) scale(0.5)" />
            <path d="M9 12l2 2 4-4" transform="translate(7, 5) scale(0.5)" stroke-linecap="round" stroke-linejoin="round" />
            <path fill="currentColor" opacity="0.2" d="M12 3l5 2v4c0 3.09-2.66 6.69-5 8-2.34-1.31-5-4.91-5-8V5l5-2z"
                transform="translate(9, 7) scale(0.5)" />
        </svg>
        @break
@endswitch