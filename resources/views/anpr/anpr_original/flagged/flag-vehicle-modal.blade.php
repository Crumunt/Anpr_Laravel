@props([
    'vehicleTypes' => ['Sedan', 'SUV', 'Truck', 'Van', 'Motorcycle', 'Other'],
    'flagReasons' => [
        ['value' => 'suspicious', 'label' => 'Suspicious Activity'],
        ['value' => 'unauthorized', 'label' => 'Unauthorized Access'],
        ['value' => 'expired', 'label' => 'Expired Registration'],
        ['value' => 'investigation', 'label' => 'Under Investigation'],
        ['value' => 'violation', 'label' => 'Parking Violation'],
        ['value' => 'other', 'label' => 'Other'],
    ],
    'priorityLevels' => [
        ['value' => 'high', 'label' => 'High'],
        ['value' => 'medium', 'label' => 'Medium'],
        ['value' => 'low', 'label' => 'Low'],
    ],
    'incidentLocations' => [
        'Main Entrance', 'Exit Gate', 'Parking Area', 'Back Gate',
        'Faculty Parking', 'Student Parking', 'Administration Building', 'Other'
    ],
    'notificationOptions' => [
        ['id' => 'notify-security', 'label' => 'Notify Security Team', 'checked' => true],
        ['id' => 'notify-admin', 'label' => 'Notify Administrators', 'checked' => true],
        ['id' => 'add-to-watchlist', 'label' => 'Add to Security Watchlist', 'checked' => true],
    ],
    'fieldRequirements' => [
        'license-plate' => true,
        'vehicle-model' => false,
        'vehicle-color' => false,
        'vehicle-type' => false,
        'flag-reason' => true,
        'priority-level' => true,
        'flag-description' => true,
        'incident-location' => true,
    ],
])
{{-- 
    Note: This component requires Alpine.js for drag-and-drop functionality and the following JavaScript functions in the host view:
    - closeFlagVehicleModal(): Closes the modal.
    - submitFlagVehicle(): Handles form submission.
    Ensure these functions are defined in the view's JavaScript or use Alpine.js for encapsulation.
--}}
<div id="flag-vehicle-modal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 hidden transition-opacity duration-300" role="dialog" aria-labelledby="flag-vehicle-modal-title" x-data="{ isDragging: false }" x-on:dragover.prevent="isDragging = true" x-on:dragleave.prevent="isDragging = false" x-on:drop.prevent="isDragging = false">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full mx-4 sm:mx-6 max-h-[90vh] overflow-y-auto animate-fade-in">
        <style>
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(20px) scale(0.98); }
                to { opacity: 1; transform: translateY(0) scale(1); }
            }
            .animate-fade-in {
                animation: fadeIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            }
        </style>
        <div class="flex justify-between items-center border-b border-gray-200 px-6 sm:px-8 py-4 sticky top-0 bg-white z-10">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 mr-4">
                    <i class="fas fa-flag text-xl" aria-hidden="true"></i>
                </div>
                <h3 id="flag-vehicle-modal-title" class="text-xl font-semibold text-gray-900">Flag a Vehicle</h3>
            </div>
            <button onclick="closeFlagVehicleModal()" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-amber-500 rounded transition-colors" aria-label="Close modal">
                <i class="fas fa-times text-lg" aria-hidden="true"></i>
            </button>
        </div>
        <div class="p-6 sm:p-8">
            <form id="flag-vehicle-form" class="space-y-6">
                <div>
                    <h4 class="text-sm font-medium text-gray-700 mb-4 uppercase tracking-wide">Vehicle Information</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="relative">
                            <label for="license-plate" class="block text-sm font-medium text-gray-700 mb-2">License Plate</label>
                            <div class="relative">
                                <i class="fas fa-car absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" aria-hidden="true"></i>
                                <input type="text" id="license-plate" class="pl-10 w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 invalid:border-red-500 invalid:ring-red-500 py-2 px-3 text-gray-700 transition-colors duration-200" placeholder="e.g. ABC-123" {{ $fieldRequirements['license-plate'] ? 'required' : '' }} aria-describedby="license-plate-error">
                            </div>
                        </div>
                        <div class="relative">
                            <label for="vehicle-model" class="block text-sm font-medium text-gray-700 mb-2">Make & Model</label>
                            <div class="relative">
                                <i class="fas fa-car-side absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" aria-hidden="true"></i>
                                <input type="text" id="vehicle-model" class="pl-10 w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 py-2 px-3 text-gray-700 transition-colors duration-200" placeholder="e.g. Toyota Camry" {{ $fieldRequirements['vehicle-model'] ? 'required' : '' }} aria-describedby="vehicle-model-error">
                            </div>
                        </div>
                        <div class="relative">
                            <label for="vehicle-color" class="block text-sm font-medium text-gray-700 mb-2">Color</label>
                            <div class="relative">
                                <i class="fas fa-palette absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" aria-hidden="true"></i>
                                <input type="text" id="vehicle-color" class="pl-10 w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 py-2 px-3 text-gray-700 transition-colors duration-200" placeholder="e.g. Silver" {{ $fieldRequirements['vehicle-color'] ? 'required' : '' }} aria-describedby="vehicle-color-error">
                            </div>
                        </div>
                        <div class="relative">
                            <label for="vehicle-type" class="block text-sm font-medium text-gray-700 mb-2">Vehicle Type</label>
                            <div class="relative">
                                <i class="fas fa-car absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" aria-hidden="true"></i>
                                <select id="vehicle-type" class="pl-10 w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 py-2 px-3 text-gray-700 transition-colors duration-200" {{ $fieldRequirements['vehicle-type'] ? 'required' : '' }} aria-describedby="vehicle-type-error">
                                    <option value="">Select type</option>
                                    @foreach($vehicleTypes as $type)
                                        <option value="{{ $type }}">{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="border-t border-gray-200 pt-6">
                    <h4 class="text-sm font-medium text-gray-700 mb-4 uppercase tracking-wide">Flag Details</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <div class="relative">
                            <label for="flag-reason" class="block text-sm font-medium text-gray-700 mb-2">Flag Reason</label>
                            <div class="relative">
                                <i class="fas fa-exclamation-circle absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" aria-hidden="true"></i>
                                <select id="flag-reason" class="pl-10 w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 py-2 px-3 text-gray-700 transition-colors duration-200" {{ $fieldRequirements['flag-reason'] ? 'required' : '' }} aria-describedby="flag-reason-error">
                                    <option value="">Select reason</option>
                                    @foreach($flagReasons as $reason)
                                        <option value="{{ $reason['value'] }}">{{ $reason['label'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="relative">
                            <label for="priority-level" class="block text-sm font-medium text-gray-700 mb-2">Priority Level</label>
                            <div class="relative">
                                <i class="fas fa-exclamation absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" aria-hidden="true"></i>
                                <select id="priority-level" class="pl-10 w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 py-2 px-3 text-gray-700 transition-colors duration-200" {{ $fieldRequirements['priority-level'] ? 'required' : '' }} aria-describedby="priority-level-error">
                                    <option value="">Select priority</option>
                                    @foreach($priorityLevels as $priority)
                                        <option value="{{ $priority['value'] }}">{{ $priority['label'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="flag-description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea id="flag-description" rows="5" class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 invalid:border-red-500 invalid:ring-red-500 py-2 px-3 text-gray-700 transition-colors duration-200 resize-none" placeholder="Provide details about why this vehicle is being flagged..." {{ $fieldRequirements['flag-description'] ? 'required' : '' }} aria-describedby="flag-description-error"></textarea>
                    </div>
                    <div class="relative">
                        <label for="incident-location" class="block text-sm font-medium text-gray-700 mb-2">Incident Location</label>
                        <div class="relative">
                            <i class="fas fa-map-marker-alt absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" aria-hidden="true"></i>
                            <select id="incident-location" class="pl-10 w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 py-2 px-3 text-gray-700 transition-colors duration-200" {{ $fieldRequirements['incident-location'] ? 'required' : '' }} aria-describedby="incident-location-error">
                                <option value="">Select location</option>
                                @foreach($incidentLocations as $location)
                                    <option value="{{ $location }}">{{ $location }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-700 mb-4 uppercase tracking-wide">Notification Options</h4>
                    <div class="space-y-3">
                        @foreach($notificationOptions as $option)
                            <label class="flex items-center">
                                <input type="checkbox" id="{{ $option['id'] }}" class="h-5 w-5 text-amber-600 focus:ring-amber-500 rounded transition-colors duration-200" {{ $option['checked'] ? 'checked' : '' }}>
                                <span class="ml-3 text-sm text-gray-700">{{ $option['label'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="border-t border-gray-200 pt-6">
                    <h4 class="text-sm font-medium text-gray-700 mb-4 uppercase tracking-wide">Evidence (Optional)</h4>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl transition-colors duration-200" :class="{ 'border-amber-500 bg-amber-50': isDragging }">
                        <div class="space-y-2 text-center">
                            <i class="fas fa-camera mx-auto h-12 w-12 text-gray-400" aria-hidden="true"></i>
                            <div class="flex text-sm text-gray-600 justify-center">
                                <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-amber-600 hover:text-amber-700 focus-within:outline-none focus-within:ring-2 focus-within:ring-amber-500 px-2 py-1">
                                    <span>Upload a file</span>
                                    <input id="file-upload" name="file-upload" type="file" class="sr-only" accept="image/png,image/jpeg,image/gif">
                                </label>
                                <p class="pl-1 self-center">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="border-t border-gray-200 px-6 sm:px-8 py-4 bg-gray-50 flex justify-end space-x-4 sticky bottom-0">
            <button class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-100 focus:ring-2 focus:ring-amber-500 transition-colors duration-200" onclick="closeFlagVehicleModal()">
                Cancel
            </button>
            <button class="px-4 py-2 bg-amber-600 text-white rounded-lg text-sm font-medium hover:bg-amber-700 focus:ring-2 focus:ring-amber-500 transition-colors duration-200 flex items-center" onclick="submitFlagVehicle()">
                <i class="fas fa-flag mr-2" aria-hidden="true"></i> Flag Vehicle
            </button>
        </div>
    </div>
</div>