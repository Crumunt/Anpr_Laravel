<!-- Assign Number Modal -->
<div
    x-data="{
            show: false,
            applicationId: '',
            vehicleNumber: '',
            closeModal() {
                this.show = false;
                this.vehicleNumber = '';
            }
        }"
    @open-assign-modal.window="
            applicationId = $event.detail.applicationId;
            vehicleNumber = '';
            setTimeout(() => { show = true; }, 150);
        "
    x-show="show"
    x-cloak
    x-trap.noescape="show"
    class="fixed inset-0 z-[9999] overflow-y-auto"
    aria-labelledby="modal-title"
    role="dialog"
    aria-modal="true"
    style="display: none;">
    <!-- Backdrop -->
    <div
        x-show="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity"></div>

    <!-- Modal Container -->
    <div class="flex min-h-full items-center justify-center p-4">
        <div
            x-show="show"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative w-full max-w-lg transform overflow-hidden rounded-xl bg-white shadow-2xl transition-all">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-white/20">
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-white" id="modal-title">
                            Assign Vehicle Number
                        </h3>
                    </div>
                    <button
                        @click.prevent.stop="closeModal()"
                        type="button"
                        class="rounded-lg p-1 text-white/80 hover:bg-white/20 hover:text-white transition-colors">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="px-6 py-5">
                <p class="text-sm text-gray-600 mb-4">
                    Enter the vehicle identification number to assign to this application.
                </p>

                <div>
                    <label for="vehicle-number" class="block text-sm font-medium text-gray-700 mb-2">
                        Vehicle Number <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input
                            type="text"
                            id="vehicle-number"
                            x-model="vehicleNumber"
                            placeholder="e.g., ABC-1234"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm px-4 py-2.5 transition-colors"
                            @keydown.enter.prevent="if (vehicleNumber.trim()) { $wire.assignVehicleNumber(applicationId, vehicleNumber); closeModal(); }">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12M8 12h12M8 17h12M3 7h.01M3 12h.01M3 17h.01" />
                            </svg>
                        </div>
                    </div>
                    <p class="mt-1.5 text-xs text-gray-500">
                        This will be permanently assigned to the applicant's vehicle.
                    </p>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3">
                <button
                    @click.prevent.stop="closeModal()"
                    type="button"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    Cancel
                </button>
                <button
                    @click.prevent.stop="if (vehicleNumber.trim()) { $wire.assignVehicleNumber(applicationId, vehicleNumber); closeModal(); }"
                    :disabled="!vehicleNumber.trim()"
                    type="button"
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                    Assign Number
                </button>
            </div>
        </div>
    </div>
</div>
