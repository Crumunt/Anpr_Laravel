<div
    x-data="{ show: false, message: '', type: 'info' }"
    @alert.window="
        message = $event.detail[0]?.message || $event.detail.message || 'Alert';
        type = $event.detail[0]?.type || $event.detail.type || 'info';
        show = true;
    "
    x-show="show"
    x-transition.opacity
    class="fixed inset-0 z-50 flex items-center justify-center"
    style="display: none;"
>
    <!-- Modal Card -->
    <div
        x-transition:enter="transform ease-out duration-300"
        x-transition:enter-start="scale-95 opacity-0"
        x-transition:enter-end="scale-100 opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="relative w-full max-w-lg bg-white rounded-xl shadow-xl"
    >
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">System Alert</h3>
            <p class="text-sm text-gray-700" x-text="message"></p>
            <div class="mt-6 flex justify-end">
                <button
                    type="button"
                    @click="show = false"
                    class="px-4 py-2 text-sm font-medium text-white bg-gray-900 rounded-md hover:bg-gray-800"
                >
                    Dismiss
                </button>
            </div>
        </div>
    </div>
</div>

