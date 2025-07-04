@props([
'id' => 'entity-modal',
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
    x-data="entityFormHandler('{{ $type }}', '{{ $entityId }}', '{{ $id }}')">
    <div class="fixed inset-0 transition-opacity" aria-hidden="true">
        <div class="absolute inset-0 bg-gray-800 bg-opacity-70 backdrop-blur-sm transition-opacity duration-300 ease-in-out opacity-0 modal-backdrop"></div>
    </div>
    <div class="flex min-h-screen items-center justify-center p-4">
        <div
            class="bg-white rounded-xl shadow-2xl overflow-hidden transform transition-all scale-95 opacity-0 duration-300 ease-out {{ $maxWidth }} w-full modal-content border border-gray-100">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h2 id="{{ $id }}-title" class="text-xl font-semibold text-gray-800">{{ $title }}</h2>
                <button
                    class="text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-all duration-150 ease-in-out close-modal-btn focus:outline-none focus:ring-2 focus:ring-green-500 rounded-full p-2"
                    data-modal="{{ $id }}"
                    aria-label="Close">
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
                            :class="{'border-red-300 hover:border-red-500': errors.file}"
                            @click="document.getElementById('file-upload').click()">
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
                                                <path d="M320 464c8.8 0 16-7.2 16-16V160H256c-17.7 0-32-14.3-32-32V48H64c-8.8 0-16 7.2-16 16V448c0 8.8 7.2 16 16 16H320zM0 64C0 28.7 28.7 0 64 0H229.5c17 0 33.3 6.7 45.3 18.7l90.5 90.5c12 12 18.7 28.3 18.7 45.3V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V64zm144 168v48c0 13.3-10.7 24-24 24H88c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8h32c13.3 0 24 10.7 24 24v48c0 8.8-7.2 16-16 16s-16-7.2-16-16V376c0-4.4-3.6-8-8-8H72c-8.8 0-16-7.2-16-16s7.2-16 16-16h32c4.4 0 8-3.6 8-8v-16c0-4.4-3.6-8-8-8H72c-8.8 0-16-7.2-16-16s7.2-16 16-16h32c4.4 0 8-3.6 8-8V232c0-8.8 7.2-16 16-16s16 7.2 16 16z" />
                                            </svg>
                                        </template>
                                        <template x-if="filePreview?.type === 'word'">
                                            <svg class="h-5 w-5 text-blue-500" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                                                <path d="M320 464c8.8 0 16-7.2 16-16V160H256c-17.7 0-32-14.3-32-32V48H64c-8.8 0-16 7.2-16 16V448c0 8.8 7.2 16 16 16H320zM0 64C0 28.7 28.7 0 64 0H229.5c17 0 33.3 6.7 45.3 18.7l90.5 90.5c12 12 18.7 28.3 18.7 45.3V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V64zm130.2 184.9c-7.6-4.4-17.4-1.8-21.8 5.8l-32 55.4C66.4 327.3 64 347.4 64 368v8c0 13.3 10.7 24 24 24s24-10.7 24-24v-8c0-12.8 1.5-25.5 4.4-37.8l8.3-14.3 7.8 30.3c3.2 12.5 14.3 21.3 27.2 21.3s24-8.8 27.2-21.3l16-62c1.9-7.3-2.2-14.9-9.5-16.8s-14.9 2.2-16.8 9.5l-9.6 37.5L149.9 250C138.5 208 114 171.7 81.5 144.2c-7.4-6.3-18.5-5.4-24.7 2s-5.4 18.5 2 24.7C88.2 196 109.9 227 121.8 262l8.4 27.9z" />
                                            </svg>
                                        </template>
                                        <template x-if="filePreview?.type === 'excel'">
                                            <svg class="h-5 w-5 text-green-500" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                                                <path d="M320 464c8.8 0 16-7.2 16-16V160H256c-17.7 0-32-14.3-32-32V48H64c-8.8 0-16 7.2-16 16V448c0 8.8 7.2 16 16 16H320zM0 64C0 28.7 28.7 0 64 0H229.5c17 0 33.3 6.7 45.3 18.7l90.5 90.5c12 12 18.7 28.3 18.7 45.3V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V64zm97.9 200.1c-9.9-9.9-26-9.9-35.9 0s-9.9 26 0 35.9l87 87c9.9 9.9 26 9.9 35.9 0l87-87c9.9-9.9 9.9-26 0-35.9s-26-9.9-35.9 0L160 329.1l-62.1-65z" />
                                            </svg>
                                        </template>
                                        <template x-if="filePreview?.type === 'image'">
                                            <svg class="h-5 w-5 text-purple-500" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                                                <path d="M320 464c8.8 0 16-7.2 16-16V160H256c-17.7 0-32-14.3-32-32V48H64c-8.8 0-16 7.2-16 16V448c0 8.8 7.2 16 16 16H320zM0 64C0 28.7 28.7 0 64 0H229.5c17 0 33.3 6.7 45.3 18.7l90.5 90.5c12 12 18.7 28.3 18.7 45.3V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V64zm128 264c0 13.3-10.7 24-24 24s-24-10.7-24-24s10.7-24 24-24s24 10.7 24 24zm96-120c13.3 0 24 10.7 24 24V328c0 13.3-10.7 24-24 24H104c-13.3 0-24-10.7-24-24V168c0-13.3 10.7-24 24-24H224z" />
                                            </svg>
                                        </template>
                                        <template x-if="filePreview?.type === 'other'">
                                            <svg class="h-5 w-5 text-gray-500" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                                                <path d="M320 464c8.8 0 16-7.2 16-16V160H256c-17.7 0-32-14.3-32-32V48H64c-8.8 0-16 7.2-16 16V448c0 8.8 7.2 16 16 16H320zM0 64C0 28.7 28.7 0 64 0H229.5c17 0 33.3 6.7 45.3 18.7l90.5 90.5c12 12 18.7 28.3 18.7 45.3V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V64z" />
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
                                @click="document.getElementById('file-upload').click()">
                                Change file
                            </button>
                        </div>
                        <p x-show="errors.file" x-text="errors.file" class="mt-1 text-sm text-red-600"></p>
                    </div>

                    <!-- Form Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label for="document_type" class="block text-sm font-medium text-gray-700">Document Type</label>
                            <select
                                id="document_type"
                                x-model="formData.document_type"
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md"
                                :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.document_type}">
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
                            <p x-show="errors.document_type" x-text="errors.document_type" class="mt-1 text-sm text-red-600"></p>
                        </div>

                        <div class="space-y-2" x-show="formData.document_type === 'other'">
                            <label for="custom_document_type" class="block text-sm font-medium text-gray-700">Custom Document Type</label>
                            <input
                                type="text"
                                id="custom_document_type"
                                x-model="formData.custom_document_type"
                                class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.custom_document_type}">
                            <p x-show="errors.custom_document_type" x-text="errors.custom_document_type" class="mt-1 text-sm text-red-600"></p>
                        </div>

                        <div class="space-y-2">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea
                                id="description"
                                x-model="formData.description"
                                rows="3"
                                class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.description}"></textarea>
                            <p x-show="errors.description" x-text="errors.description" class="mt-1 text-sm text-red-600"></p>
                        </div>

                        <div class="space-y-2">
                            <label for="expiry_date" class="block text-sm font-medium text-gray-700">Expiry Date (if applicable)</label>
                            <input
                                type="date"
                                id="expiry_date"
                                x-model="formData.expiry_date"
                                class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.expiry_date}">
                            <p x-show="errors.expiry_date" x-text="errors.expiry_date" class="mt-1 text-sm text-red-600"></p>
                        </div>

                        <div class="space-y-2">
                            <label for="tags" class="block text-sm font-medium text-gray-700">Tags (comma separated)</label>
                            <input
                                type="text"
                                id="tags"
                                x-model="formData.tags"
                                placeholder="important, archived, etc."
                                class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                    </div>
                </div>
                @else
                <form id="{{ $formId }}" @submit.prevent="submitForm">
                    <div x-show="type === 'applicant'" x-transition>
                        <template x-if="currentStep === 1">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                    <input type="text" name="full_name" x-model="formData.full_name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.full_name}" required>
                                    <p x-show="errors.full_name" x-text="errors.full_name" class="mt-1 text-sm text-red-600"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Home Address</label>
                                    <input type="text" name="address" x-model="formData.address" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.address}" required>
                                    <p x-show="errors.address" x-text="errors.address" class="mt-1 text-sm text-red-600"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">College/Unit/Department</label>
                                    <input type="text" name="department" x-model="formData.department" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.department}" required>
                                    <p x-show="errors.department" x-text="errors.department" class="mt-1 text-sm text-red-600"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Designation/Position</label>
                                    <input type="text" name="position" x-model="formData.position" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.position}" required>
                                    <p x-show="errors.position" x-text="errors.position" class="mt-1 text-sm text-red-600"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                    <input type="tel" name="phone" x-model="formData.phone" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.phone}" required>
                                    <p x-show="errors.phone" x-text="errors.phone" class="mt-1 text-sm text-red-600"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                    <input type="email" name="email" x-model="formData.email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.email}" required>
                                    <p x-show="errors.email" x-text="errors.email" class="mt-1 text-sm text-red-600"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">CLSU ID Number</label>
                                    <input type="text" name="clsu_id" x-model="formData.clsu_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.clsu_id}" required>
                                    <p x-show="errors.clsu_id" x-text="errors.clsu_id" class="mt-1 text-sm text-red-600"></p>
                                </div>
                            </div>
                        </template>
                        <template x-if="currentStep === 2">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Vehicle Type</label>
                                    <select name="vehicle_type" x-model="formData.vehicle_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.vehicle_type}" required>
                                        <option value="">Select type</option>
                                        <option value="car">Car</option>
                                        <option value="motorcycle">Motorcycle</option>
                                        <option value="truck">Truck</option>
                                        <option value="bus">Bus</option>
                                    </select>
                                    <p x-show="errors.vehicle_type" x-text="errors.vehicle_type" class="mt-1 text-sm text-red-600"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Make</label>
                                    <input type="text" name="make" x-model="formData.make" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.make}" required>
                                    <p x-show="errors.make" x-text="errors.make" class="mt-1 text-sm text-red-600"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Model</label>
                                    <input type="text" name="model" x-model="formData.model" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.model}" required>
                                    <p x-show="errors.model" x-text="errors.model" class="mt-1 text-sm text-red-600"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                                    <input type="text" name="color" x-model="formData.color" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.color}" required>
                                    <p x-show="errors.color" x-text="errors.color" class="mt-1 text-sm text-red-600"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                                    <input type="number" name="year" x-model="formData.year" min="1900" max="2099" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.year}" required>
                                    <p x-show="errors.year" x-text="errors.year" class="mt-1 text-sm text-red-600"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Plate Number</label>
                                    <input type="text" name="plate_number" x-model="formData.plate_number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.plate_number}" required>
                                    <p x-show="errors.plate_number" x-text="errors.plate_number" class="mt-1 text-sm text-red-600"></p>
                                </div>
                            </div>
                        </template>
                        <template x-if="currentStep === 3">
                            <div class="space-y-6">
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Applicant Type</label>
                                    <select x-model="formData.applicant_type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md" :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.applicant_type}">
                                        <option value="">Select applicant type</option>
                                        <option value="student">Student</option>
                                        <option value="faculty">Faculty</option>
                                        <option value="visitor">Visitor/Outsider</option>
                                    </select>
                                    <p x-show="errors.applicant_type" x-text="errors.applicant_type" class="mt-1 text-sm text-red-600"></p>
                                </div>
                                <!-- File Upload Area (reuse existing UI) -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Upload Document</label>
                                    <div x-show="!filePreview" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-green-500 transition-colors duration-200 cursor-pointer" :class="{'border-red-300 hover:border-red-500': errors.file}" @click="document.getElementById('file-upload').click()">
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
                                            <p class="text-xs text-gray-500">PDF, Word, Excel, and Image files up to 10MB</p>
                                        </div>
                                    </div>
                                    <!-- File Preview (reuse existing UI) -->
                                    <div x-show="filePreview" class="mt-3 bg-gray-50 rounded-lg p-4 border border-gray-200">
                                        <div class="flex items-start justify-between">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 rounded-lg flex items-center justify-center" :class="{'bg-red-100': filePreview?.type === 'pdf','bg-blue-100': filePreview?.type === 'word','bg-green-100': filePreview?.type === 'excel','bg-purple-100': filePreview?.type === 'image','bg-gray-100': filePreview?.type === 'other'}">
                                                    <!-- SVGs as before -->
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
                                        <button type="button" class="mt-3 text-sm text-green-600 hover:text-green-500 focus:outline-none" @click="document.getElementById('file-upload').click()">Change file</button>
                                    </div>
                                    <p x-show="errors.file" x-text="errors.file" class="mt-1 text-sm text-red-600"></p>
                                </div>
                            </div>
                        </template>
                        <template x-if="currentStep === 4">
                            <div class="space-y-4">
                                <h3 class="text-lg font-semibold">Review Information</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <h4 class="font-medium">Personal Info</h4>
                                        <ul class="text-sm">
                                            <li><strong>Full Name:</strong> <span x-text="formData.full_name"></span></li>
                                            <li><strong>Address:</strong> <span x-text="formData.address"></span></li>
                                            <li><strong>Department:</strong> <span x-text="formData.department"></span></li>
                                            <li><strong>Position:</strong> <span x-text="formData.position"></span></li>
                                            <li><strong>Phone:</strong> <span x-text="formData.phone"></span></li>
                                            <li><strong>Email:</strong> <span x-text="formData.email"></span></li>
                                            <li><strong>CLSU ID:</strong> <span x-text="formData.clsu_id"></span></li>
                                        </ul>
                                    </div>
                                    <div>
                                        <h4 class="font-medium">Vehicle Info</h4>
                                        <ul class="text-sm">
                                            <li><strong>Type:</strong> <span x-text="formData.vehicle_type"></span></li>
                                            <li><strong>Make:</strong> <span x-text="formData.make"></span></li>
                                            <li><strong>Model:</strong> <span x-text="formData.model"></span></li>
                                            <li><strong>Color:</strong> <span x-text="formData.color"></span></li>
                                            <li><strong>Year:</strong> <span x-text="formData.year"></span></li>
                                            <li><strong>Plate Number:</strong> <span x-text="formData.plate_number"></span></li>
                                        </ul>
                                    </div>
                                    <div class="md:col-span-2">
                                        <h4 class="font-medium">Document</h4>
                                        <ul class="text-sm">
                                            <li><strong>Applicant Type:</strong> <span x-text="formData.applicant_type"></span></li>
                                            <li><strong>File:</strong> <span x-text="filePreview?.name"></span></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <div class="flex justify-center gap-4 mt-8">
                            <button
                                type="button"
                                class="px-6 py-2 bg-gray-200 text-gray-700 rounded shadow-sm hover:bg-gray-300 transition"
                                x-show="currentStep === 1"
                                @click="closeModal('{{ $id }}')"
                            >Cancel</button>
                            <button
                                type="button"
                                class="px-6 py-2 bg-gray-200 text-gray-700 rounded shadow-sm hover:bg-gray-300 transition"
                                x-show="currentStep > 1"
                                @click="prevStep"
                            >Back</button>
                            <button
                                type="button"
                                class="px-6 py-2 bg-green-600 text-white rounded shadow-sm hover:bg-green-700 transition"
                                x-show="currentStep < 4"
                                @click="nextStep"
                            >Next</button>
                            <button
                                type="button"
                                class="px-6 py-2 bg-green-600 text-white rounded shadow-sm hover:bg-green-700 transition"
                                x-show="currentStep === 4 && !isSubmitting"
                                @click="submitForm"
                            >Save</button>
                            <span x-show="isSubmitting" class="flex items-center"><svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Processing...</span>
                        </div>
                    </div>
                    <div x-show="type === 'admin'" x-transition>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                                <input
                                    type="text"
                                    name="first_name"
                                    x-model="formData.first_name"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.first_name}"
                                    required>
                                <p x-show="errors.first_name" x-text="errors.first_name" class="mt-1 text-sm text-red-600"></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                                <input
                                    type="text"
                                    name="last_name"
                                    x-model="formData.last_name"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.last_name}"
                                    required>
                                <p x-show="errors.last_name" x-text="errors.last_name" class="mt-1 text-sm text-red-600"></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                <input
                                    type="email"
                                    name="email"
                                    x-model="formData.email"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.email}"
                                    required>
                                <p x-show="errors.email" x-text="errors.email" class="mt-1 text-sm text-red-600"></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                <input
                                    type="tel"
                                    name="phone"
                                    x-model="formData.phone"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.phone}"
                                    required>
                                <p x-show="errors.phone" x-text="errors.phone" class="mt-1 text-sm text-red-600"></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                                <select
                                    name="role"
                                    x-model="formData.role"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.role}"
                                    required>
                                    <option value="">Select role</option>
                                    <option value="super_admin">Super Admin</option>
                                    <option value="admin">Admin</option>
                                    <option value="moderator">Moderator</option>
                                </select>
                                <p x-show="errors.role" x-text="errors.role" class="mt-1 text-sm text-red-600"></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select
                                    name="status"
                                    x-model="formData.status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.status}"
                                    required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                                <p x-show="errors.status" x-text="errors.status" class="mt-1 text-sm text-red-600"></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                                <input
                                    type="password"
                                    name="password"
                                    x-model="formData.password"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.password}"
                                    required>
                                <p x-show="errors.password" x-text="errors.password" class="mt-1 text-sm text-red-600"></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                                <input
                                    type="password"
                                    name="password_confirmation"
                                    x-model="formData.password_confirmation"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.password_confirmation}"
                                    required>
                                <p x-show="errors.password_confirmation" x-text="errors.password_confirmation" class="mt-1 text-sm text-red-600"></p>
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Permissions</label>
                                <div class="grid grid-cols-2 gap-2 mt-2">
                                    <div class="flex items-center">
                                        <input
                                            type="checkbox"
                                            id="{{ $id }}_perm_applicants"
                                            name="permissions[]"
                                            value="applicants"
                                            x-model="formData.permissions"
                                            class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                        <label for="{{ $id }}_perm_applicants" class="ml-2 block text-sm text-gray-700">Manage Applicants</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input
                                            type="checkbox"
                                            id="{{ $id }}_perm_vehicles"
                                            name="permissions[]"
                                            value="vehicles"
                                            x-model="formData.permissions"
                                            class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                        <label for="{{ $id }}_perm_vehicles" class="ml-2 block text-sm text-gray-700">Manage Vehicles</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input
                                            type="checkbox"
                                            id="{{ $id }}_perm_rfid"
                                            name="permissions[]"
                                            value="rfid"
                                            x-model="formData.permissions"
                                            class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                        <label for="{{ $id }}_perm_rfid" class="ml-2 block text-sm text-gray-700">Manage RFID</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input
                                            type="checkbox"
                                            id="{{ $id }}_perm_admins"
                                            name="permissions[]"
                                            value="admins"
                                            x-model="formData.permissions"
                                            class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                        <label for="{{ $id }}_perm_admins" class="ml-2 block text-sm text-gray-700">Manage Administrators</label>
                                    </div>
                                </div>
                                <p x-show="errors.permissions" x-text="errors.permissions" class="mt-1 text-sm text-red-600"></p>
                            </div>
                        </div>
                    </div>
                    <div x-show="type === 'document_upload'" x-transition>
                        <!-- Restore original document upload modal fields here (as before) -->
                        <!-- ... existing document upload form fields ... -->
                    </div>
                </form>
                @endif
            </div>

            @if ($type !== 'applicant')
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end space-x-3">
                <button
                    type="button"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                    data-cancel-modal="{{ $id }}">
                    Cancel
                </button>

                <button
                    type="button"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed"
                    @click="submitForm"
                    :disabled="isSubmitting">
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
            @endif
        </div>
    </div>
