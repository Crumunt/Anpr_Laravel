<div id="vehicle-details-modal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 hidden transition-all duration-300 ease-out" role="dialog" aria-labelledby="vehicle-details-title">
    <div class="bg-white rounded-2xl shadow-2xl max-w-5xl w-full mx-4 sm:mx-6 max-h-[95vh] overflow-hidden transform transition-all duration-300 scale-95 opacity-0" id="vehicle-modal-content">
        <div class="relative bg-gradient-to-r from-green-50 via-emerald-50 to-green-50 border-b border-gray-200 px-6 py-5">
            <div class="absolute inset-0 bg-gradient-to-r from-green-100/20 to-emerald-100/20"></div>
            <div class="relative flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-100 to-emerald-200 flex items-center justify-center shadow-lg">
                            <i class="fas fa-car text-emerald-700 text-lg" aria-hidden="true"></i>
                        </div>
                    </div>
                    <div>
                        <h3 id="vehicle-details-title" class="text-xl font-bold text-gray-900">Vehicle Details</h3>
                        <p class="text-sm text-gray-600">
                            <span id="vdm-time">—</span>
                            <span class="text-gray-400">·</span>
                            <span id="vdm-time-ago">—</span>
                        </p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <button class="p-2 border border-gray-300 rounded-lg text-sm text-gray-700 bg-white hover:bg-gray-50 transition" aria-label="Close" onclick="closeVehicleDetails()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="overflow-y-auto p-6 max-h-[calc(95vh-140px)] scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
            <div class="space-y-6">
                <div class="flex justify-center">
                    <div class="relative">
                        <div class="relative bg-gradient-to-r from-yellow-400 to-yellow-500 border-4 border-black py-4 px-10 rounded-lg shadow-2xl">
                            <div class="absolute inset-0 bg-gradient-to-b from-yellow-300 to-yellow-600 rounded-lg opacity-80"></div>
                            <div class="relative z-10">
                                <span id="vdm-license-plate" class="text-3xl font-black tracking-widest text-black drop-shadow-lg">ABC-123</span>
                            </div>
                            <div class="absolute inset-0 border-2 border-yellow-200 rounded-lg opacity-50"></div>
                        </div>
                        <div class="absolute -top-2 -right-2">
                            <span id="vdm-status-badge" class="bg-gradient-to-r from-green-500 to-emerald-600 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg border-2 border-white">
                                <i class="fas fa-check-circle mr-1" aria-hidden="true"></i>
                                VALID
                            </span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <div class="space-y-6">
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                                <h4 class="text-lg font-semibold text-gray-900 flex items-center">
                                    <i class="fas fa-car text-emerald-600 mr-2"></i>
                                    Vehicle Information
                                </h4>
                            </div>
                            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Make & Model</p>
                                    <p id="vdm-make-model" class="text-sm font-semibold text-gray-900 mt-1">—</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Type</p>
                                    <p id="vdm-type" class="text-sm font-semibold text-gray-900 mt-1">—</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Color</p>
                                    <p id="vdm-color" class="text-sm font-semibold text-gray-900 mt-1">—</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Year</p>
                                    <p id="vdm-year" class="text-sm font-semibold text-gray-900 mt-1">—</p>
                                </div>
                                <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Location</p>
                                        <p id="vdm-location" class="text-sm font-semibold text-gray-900 mt-1">—</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Camera</p>
                                        <p id="vdm-camera" class="text-sm font-semibold text-gray-900 mt-1">—</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                                <h4 class="text-lg font-semibold text-gray-900 flex items-center">
                                    <i class="fas fa-user text-emerald-600 mr-2"></i>
                                    Owner Information
                                </h4>
                            </div>
                            <div class="p-6 space-y-4">
                                <div class="flex items-center justify-between bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-700">
                                            <i class="fas fa-id-card" aria-hidden="true"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-indigo-700">RFID Tag</p>
                                            <p id="vdm-rfid" class="text-sm font-semibold text-gray-900">—</p>
                                        </div>
                                    </div>
                                    <span class="bg-gradient-to-r from-green-500 to-emerald-600 text-white text-xs font-bold px-3 py-1 rounded-full">
                                        ACTIVE
                                    </span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                                        <i class="fas fa-user" aria-hidden="true"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p id="vdm-owner-name" class="text-base font-medium">—</p>
                                        <p id="vdm-owner-role" class="text-sm text-gray-500">—</p>
                                        <p id="vdm-owner-id" class="text-xs text-gray-500">—</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                </div>
            </div>
        </div>

        <div class="border-t border-gray-200 px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 flex justify-between">
            <div class="text-xs text-gray-500">Press Esc to close</div>
            <div class="flex space-x-2">
                <button class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200" onclick="closeVehicleDetails()">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

