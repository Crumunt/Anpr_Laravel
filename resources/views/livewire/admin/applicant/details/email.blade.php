<div>
    <!-- Button with Alpine.js click handler -->
    <!-- Button -->
    <button
        @click="$dispatch('open-modal', 'send-email')"
        class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-150 whitespace-nowrap">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
        </svg>
        Send Email
    </button>

    <!-- Email Modal -->
    <div
        x-data="{ show: false }"
        @open-modal.window="if ($event.detail === 'send-email') show = true"
        @close-modal.window="if ($event.detail === 'send-email') { show = false; $wire.resetEmailForm(); }"
        @keydown.escape.window="if (show) { show = false; $wire.resetEmailForm(); }"
        x-show="show"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title"
        role="dialog"
        aria-modal="true"
        style="display: none;">

        <!-- Background overlay -->
        <div
            x-show="show"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
            @click="show = false; $wire.resetEmailForm();"></div>

        <!-- Modal panel -->
        <div class="flex min-h-full items-center justify-center p-4">
            <div
                x-show="show"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative bg-white rounded-lg shadow-xl w-full max-w-lg overflow-hidden"
                @click.stop>

                <!-- Modal Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900" id="modal-title">
                        Send Email
                    </h3>
                    <button
                        type="button"
                        @click="show = false; $wire.resetEmailForm();"
                        class="text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 rounded-lg p-1 transition-colors">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <form wire:submit="sendEmail" class="px-6 py-4 space-y-4">
                    <!-- Recipient -->
                    <div>
                        <label for="recipient" class="block text-sm font-medium text-gray-700 mb-1">
                            Recipient <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="email"
                            id="recipient"
                            wire:model="emailRecipient"
                            required
                            placeholder="recipient@example.com"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                            :disabled="$wire.sending">
                        @error('emailRecipient')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Subject -->
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">
                            Subject <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="subject"
                            wire:model="emailSubject"
                            required
                            placeholder="Email subject"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                            :disabled="$wire.sending">
                        @error('emailSubject')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Message -->
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-1">
                            Message <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            id="message"
                            wire:model="emailMessage"
                            required
                            rows="5"
                            placeholder="Type your message here..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all resize-none"
                            :disabled="$wire.sending"></textarea>
                        @error('emailMessage')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex items-center justify-end gap-3 pt-4">
                        <button
                            type="button"
                            @click="show = false; $wire.resetEmailForm();"
                            :disabled="$wire.sending"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                            Cancel
                        </button>
                        <button
                            type="submit"
                            :disabled="$wire.sending"
                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg x-show="$wire.sending" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span x-text="$wire.sending ? 'Sending...' : 'Send Email'">Send Email</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</div>
