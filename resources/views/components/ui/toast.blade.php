<div
    x-data="{
        show: false,
        message: '',
        type: 'info',
        timeout: null,
        duration: 1000,
        progress: 100,
        progressInterval: null
    }"
    @toast.window="
        message = $event.detail[0]?.message || $event.detail.message || 'Notification';
        type = $event.detail[0]?.type || $event.detail.type || 'info';
        duration = $event.detail[0]?.duration || $event.detail.duration || 1000;
        show = true;
        progress = 100;

        // Clear existing timers
        if (timeout) clearTimeout(timeout);
        if (progressInterval) clearInterval(progressInterval);

        // Auto-hide timeout
        timeout = setTimeout(() => { show = false }, duration);

        // Progress bar animation (updates every 10ms for smooth animation)
        const step = (10 / duration) * 100;
        progressInterval = setInterval(() => {
            progress -= step;
            if (progress <= 0) {
                progress = 0;
                clearInterval(progressInterval);
            }
        }, 10);
    "
    x-show="show"
    x-transition:enter="transform ease-out duration-300 transition"
    x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
    x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
    x-transition:leave="transition ease-in duration-100"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed top-4 right-4 z-50 max-w-sm w-full sm:w-96"
    style="display: none;"
>
    <div class="bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden">
        <div class="p-4">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <!-- Success Icon -->
                    <svg x-show="type === 'success'" class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <!-- Warning Icon -->
                    <svg x-show="type === 'warning'" class="h-6 w-6 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <!-- Error Icon -->
                    <svg x-show="type === 'error'" class="h-6 w-6 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <!-- Info Icon -->
                    <svg x-show="type === 'info'" class="h-6 w-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3 w-0 flex-1">
                    <p class="text-sm font-medium text-gray-900" x-text="message"></p>
                </div>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="h-1 bg-gray-200">
            <div
                class="h-full transition-all ease-linear"
                :class="{
                    'bg-green-500': type === 'success',
                    'bg-yellow-500': type === 'warning',
                    'bg-red-500': type === 'error',
                    'bg-blue-500': type === 'info'
                }"
                :style="`width: ${progress}%`"
            ></div>
        </div>
    </div>
</div>