</div>
<script>
    function entityFormHandler(type, entityId, modalId) {
        return {
            type: type,
            entityId: entityId,
            modalId: modalId,
            formData: {},
            isSubmitting: false,
            errors: {}, // Object to store field-specific validation errors
            filePreview: null,
            fileInput: null,
            currentStep: 1,

            init() {
                this.initializeFormData();
            },

            initializeFormData() {
                this.currentStep = 1;
                // Reset error state when initializing form
                this.errors = {};

                switch (this.type) {
                    case 'applicant':
                        this.formData = {
                            full_name: '',
                            address: '',
                            department: '',
                            position: '',
                            phone: '',
                            email: '',
                            clsu_id: '',
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
                // Clear previous file error
                this.errors.file = null;
                if (file.size > 10 * 1024 * 1024) {
                    this.errors.file = 'File size should not exceed 10MB';
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
                this.errors.file = null;
                document.getElementById('file-upload').value = '';
            },

            validateForm() {
                // Reset all errors before validation
                this.errors = {};
                let isValid = true;

                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                const phoneRegex = /^\+?\d{7,15}$/;

                switch (this.type) {
                    case 'applicant':
                        if (!this.formData.full_name) {
                            this.errors.full_name = 'Full Name is required';
                            isValid = false;
                        }
                        if (!this.formData.address) {
                            this.errors.address = 'Address is required';
                            isValid = false;
                        }
                        if (!this.formData.department) {
                            this.errors.department = 'Department is required';
                            isValid = false;
                        }
                        if (!this.formData.position) {
                            this.errors.position = 'Position is required';
                            isValid = false;
                        }
                        if (!this.formData.phone) {
                            this.errors.phone = 'Phone number is required';
                            isValid = false;
                        } else if (!phoneRegex.test(this.formData.phone)) {
                            this.errors.phone = 'Valid phone number required';
                            isValid = false;
                        }
                        if (!this.formData.email) {
                            this.errors.email = 'Email is required';
                            isValid = false;
                        } else if (!emailRegex.test(this.formData.email)) {
                            this.errors.email = 'Valid email required';
                            isValid = false;
                        }
                        if (!this.formData.clsu_id) {
                            this.errors.clsu_id = 'CLSU ID is required';
                            isValid = false;
                        }
                        break;
                    case 'vehicle':
                        if (!this.formData.license_plate) {
                            this.errors.license_plate = 'License Plate is required';
                            isValid = false;
                        }
                        if (!this.formData.vehicle_type) {
                            this.errors.vehicle_type = 'Vehicle type is required';
                            isValid = false;
                        }
                        if (!this.formData.make) {
                            this.errors.make = 'Make/Brand is required';
                            isValid = false;
                        }
                        if (!this.formData.model) {
                            this.errors.model = 'Model is required';
                            isValid = false;
                        }
                        if (!this.formData.year) {
                            this.errors.year = 'Year is required';
                            isValid = false;
                        } else if (this.formData.year < 1900 || this.formData.year > new Date().getFullYear() + 1) {
                            this.errors.year = 'Invalid vehicle year';
                            isValid = false;
                        }
                        if (!this.formData.color) {
                            this.errors.color = 'Color is required';
                            isValid = false;
                        }
                        if (!this.formData.owner) {
                            this.errors.owner = 'Owner is required';
                            isValid = false;
                        }
                        if (!this.formData.registration_date) {
                            this.errors.registration_date = 'Registration date is required';
                            isValid = false;
                        }
                        break;
                    case 'rfid':
                        if (!this.formData.tag_number) {
                            this.errors.tag_number = 'Tag number is required';
                            isValid = false;
                        }
                        if (!this.formData.tag_type) {
                            this.errors.tag_type = 'Tag type is required';
                            isValid = false;
                        }
                        if (!this.formData.issue_date) {
                            this.errors.issue_date = 'Issue date is required';
                            isValid = false;
                        }
                        if (!this.formData.expiry_date) {
                            this.errors.expiry_date = 'Expiry date is required';
                            isValid = false;
                        } else if (new Date(this.formData.expiry_date) <= new Date(this.formData.issue_date)) {
                            this.errors.expiry_date = 'Expiry date must be after issue date';
                            isValid = false;
                        }
                        if (!this.formData.assigned_to) {
                            this.errors.assigned_to = 'Assigned To is required';
                            isValid = false;
                        }
                        break;
                    case 'admin':
                        if (!this.formData.first_name) {
                            this.errors.first_name = 'First Name is required';
                            isValid = false;
                        }
                        if (!this.formData.last_name) {
                            this.errors.last_name = 'Last Name is required';
                            isValid = false;
                        }
                        if (!this.formData.email) {
                            this.errors.email = 'Email is required';
                            isValid = false;
                        } else if (!emailRegex.test(this.formData.email)) {
                            this.errors.email = 'Valid email required';
                            isValid = false;
                        }
                        if (!this.formData.phone) {
                            this.errors.phone = 'Phone number is required';
                            isValid = false;
                        } else if (!phoneRegex.test(this.formData.phone)) {
                            this.errors.phone = 'Valid phone number required';
                            isValid = false;
                        }
                        if (!this.formData.role) {
                            this.errors.role = 'Role selection is required';
                            isValid = false;
                        }
                        if (!this.formData.password) {
                            this.errors.password = 'Password is required';
                            isValid = false;
                        } else if (this.formData.password.length < 8) {
                            this.errors.password = 'Password must be at least 8 characters';
                            isValid = false;
                        }
                        if (!this.formData.password_confirmation) {
                            this.errors.password_confirmation = 'Please confirm password';
                            isValid = false;
                        } else if (this.formData.password !== this.formData.password_confirmation) {
                            this.errors.password_confirmation = 'Passwords do not match';
                            isValid = false;
                        }
                        if (this.formData.permissions.length === 0) {
                            this.errors.permissions = 'At least one permission is required';
                            isValid = false;
                        }
                        break;
                    case 'document_upload':
                        if (!this.fileInput) {
                            this.errors.file = 'File upload is required';
                            isValid = false;
                        }
                        if (!this.formData.document_type) {
                            this.errors.document_type = 'Document type is required';
                            isValid = false;
                        }
                        if (this.formData.document_type === 'other' && !this.formData.custom_document_type) {
                            this.errors.custom_document_type = 'Custom document type is required';
                            isValid = false;
                        }
                        if (!this.formData.description) {
                            this.errors.description = 'Description is required';
                            isValid = false;
                        }
                        break;
                }
                return isValid;
            },

            async submitForm() {
                // Validate the form before submission
                if (!this.validateForm()) {
                    // Scroll to the first error
                    const firstErrorField = document.querySelector('.border-red-300');
                    if (firstErrorField) {
                        firstErrorField.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                        firstErrorField.focus();
                    }
                    return;
                }

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
                    this.initializeFormData();
                    if (this.type === 'document_upload') this.removeFile();
                    closeModal(this.modalId);
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
            },

            prevStep() {
                if (this.currentStep > 1) {
                    this.currentStep--;
                }
            },

            nextStep() {
                if (this.currentStep < 4) {
                    this.currentStep++;
                }
            }
        };
    }

    window.SweetAlertHelper = {
        confirmDelete(count, entityName = null) {
            const message = entityName ?
                `You are about to delete ${entityName}. This action cannot be undone.` :
                `You are about to delete ${count} item(s). This action cannot be undone.`;
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
            const message = entityName ?
                `You are about to approve ${entityName}.` :
                `You are about to approve ${count} item(s).`;
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
            switch (action) {
                case 'delete':
                    title = 'Deleted!';
                    text = entityName ?
                        `${entityName} has been deleted.` :
                        `${count} item(s) deleted.`;
                    break;
                case 'approve':
                    title = 'Approved!';
                    text = entityName ?
                        `${entityName} has been approved.` :
                        `${count} item(s) approved.`;
                    break;
                case 'create':
                    title = 'Created!';
                    text = entityName ?
                        `${entityName} has been created.` :
                        `${count} item(s) created.`;
                    break;
                case 'upload':
                    title = 'Uploaded!';
                    text = entityName ?
                        `${entityName} has been uploaded.` :
                        `${count} item(s) uploaded.`;
                    break;
                default:
                    title = 'Success!';
                    text = 'Operation completed successfully.';
            }
            return Swal.fire({
                title,
                text,
                icon: 'success',
                confirmButtonColor: '#43A047'
            });
        },

        confirmAction(action, count = 1, entityName = null) {
            switch (action) {
                case 'delete':
                    return this.confirmDelete(count, entityName);
                case 'approve':
                    return this.confirmApproval(count, entityName);
                case 'create':
                    return this.confirmCreate(entityName);
                case 'upload':
                    return this.confirmUpload(entityName);
                default:
                    return Promise.resolve({
                        isConfirmed: true
                    });
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
<style>
    .modal-content input[type="text"],
    .modal-content input[type="email"],
    .modal-content input[type="password"],
    .modal-content input[type="tel"],
    .modal-content input[type="number"],
    .modal-content input[type="date"],
    .modal-content select,
    .modal-content textarea {
        transition: box-shadow 0.2s, border-color 0.2s;
        border-radius: 0.75rem;
        box-shadow: 0 1px 2px 0 rgba(60,60,60,0.04);
        background: #fafbfc;
    }
    .modal-content input:focus,
    .modal-content select:focus,
    .modal-content textarea:focus {
        border-color: #43A047;
        box-shadow: 0 0 0 2px #a7f3d0;
        background: #fff;
    }
    .modal-content input:hover,
    .modal-content select:hover,
    .modal-content textarea:hover {
        border-color: #a7f3d0;
    }
    .modal-content label {
        font-weight: 500;
        color: #374151;
    }
    .modal-content .shadow-sm {
        box-shadow: 0 1px 2px 0 rgba(60,60,60,0.04);
    }
</style>