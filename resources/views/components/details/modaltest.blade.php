@props([   'id' => 'entity-modal',
    'type' => 'applicant', // Options: applicant, vehicle, rfid, admin, document_upload
    'maxWidth' => '2xl',
    'entityId' => null, // For document upload modal
])
@php
$maxWidth = match($maxWidth) {
    'sm' => 'max-w-sm',
    'md' => 'max-w-md',
    'lg' => 'max-w-lg',
    'xl' => 'max-w-xl',
    '2xl' => 'max-w-2xl',
    'full' => 'max-w-full',
    default => 'max-w-md'
};
$titles = [
    'applicant' => 'Add Applicant',
    'vehicle' => 'Add Vehicle',
    'rfid' => 'Add RFID Tag',
    'admin' => 'Add Administrator',
    'document_upload' => 'Upload Document',
];
$title = $titles[$type] ?? 'Add Entry';
$formId = "{$type}-form";
@endphp
<div
    id="{{ $id }}"
    class="fixed inset-0 z-50 hidden"
    role="dialog"
    aria-modal="true"
    aria-labelledby="{{ $id }}-title"
    x-data="entityFormHandler('{{ $type }}', '{{ $entityId }}')"
>
    <div class="fixed inset-0 transition-opacity" aria-hidden="true">
        <div class="absolute inset-0 bg-gray-800 bg-opacity-70 backdrop-blur-sm transition-opacity duration-300 ease-in-out opacity-0 modal-backdrop"></div>
    </div>
    <div class="flex min-h-screen items-center justify-center p-4">
        <div
            class="bg-white rounded-xl shadow-2xl overflow-hidden transform transition-all scale-95 opacity-0 duration-300 ease-out {{ $maxWidth }} w-full modal-content border border-gray-100"
        >
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h2 id="{{ $id }}-title" class="text-xl font-semibold text-gray-800">{{ $title }}</h2>
                <button
                    class="text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-all duration-150 ease-in-out close-modal-btn focus:outline-none focus:ring-2 focus:ring-green-500 rounded-full p-2"
                    data-modal="{{ $id }}"
                    aria-label="Close"
                >
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="p-6 max-h-[70vh] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
                @if ($type === 'document_upload')
                    <div class="space-y-6">
                        <!-- File Upload Area -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Upload Document</label>
                            <div 
                                x-show="!filePreview"
                                class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-green-500 transition-colors duration-200 cursor-pointer"
                                @click="document.getElementById('file-upload').click()"
                            >
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="file-upload" class="relative cursor-pointer rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none">
                                            <span>Upload a file</span>
                                            <input id="file-upload" type="file" class="sr-only" @change="handleFileChange" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        PDF, Word, Excel, and Image files up to 10MB
                                    </p>
                                </div>
                            </div>
                            
                            <!-- File Preview -->
                            <div x-show="filePreview" class="mt-3 bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-lg flex items-center justify-center" :class="{
                                            'bg-red-100': filePreview?.type === 'pdf',
                                            'bg-blue-100': filePreview?.type === 'word',
                                            'bg-green-100': filePreview?.type === 'excel',
                                            'bg-purple-100': filePreview?.type === 'image',
                                            'bg-gray-100': filePreview?.type === 'other'
                                        }">
                                            <template x-if="filePreview?.type === 'pdf'">
                                                <svg class="h-5 w-5 text-red-500" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                                                    <path d="M320 464c8.8 0 16-7.2 16-16V160H256c-17.7 0-32-14.3-32-32V48H64c-8.8 0-16 7.2-16 16V448c0 8.8 7.2 16 16 16H320zM0 64C0 28.7 28.7 0 64 0H229.5c17 0 33.3 6.7 45.3 18.7l90.5 90.5c12 12 18.7 28.3 18.7 45.3V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V64zm144 168v48c0 13.3-10.7 24-24 24H88c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8h32c13.3 0 24 10.7 24 24v48c0 8.8-7.2 16-16 16s-16-7.2-16-16V376c0-4.4-3.6-8-8-8H72c-8.8 0-16-7.2-16-16s7.2-16 16-16h32c4.4 0 8-3.6 8-8v-16c0-4.4-3.6-8-8-8H72c-8.8 0-16-7.2-16-16s7.2-16 16-16h32c4.4 0 8-3.6 8-8V232c0-8.8 7.2-16 16-16s16 7.2 16 16z"/>
                                                </svg>
                                            </template>
                                            <template x-if="filePreview?.type === 'word'">
                                                <svg class="h-5 w-5 text-blue-500" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                                                    <path d="M320 464c8.8 0 16-7.2 16-16V160H256c-17.7 0-32-14.3-32-32V48H64c-8.8 0-16 7.2-16 16V448c0 8.8 7.2 16 16 16H320zM0 64C0 28.7 28.7 0 64 0H229.5c17 0 33.3 6.7 45.3 18.7l90.5 90.5c12 12 18.7 28.3 18.7 45.3V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V64zm130.2 184.9c-7.6-4.4-17.4-1.8-21.8 5.8l-32 55.4C66.4 327.3 64 347.4 64 368v8c0 13.3 10.7 24 24 24s24-10.7 24-24v-8c0-12.8 1.5-25.5 4.4-37.8l8.3-14.3 7.8 30.3c3.2 12.5 14.3 21.3 27.2 21.3s24-8.8 27.2-21.3l16-62c1.9-7.3-2.2-14.9-9.5-16.8s-14.9 2.2-16.8 9.5l-9.6 37.5L149.9 250C138.5 208 114 171.7 81.5 144.2c-7.4-6.3-18.5-5.4-24.7 2s-5.4 18.5 2 24.7C88.2 196 109.9 227 121.8 262l8.4 27.9z"/>
                                                </svg>
                                            </template>
                                            <template x-if="filePreview?.type === 'excel'">
                                                <svg class="h-5 w-5 text-green-500" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                                                    <path d="M320 464c8.8 0 16-7.2 16-16V160H256c-17.7 0-32-14.3-32-32V48H64c-8.8 0-16 7.2-16 16V448c0 8.8 7.2 16 16 16H320zM0 64C0 28.7 28.7 0 64 0H229.5c17 0 33.3 6.7 45.3 18.7l90.5 90.5c12 12 18.7 28.3 18.7 45.3V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V64zm97.9 200.1c-9.9-9.9-26-9.9-35.9 0s-9.9 26 0 35.9l87 87c9.9 9.9 26 9.9 35.9 0l87-87c9.9-9.9 9.9-26 0-35.9s-26-9.9-35.9 0L160 329.1l-62.1-65z"/>
                                                </svg>
                                            </template>
                                            <template x-if="filePreview?.type === 'image'">
                                                <svg class="h-5 w-5 text-purple-500" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                                                    <path d="M320 464c8.8 0 16-7.2 16-16V160H256c-17.7 0-32-14.3-32-32V48H64c-8.8 0-16 7.2-16 16V448c0 8.8 7.2 16 16 16H320zM0 64C0 28.7 28.7 0 64 0H229.5c17 0 33.3 6.7 45.3 18.7l90.5 90.5c12 12 18.7 28.3 18.7 45.3V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V64zm128 264c0 13.3-10.7 24-24 24s-24-10.7-24-24s10.7-24 24-24s24 10.7 24 24zm96-120c13.3 0 24 10.7 24 24V328c0 13.3-10.7 24-24 24H104c-13.3 0-24-10.7-24-24V168c0-13.3 10.7-24 24-24H224z"/>
                                                </svg>
                                            </template>
                                            <template x-if="filePreview?.type === 'other'">
                                                <svg class="h-5 w-5 text-gray-500" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                                                    <path d="M320 464c8.8 0 16-7.2 16-16V160H256c-17.7 0-32-14.3-32-32V48H64c-8.8 0-16 7.2-16 16V448c0 8.8 7.2 16 16 16H320zM0 64C0 28.7 28.7 0 64 0H229.5c17 0 33.3 6.7 45.3 18.7l90.5 90.5c12 12 18.7 28.3 18.7 45.3V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V64z"/>
                                                </svg>
                                            </template>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900" x-text="filePreview?.name"></p>
                                            <p class="text-xs text-gray-500" x-text="filePreview?.size"></p>
                                        </div>
                                    </div>
                                    <button type="button" @click="removeFile" class="ml-4 flex-shrink-0 text-gray-400 hover:text-gray-500">
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                                <button 
                                    type="button" 
                                    class="mt-3 text-sm text-green-600 hover:text-green-500 focus:outline-none"
                                    @click="document.getElementById('file-upload').click()"
                                >
                                    Change file
                                </button>
                            </div>
                        </div>
                        
                        <!-- Form Fields -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label for="document_type" class="block text-sm font-medium text-gray-700">Document Type</label>
                                <select id="document_type" x-model="formData.document_type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md">
                                    <option value="">Select document type</option>
                                    <option value="id_card">ID Card</option>
                                    <option value="passport">Passport</option>
                                    <option value="drivers_license">Driver's License</option>
                                    <option value="vehicle_registration">Vehicle Registration</option>
                                    <option value="insurance">Insurance Document</option>
                                    <option value="invoice">Invoice</option>
                                    <option value="contract">Contract</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            
                            <div class="space-y-2" x-show="formData.document_type === 'other'">
                                <label for="custom_document_type" class="block text-sm font-medium text-gray-700">Custom Document Type</label>
                                <input type="text" id="custom_document_type" x-model="formData.custom_document_type" class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>
                            
                            <div class="space-y-2">
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea id="description" x-model="formData.description" rows="3" class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                            </div>
                            
                            <div class="space-y-2">
                                <label for="expiry_date" class="block text-sm font-medium text-gray-700">Expiry Date (if applicable)</label>
                                <input type="date" id="expiry_date" x-model="formData.expiry_date" class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>
                            
                            <div class="space-y-2">
                                <label for="tags" class="block text-sm font-medium text-gray-700">Tags (comma separated)</label>
                                <input type="text" id="tags" x-model="formData.tags" placeholder="important, archived, etc." class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>
                    </div>
                @else
                    <form id="{{ $formId }}" @submit.prevent="submitForm">
                        <div x-show="type === 'applicant'" x-transition>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                                    <input type="text" name="first_name" x-model="formData.first_name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                                    <input type="text" name="last_name" x-model="formData.last_name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Home Address</label>
                                    <input type="text" name="address" x-model="formData.address" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">College/Unit/Department</label>
                                    <input type="text" name="department" x-model="formData.department" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Designation/Position</label>
                                    <input type="text" name="position" x-model="formData.position" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                    <input type="tel" name="phone" x-model="formData.phone" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                    <input type="email" name="email" x-model="formData.email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Applicant Type</label>
                                    <select name="applicant_type" x-model="formData.applicant_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                                        <option value="">Select type</option>
                                        <option value="student">Student</option>
                                        <option value="faculty">Faculty</option>
                                        <option value="staff">Staff</option>
                                        <option value="visitor">Visitor</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div x-show="type === 'vehicle'" x-transition>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">License Plate</label>
                                    <input type="text" name="license_plate" x-model="formData.license_plate" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Vehicle Type</label>
                                    <select name="vehicle_type" x-model="formData.vehicle_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                                        <option value="">Select type</option>
                                        <option value="car">Car</option>
                                        <option value="motorcycle">Motorcycle</option>
                                        <option value="truck">Truck</option>
                                        <option value="bus">Bus</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Make/Brand</label>
                                    <input type="text" name="make" x-model="formData.make" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Model</label>
                                    <input type="text" name="model" x-model="formData.model" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                                    <input type="number" name="year" x-model="formData.year" min="1900" max="2099" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                                    <input type="text" name="color" x-model="formData.color" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Owner</label>
                                    <input type="text" name="owner" x-model="formData.owner" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Registration Date</label>
                                    <input type="date" name="registration_date" x-model="formData.registration_date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                                </div>
                            </div>
                        </div>
                        
                        <div x-show="type === 'rfid'" x-transition>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">RFID Tag Number</label>
                                    <input type="text" name="tag_number" x-model="formData.tag_number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tag Type</label>
                                    <select name="tag_type" x-model="formData.tag_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                                        <option value="">Select type</option>
                                        <option value="student">Student</option>
                                        <option value="employee">Employee</option>
                                        <option value="visitor">Visitor</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Issue Date</label>
                                    <input type="date" name="issue_date" x-model="formData.issue_date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Expiry Date</label>
                                    <input type="date" name="expiry_date" x-model="formData.expiry_date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                    <select name="status" x-model="formData.status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="lost">Lost/Damaged</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Assigned To</label>
                                    <input type="text" name="assigned_to" x-model="formData.assigned_to" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Vehicle (if applicable)</label>
                                    <input type="text" name="vehicle" x-model="formData.vehicle" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                                    <textarea name="notes" x-model="formData.notes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div x-show="type === 'admin'" x-transition>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                                    <input type="text" name="first_name" x-model="formData.first_name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                                    <input type="text" name="last_name" x-model="formData.last_name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                    <input type="email" name="email" x-model="formData.email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                    <input type="tel" name="phone" x-model="formData.phone" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                                    <select name="role" x-model="formData.role" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                                        <option value="">Select role</option>
                                        <option value="super_admin">Super Admin</option>
                                        <option value="admin">Admin</option>
                                        <option value="moderator">Moderator</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                    <select name="status" x-model="formData.status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                                    <input type="password" name="password" x-model="formData.password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                                    <input type="password" name="password_confirmation" x-model="formData.password_confirmation" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                                    <p x-show="passwordsDoNotMatch" class="mt-1 text-sm text-red-600">Passwords do not match</p>
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Permissions</label>
                                    <div class="grid grid-cols-2 gap-2 mt-2">
                                        <div class="flex items-center">
                                            <input type="checkbox" id="{{ $id }}_perm_applicants" name="permissions[]" value="applicants" x-model="formData.permissions" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                            <label for="{{ $id }}_perm_applicants" class="ml-2 block text-sm text-gray-700">Manage Applicants</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" id="{{ $id }}_perm_vehicles" name="permissions[]" value="vehicles" x-model="formData.permissions" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                            <label for="{{ $id }}_perm_vehicles" class="ml-2 block text-sm text-gray-700">Manage Vehicles</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" id="{{ $id }}_perm_rfid" name="permissions[]" value="rfid" x-model="formData.permissions" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                            <label for="{{ $id }}_perm_rfid" class="ml-2 block text-sm text-gray-700">Manage RFID</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" id="{{ $id }}_perm_admins" name="permissions[]" value="admins" x-model="formData.permissions" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                            <label for="{{ $id }}_perm_admins" class="ml-2 block text-sm text-gray-700">Manage Administrators</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
            
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end space-x-3">
                <button 
                    type="button" 
                    class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                    data-cancel-modal="{{ $id }}"
                >
                    Cancel
                </button>
                
                <button 
                    type="button" 
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed"
                    @click="submitForm"
                    :disabled="isSubmitting"
                >
                    <span x-show="!isSubmitting">Save</span>
                    <span x-show="isSubmitting" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Processing...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    function entityFormHandler(type, entityId) {
        return {
            type: type,
            entityId: entityId,
            formData: {},
            isSubmitting: false,
            passwordsDoNotMatch: false,
            filePreview: null,
            fileInput: null,
            
            init() {
                this.initializeFormData();
            },
            
            initializeFormData() {
                switch(this.type) {
                    case 'applicant':
                        this.formData = {
                            first_name: '',
                            last_name: '',
                            address: '',
                            department: '',
                            position: '',
                            phone: '',
                            email: '',
                            applicant_type: ''
                        };
                        break;
                    case 'vehicle':
                        this.formData = {
                            license_plate: '',
                            vehicle_type: '',
                            make: '',
                            model: '',
                            year: new Date().getFullYear(),
                            color: '',
                            owner: '',
                            registration_date: new Date().toISOString().split('T')[0]
                        };
                        break;
                    case 'rfid':
                        this.formData = {
                            tag_number: '',
                            tag_type: '',
                            issue_date: new Date().toISOString().split('T')[0],
                            expiry_date: new Date(new Date().setFullYear(new Date().getFullYear() + 1)).toISOString().split('T')[0],
                            status: 'active',
                            assigned_to: '',
                            vehicle: '',
                            notes: ''
                        };
                        break;
                    case 'admin':
                        this.formData = {
                            first_name: '',
                            last_name: '',
                            email: '',
                            phone: '',
                            role: '',
                            status: 'active',
                            password: '',
                            password_confirmation: '',
                            permissions: [],
                        };
                        break;
                    case 'document_upload':
                        this.formData = {
                            document_type: '',
                            custom_document_type: '',
                            description: '',
                            expiry_date: '',
                            tags: ''
                        };
                        break;
                }
            },
            
            handleFileChange(event) {
                const file = event.target.files[0];
                if (!file) return;
                
                if (file.size > 10 * 1024 * 1024) {
                    Swal.fire({
                        title: 'File too large!',
                        text: 'File size should not exceed 10MB',
                        icon: 'error'
                    });
                    event.target.value = '';
                    return;
                }
                
                const type = this.getFileType(file.name);
                const formattedSize = this.formatFileSize(file.size);
                
                this.filePreview = {
                    name: file.name,
                    size: formattedSize,
                    type: type
                };
                this.fileInput = file;
            },
            
            getFileType(filename) {
                const extension = filename.split('.').pop().toLowerCase();
                const types = {
                    'jpg|jpeg|png|gif': 'image',
                    'doc|docx': 'word',
                    'xls|xlsx': 'excel',
                    'pdf': 'pdf'
                };
                
                for (const [pattern, type] of Object.entries(types)) {
                    if (new RegExp(pattern).test(extension)) return type;
                }
                return 'other';
            },
            
            formatFileSize(bytes) {
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                if (bytes === 0) return '0 Byte';
                const i = Math.floor(Math.log(bytes) / Math.log(1024));
                return Math.round(bytes / Math.pow(1024, i)) + ' ' + sizes[i];
            },
            
            removeFile() {
                this.filePreview = null;
                this.fileInput = null;
                document.getElementById('file-upload').value = '';
            },
            
            validateForm() {
                let errors = [];
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                const phoneRegex = /^\+?\d{7,15}$/;

                switch(this.type) {
                    case 'applicant':
                        if (!this.formData.first_name) errors.push('First Name is required');
                        if (!this.formData.last_name) errors.push('Last Name is required');
                        if (!emailRegex.test(this.formData.email)) errors.push('Valid email required');
                        if (!phoneRegex.test(this.formData.phone)) errors.push('Valid phone number required');
                        if (!this.formData.applicant_type) errors.push('Applicant type required');
                        break;

                    case 'vehicle':
                        if (!this.formData.license_plate) errors.push('License Plate required');
                        if (this.formData.year < 1900 || this.formData.year > new Date().getFullYear() + 1) {
                            errors.push('Invalid vehicle year');
                        }
                        if (!this.formData.vehicle_type) errors.push('Vehicle type required');
                        break;

                    case 'rfid':
                        if (new Date(this.formData.expiry_date) <= new Date(this.formData.issue_date)) {
                            errors.push('Expiry date must be after issue date');
                        }
                        if (!this.formData.tag_number) errors.push('Tag number required');
                        break;

                    case 'admin':
                        if (this.formData.password !== this.formData.password_confirmation) {
                            this.passwordsDoNotMatch = true;
                            errors.push('Passwords do not match');
                        }
                        if (this.formData.password.length < 8) {
                            errors.push('Password must be at least 8 characters');
                        }
                        if (!this.formData.role) errors.push('Role selection required');
                        break;

                    case 'document_upload':
                        if (!this.fileInput) errors.push('File upload required');
                        if (!this.formData.document_type) errors.push('Document type required');
                        if (this.formData.document_type === 'other' && !this.formData.custom_document_type) {
                            errors.push('Custom document type required');
                        }
                        break;
                }

                if (errors.length > 0) {
                    Swal.fire({
                        title: 'Validation Error!',
                        html: errors.join('<br>'),
                        icon: 'error',
                        confirmButtonColor: '#43A047'
                    });
                    return false;
                }
                return true;
            },
            
            async submitForm() {
                if (!this.validateForm()) return;

                const action = this.type === 'document_upload' ? 'upload' : 'create';
                const entityName = this.type.charAt(0).toUpperCase() + this.type.slice(1);
                
                try {
                    const confirmation = await SweetAlertHelper.confirmAction(action, 1, entityName);
                    if (!confirmation.isConfirmed) return;

                    const loader = SweetAlertHelper.showLoading();
                    this.isSubmitting = true;

                    // Simulate API call
                    await new Promise(resolve => setTimeout(resolve, 1500));
                    
                    SweetAlertHelper.showSuccessMessage(action, 1, entityName);
                    closeModal('{{ $id }}');
                    this.initializeFormData();
                    if (this.type === 'document_upload') this.removeFile();
                } catch (error) {
                    Swal.fire({
                        title: 'Error!',
                        text: error.message || 'An error occurred',
                        icon: 'error',
                        confirmButtonColor: '#43A047'
                    });
                } finally {
                    this.isSubmitting = false;
                    Swal.close();
                }
            }
        };
    }

    window.SweetAlertHelper = {
        confirmDelete(count, entityName = null) {
            const message = entityName 
                ? `You are about to delete ${entityName}. This action cannot be undone.` 
                : `You are about to delete ${count} item(s). This action cannot be undone.`;
            return Swal.fire({
                title: 'Are you sure?',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            });
        },
        
        confirmApproval(count, entityName = null) {
            const message = entityName 
                ? `You are about to approve ${entityName}.`
                : `You are about to approve ${count} item(s).`;
            return Swal.fire({
                title: 'Confirm Approval',
                text: message,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#43A047',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Yes, approve it!'
            });
        },
        
        confirmCreate(entityName) {
            return Swal.fire({
                title: 'Confirm Creation',
                text: `You are about to create a new ${entityName}.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#43A047',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Yes, create it!'
            });
        },
        
        confirmUpload(entityName) {
            return Swal.fire({
                title: 'Confirm Upload',
                text: `You are about to upload ${entityName}.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#43A047',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Yes, upload it!'
            });
        },
        
        showLoading() {
            return Swal.fire({
                title: 'Processing...',
                text: 'Please wait while we process your request.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => Swal.showLoading()
            });
        },
        
        showSuccessMessage(action, count = 1, entityName = null) {
            let title, text;
            switch(action) {
                case 'delete':
                    title = 'Deleted!';
                    text = entityName 
                        ? `${entityName} has been deleted.`
                        : `${count} item(s) deleted.`;
                    break;
                case 'approve':
                    title = 'Approved!';
                    text = entityName 
                        ? `${entityName} has been approved.`
                        : `${count} item(s) approved.`;
                    break;
                case 'create':
                    title = 'Created!';
                    text = entityName 
                        ? `${entityName} has been created.`
                        : `${count} item(s) created.`;
                    break;
                case 'upload':
                    title = 'Uploaded!';
                    text = entityName 
                        ? `${entityName} has been uploaded.`
                        : `${count} item(s) uploaded.`;
                    break;
                default:
                    title = 'Success!';
                    text = 'Operation completed successfully.';
            }
            return Swal.fire({ title, text, icon: 'success', confirmButtonColor: '#43A047' });
        },
        
        confirmAction(action, count = 1, entityName = null) {
            switch(action) {
                case 'delete': return this.confirmDelete(count, entityName);
                case 'approve': return this.confirmApproval(count, entityName);
                case 'create': return this.confirmCreate(entityName);
                case 'upload': return this.confirmUpload(entityName);
                default: return Promise.resolve({ isConfirmed: true });
            }
        }
    };

    document.addEventListener('DOMContentLoaded', function() {
        // Modal animation functions
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            if (!modal) return;
            
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.querySelector('.modal-backdrop').classList.add('opacity-100');
                modal.querySelector('.modal-content').classList.remove('scale-95', 'opacity-0');
                modal.querySelector('.modal-content').classList.add('scale-100', 'opacity-100');
                
                // Set focus to first focusable element
                const focusable = modal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
                if (focusable.length) focusable[0].focus();
            }, 10);
            
            // Prevent body scrolling when modal is open
            document.body.style.overflow = 'hidden';
        }
        
        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (!modal) return;
            
            modal.querySelector('.modal-backdrop').classList.remove('opacity-100');
            modal.querySelector('.modal-content').classList.remove('scale-100', 'opacity-100');
            modal.querySelector('.modal-content').classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                // Restore body scrolling
                document.body.style.overflow = '';
            }, 300);
        }
        
        // Make the functions globally available
        window.openModal = openModal;
        window.closeModal = closeModal;
        
        // Open modals
        document.querySelectorAll('[data-open-modal]').forEach(button => {
            button.addEventListener('click', function() {
                const modalId = this.getAttribute('data-open-modal');
                openModal(modalId);
            });
        });
        
        // Close modals
        document.querySelectorAll('.close-modal-btn').forEach(button => {
            button.addEventListener('click', function() {
                const modalId = this.getAttribute('data-modal');
                closeModal(modalId);
            });
        });
        
        // Close modals when clicking cancel buttons
        document.querySelectorAll('[data-cancel-modal]').forEach(button => {
            button.addEventListener('click', function() {
                const modalId = this.getAttribute('data-cancel-modal');
                closeModal(modalId);
            });
        });
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
                    if (backdrop.classList.contains('opacity-100')) {
                        const modal = backdrop.closest('[id]');
                        if (modal) closeModal(modal.id);
                    }
                });
            }
        });
        
        // Close modal when clicking backdrop
        document.querySelectorAll('[id].fixed').forEach(modal => {
            modal.addEventListener('click', function(event) {
                if (event.target === this || event.target.classList.contains('modal-backdrop')) {
                    closeModal(this.id);
                }
            });
        });
        
        // Trap focus inside modal
        document.querySelectorAll('[id].fixed').forEach(modal => {
            modal.addEventListener('keydown', function(e) {
                if (e.key !== 'Tab') return;
                
                const focusable = modal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
                const firstFocusable = focusable[0];
                const lastFocusable = focusable[focusable.length - 1];
                
                if (e.shiftKey && document.activeElement === firstFocusable) {
                    e.preventDefault();
                    lastFocusable.focus();
                } else if (!e.shiftKey && document.activeElement === lastFocusable) {
                    e.preventDefault();
                    firstFocusable.focus();
                }
            });
        });
    });
</script>