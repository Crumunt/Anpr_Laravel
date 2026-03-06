<div x-data="{ show: false }"
     @open-modal.window="if ($event.detail === 'send-email') { show = true; $nextTick(() => $refs.subject?.focus()); }"
     @close-modal.window="if ($event.detail === 'send-email') { show = false; $wire.resetEmailForm(); }"
     @keydown.escape.window="if (show) { show = false; $wire.resetEmailForm(); }">
    <!-- Trigger button -->
    <button
        type="button"
        @click="$dispatch('open-modal', 'send-email')"
        class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-150 whitespace-nowrap"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
        </svg>
        <span>Send Email</span>
    </button>

    <!-- Email Modal - Teleported to body -->
    <template x-teleport="body">
        <div
            x-show="show"
            x-cloak
            class="fixed inset-0 z-[9999] overflow-y-auto"
            aria-labelledby="send-email-title"
            role="dialog"
            aria-modal="true"
            style="display: none;"
        >
            <!-- Background overlay -->
            <div
                x-show="show"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"
                @click="if (!$wire.sending) { show = false; $wire.resetEmailForm(); }"
            ></div>

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
                class="relative w-full max-w-xl transform overflow-hidden rounded-2xl bg-white shadow-2xl ring-1 ring-black/5"
                @click.stop
            >
                <!-- Modal Header -->
                <div class="flex items-start justify-between px-6 py-4 border-b border-gray-100 bg-gray-50/80">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-green-100 text-green-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </span>
                            <div>
                                <h3 id="send-email-title" class="text-base font-semibold text-gray-900">
                                    Send Email to Applicant
                                </h3>
                                <p class="mt-0.5 text-xs text-gray-500">
                                    Compose and send a message directly to the applicant. All required fields are marked with
                                    <span class="text-red-500 font-medium">*</span>.
                                </p>
                            </div>
                        </div>
                    </div>
                    <button
                        type="button"
                        @click="show = false; $wire.resetEmailForm();"
                        :disabled="$wire.sending"
                        class="ml-3 inline-flex items-center justify-center rounded-full p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition disabled:cursor-not-allowed disabled:opacity-50"
                        aria-label="Close"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <form wire:submit.prevent="sendEmail" class="px-6 py-5 space-y-5">
                    <!-- Recipient (Read-only) -->
                    <div class="space-y-1.5">
                        <label for="recipient" class="block text-sm font-medium text-gray-700">
                            Recipient
                        </label>
                        <!-- Hidden input for Livewire validation -->
                        <input type="hidden" wire:model="emailRecipient" value="{{ $applicantEmail }}">
                        <div class="relative">
                            <input
                                type="email"
                                id="recipient"
                                value="{{ $applicantEmail }}"
                                readonly
                                disabled
                                class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 text-sm text-gray-700 shadow-sm cursor-not-allowed"
                            >
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">
                            Email will be sent to the applicant shown on this page.
                        </p>
                    </div>

                    <!-- Subject -->
                    <div class="space-y-1.5">
                        <label for="subject" class="block text-sm font-medium text-gray-700">
                            Subject <span class="text-red-500">*</span>
                        </label>
                        <input
                            x-ref="subject"
                            type="text"
                            id="subject"
                            wire:model.defer="emailSubject"
                            required
                            placeholder="e.g. Your Vehicle Access Application Status"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500/70 disabled:cursor-not-allowed disabled:bg-gray-50"
                            :disabled="$wire.sending"
                            maxlength="255"
                        >
                        <div class="flex items-center justify-between">
                            @error('emailSubject')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @else
                                <p class="mt-1 text-xs text-gray-400">Max 255 characters.</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Message -->
                    <div class="space-y-1.5">
                        <label for="message" class="block text-sm font-medium text-gray-700">
                            Message <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            id="message"
                            wire:model.defer="emailMessage"
                            required
                            rows="6"
                            placeholder="Type your message here. You can include details about the application status, next steps, or any additional instructions."
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500/70 disabled:cursor-not-allowed disabled:bg-gray-50 resize-y min-h-[140px]"
                            :disabled="$wire.sending"
                            maxlength="5000"
                        ></textarea>
                        <div class="flex items-center justify-between">
                            @error('emailMessage')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @else
                                <p class="mt-1 text-xs text-gray-400">You can write up to 5,000 characters.</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <p class="text-xs text-gray-400">
                            Emails are sent using the system default mail configuration.
                        </p>
                        <div class="flex items-center gap-3">
                            <button
                                type="button"
                                @click="show = false; $wire.resetEmailForm();"
                                :disabled="$wire.sending"
                                class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                wire:loading.attr="disabled"
                                :disabled="$wire.sending"
                                class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-70 transition-all duration-200"
                            >
                                <svg
                                    wire:loading
                                    wire:target="sendEmail"
                                    class="h-4 w-4 animate-spin"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path
                                        class="opacity-75"
                                        fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                    ></path>
                                </svg>
                                <span wire:loading.remove wire:target="sendEmail">Send Email</span>
                                <span wire:loading wire:target="sendEmail">Sending…</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </template>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</div>
