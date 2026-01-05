@props([
    'alertTypes' => [
        ['value' => 'unauthorized', 'label' => 'Unauthorized Vehicle'],
        ['value' => 'suspicious', 'label' => 'Suspicious Activity'],
        ['value' => 'system', 'label' => 'System Error'],
        ['value' => 'rfid', 'label' => 'RFID Mismatch'],
        ['value' => 'maintenance', 'label' => 'Maintenance'],
    ],
    'priorityLevels' => [
        ['value' => 'critical', 'label' => 'Critical'],
        ['value' => 'high', 'label' => 'High'],
        ['value' => 'medium', 'label' => 'Medium'],
        ['value' => 'low', 'label' => 'Low'],
    ],
    'locations' => [
        ['value' => 'Main Entrance', 'label' => 'Main Entrance (Camera #1)'],
        ['value' => 'Exit Gate', 'label' => 'Exit Gate (Camera #2)'],
        ['value' => 'Parking Area', 'label' => 'Parking Area (Camera #3)'],
        ['value' => 'Back Gate', 'label' => 'Back Gate (Camera #4)'],
        ['value' => 'System-wide', 'label' => 'System-wide'],
    ],
    'vehicleTypes' => [
        'Sedan', 'SUV', 'Truck', 'Van', 'Motorcycle', 'Other'
    ],
    'assignToOptions' => [
        ['value' => '', 'label' => 'Unassigned'],
        ['value' => 'Security Team', 'label' => 'Security Team'],
        ['value' => 'Maintenance Team', 'label' => 'Maintenance Team'],
        ['value' => 'IT Support', 'label' => 'IT Support'],
        ['value' => 'Admin Staff', 'label' => 'Admin Staff'],
        ['value' => 'John Doe', 'label' => 'John Doe'],
        ['value' => 'Sarah Williams', 'label' => 'Sarah Williams'],
    ],
])
<div id="add-alert-modal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 hidden" role="dialog" aria-labelledby="add-alert-modal-title">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full mx-4 sm:mx-6 lg:mx-8 animate-fade-in">
        <style>
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-fade-in {
                animation: fadeIn 0.4s ease-out;
            }
        </style>
        <div class="flex justify-between items-center border-b border-gray-200 px-6 py-4">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center text-green-600 mr-4">
                    <i class="fas fa-plus text-xl" aria-hidden="true"></i>
                </div>
                <h3 id="add-alert-modal-title" class="text-xl font-semibold text-gray-900">Create New Alert</h3>
            </div>
            <button onclick="closeAddAlertModal()" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-500 rounded transition-colors" aria-label="Close modal">
                <i class="fas fa-times text-lg" aria-hidden="true"></i>
            </button>
        </div>
        <div class="p-6 sm:p-8">
            <form id="add-alert-form" class="space-y-6">
                <!-- Alert Type -->
                <div>
                    <label for="alert-type" class="block text-sm font-medium text-gray-700 mb-2">Alert Type</label>
                    <div class="relative">
                        <i class="fas fa-exclamation-circle absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" aria-hidden="true"></i>
                        <select id="alert-type" class="pl-10 w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 py-2 px-3 text-gray-700 transition-colors duration-200" required aria-describedby="alert-type-error">
                            <option value="">Select alert type</option>
                            @foreach($alertTypes as $type)
                                <option value="{{ $type['value'] }}">{{ $type['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <!-- Priority Level -->
                <div>
                    <label for="priority-level" class="block text-sm font-medium text-gray-700 mb-2">Priority Level</label>
                    <div class="relative">
                        <i class="fas fa-exclamation absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" aria-hidden="true"></i>
                        <select id="priority-level" class="pl-10 w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 py-2 px-3 text-gray-700 transition-colors duration-200" required aria-describedby="priority-level-error">
                            <option value="">Select priority</option>
                            @foreach($priorityLevels as $priority)
                                <option value="{{ $priority['value'] }}">{{ $priority['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <!-- Location -->
                <div>
                    <label for="alert-location" class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                    <div class="relative">
                        <i class="fas fa-map-marker-alt absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" aria-hidden="true"></i>
                        <select id="alert-location" class="pl-10 w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 py-2 px-3 text-gray-700 transition-colors duration-200" required aria-describedby="alert-location-error">
                            <option value="">Select location</option>
                            @foreach($locations as $location)
                                <option value="{{ $location['value'] }}">{{ $location['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <!-- Vehicle Information (conditional) -->
                <div id="vehicle-info-section" class="border-t border-gray-200 pt-6 hidden">
                    <h4 class="text-sm font-medium text-gray-700 mb-4 uppercase tracking-wide">Vehicle Information</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="relative">
                            <label for="license-plate" class="block text-sm font-medium text-gray-700 mb-2">License Plate</label>
                            <div class="relative">
                                <i class="fas fa-car absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" aria-hidden="true"></i>
                                <input type="text" id="license-plate" class="pl-10 w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 py-2 px-3 text-gray-700 transition-colors duration-200" placeholder="e.g. ABC-123">
                            </div>
                        </div>
                        <div class="relative">
                            <label for="vehicle-model" class="block text-sm font-medium text-gray-700 mb-2">Make & Model</label>
                            <div class="relative">
                                <i class="fas fa-car-side absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" aria-hidden="true"></i>
                                <input type="text" id="vehicle-model" class="pl-10 w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 py-2 px-3 text-gray-700 transition-colors duration-200" placeholder="e.g. Toyota Camry">
                            </div>
                        </div>
                        <div class="relative">
                            <label for="vehicle-color" class="block text-sm font-medium text-gray-700 mb-2">Color</label>
                            <div class="relative">
                                <i class="fas fa-palette absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" aria-hidden="true"></i>
                                <input type="text" id="vehicle-color" class="pl-10 w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 py-2 px-3 text-gray-700 transition-colors duration-200" placeholder="e.g. Silver">
                            </div>
                        </div>
                        <div class="relative">
                            <label for="vehicle-type" class="block text-sm font-medium text-gray-700 mb-2">Vehicle Type</label>
                            <div class="relative">
                                <i class="fas fa-car absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" aria-hidden="true"></i>
                                <select id="vehicle-type" class="pl-10 w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 py-2 px-3 text-gray-700 transition-colors duration-200">
                                    <option value="">Select type</option>
                                    @foreach($vehicleTypes as $type)
                                        <option value="{{ $type }}">{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Alert Description -->
                <div>
                    <label for="alert-description" class="block text-sm font-medium text-gray-700 mb-2">Alert Description</label>
                    <textarea id="alert-description" rows="5" class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 invalid:border-red-500 invalid:ring-red-500 py-2 px-3 text-gray-700 transition-colors duration-200 resize-none" placeholder="Provide a detailed description of the alert..." required aria-describedby="alert-description-error"></textarea>
                </div>
                <!-- Assign To -->
                <div>
                    <label for="assign-to" class="block text-sm font-medium text-gray-700 mb-2">Assign To</label>
                    <div class="relative">
                        <i class="fas fa-user absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" aria-hidden="true"></i>
                        <select id="assign-to" class="pl-10 w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 py-2 px-3 text-gray-700 transition-colors duration-200">
                            @foreach($assignToOptions as $option)
                                <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <!-- Notification Options -->
                <div>
                    <h4 class="text-sm font-medium text-gray-700 mb-3 uppercase tracking-wide">Notification Options</h4>
                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="checkbox" id="notify-security" class="h-5 w-5 text-green-600 focus:ring-green-500 rounded transition-colors duration-200" checked>
                            <span class="ml-3 text-sm text-gray-700">Notify Security Team</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" id="notify-admin" class="h-5 w-5 text-green-600 focus:ring-green-500 rounded transition-colors duration-200" checked>
                            <span class="ml-3 text-sm text-gray-700">Notify Administrators</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" id="notify-email" class="h-5 w-5 text-green-600 focus:ring-green-500 rounded transition-colors duration-200">
                            <span class="ml-3 text-sm text-gray-700">Send Email Notification</span>
                        </label>
                    </div>
                </div>
            </form>
        </div>
        <div class="border-t border-gray-200 px-6 sm:px-8 py-4 bg-gray-50 flex justify-end space-x-4 sticky bottom-0">
            <button class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-100 focus:ring-2 focus:ring-green-500 transition-colors duration-200" onclick="closeAddAlertModal()">
                Cancel
            </button>
            <button class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 focus:ring-2 focus:ring-green-500 transition-colors duration-200 flex items-center" onclick="submitNewAlert()">
                <i class="fas fa-plus mr-2" aria-hidden="true"></i> Create Alert
            </button>
        </div>
    </div>
</div>