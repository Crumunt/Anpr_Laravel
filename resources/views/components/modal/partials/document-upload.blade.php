<div class="space-y-6">
    <!-- File Upload Area -->
    <div class="space-y-2">
        <label class="block text-sm font-medium text-gray-700">Upload Document</label>
        <div x-show="!filePreview"
            class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-green-500 transition-colors duration-200 cursor-pointer"
            :class="{'border-red-300 hover:border-red-500': errors.file}"
            @click="document.getElementById('file-upload').click()">
            <div class="space-y-1 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48"
                    aria-hidden="true">
                    <path
                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <div class="flex text-sm text-gray-600">
                    <label for="file-upload"
                        class="relative cursor-pointer rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none">
                        <span>Upload a file</span>
                        <input id="file-upload" type="file" class="sr-only" @change="handleFileChange"
                            accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
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
                            <svg class="h-5 w-5 text-red-500" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 384 512">
                                <path
                                    d="M320 464c8.8 0 16-7.2 16-16V160H256c-17.7 0-32-14.3-32-32V48H64c-8.8 0-16 7.2-16 16V448c0 8.8 7.2 16 16 16H320zM0 64C0 28.7 28.7 0 64 0H229.5c17 0 33.3 6.7 45.3 18.7l90.5 90.5c12 12 18.7 28.3 18.7 45.3V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V64zm144 168v48c0 13.3-10.7 24-24 24H88c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8h32c13.3 0 24 10.7 24 24v48c0 8.8-7.2 16-16 16s-16-7.2-16-16V376c0-4.4-3.6-8-8-8H72c-8.8 0-16-7.2-16-16s7.2-16 16-16h32c4.4 0 8-3.6 8-8v-16c0-4.4-3.6-8-8-8H72c-8.8 0-16-7.2-16-16s7.2-16 16-16h32c4.4 0 8-3.6 8-8V232c0-8.8 7.2-16 16-16s16 7.2 16 16z" />
                            </svg>
                        </template>
                        <template x-if="filePreview?.type === 'word'">
                            <svg class="h-5 w-5 text-blue-500" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 384 512">
                                <path
                                    d="M320 464c8.8 0 16-7.2 16-16V160H256c-17.7 0-32-14.3-32-32V48H64c-8.8 0-16 7.2-16 16V448c0 8.8 7.2 16 16 16H320zM0 64C0 28.7 28.7 0 64 0H229.5c17 0 33.3 6.7 45.3 18.7l90.5 90.5c12 12 18.7 28.3 18.7 45.3V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V64zm130.2 184.9c-7.6-4.4-17.4-1.8-21.8 5.8l-32 55.4C66.4 327.3 64 347.4 64 368v8c0 13.3 10.7 24 24 24s24-10.7 24-24v-8c0-12.8 1.5-25.5 4.4-37.8l8.3-14.3 7.8 30.3c3.2 12.5 14.3 21.3 27.2 21.3s24-8.8 27.2-21.3l16-62c1.9-7.3-2.2-14.9-9.5-16.8s-14.9 2.2-16.8 9.5l-9.6 37.5L149.9 250C138.5 208 114 171.7 81.5 144.2c-7.4-6.3-18.5-5.4-24.7 2s-5.4 18.5 2 24.7C88.2 196 109.9 227 121.8 262l8.4 27.9z" />
                            </svg>
                        </template>
                        <template x-if="filePreview?.type === 'excel'">
                            <svg class="h-5 w-5 text-green-500" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 384 512">
                                <path
                                    d="M320 464c8.8 0 16-7.2 16-16V160H256c-17.7 0-32-14.3-32-32V48H64c-8.8 0-16 7.2-16 16V448c0 8.8 7.2 16 16 16H320zM0 64C0 28.7 28.7 0 64 0H229.5c17 0 33.3 6.7 45.3 18.7l90.5 90.5c12 12 18.7 28.3 18.7 45.3V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V64zm97.9 200.1c-9.9-9.9-26-9.9-35.9 0s-9.9 26 0 35.9l87 87c9.9 9.9 26 9.9 35.9 0l87-87c9.9-9.9 9.9-26 0-35.9s-26-9.9-35.9 0L160 329.1l-62.1-65z" />
                            </svg>
                        </template>
                        <template x-if="filePreview?.type === 'image'">
                            <svg class="h-5 w-5 text-purple-500" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 384 512">
                                <path
                                    d="M320 464c8.8 0 16-7.2 16-16V160H256c-17.7 0-32-14.3-32-32V48H64c-8.8 0-16 7.2-16 16V448c0 8.8 7.2 16 16 16H320zM0 64C0 28.7 28.7 0 64 0H229.5c17 0 33.3 6.7 45.3 18.7l90.5 90.5c12 12 18.7 28.3 18.7 45.3V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V64zm128 264c0 13.3-10.7 24-24 24s-24-10.7-24-24s10.7-24 24-24s24 10.7 24 24zm96-120c13.3 0 24 10.7 24 24V328c0 13.3-10.7 24-24 24H104c-13.3 0-24-10.7-24-24V168c0-13.3 10.7-24 24-24H224z" />
                            </svg>
                        </template>
                        <template x-if="filePreview?.type === 'other'">
                            <svg class="h-5 w-5 text-gray-500" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 384 512">
                                <path
                                    d="M320 464c8.8 0 16-7.2 16-16V160H256c-17.7 0-32-14.3-32-32V48H64c-8.8 0-16 7.2-16 16V448c0 8.8 7.2 16 16 16H320zM0 64C0 28.7 28.7 0 64 0H229.5c17 0 33.3 6.7 45.3 18.7l90.5 90.5c12 12 18.7 28.3 18.7 45.3V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V64z" />
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
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            <button type="button" class="mt-3 text-sm text-green-600 hover:text-green-500 focus:outline-none"
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
            <select id="document_type" x-model="formData.document_type"
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
            <label for="custom_document_type" class="block text-sm font-medium text-gray-700">Custom Document
                Type</label>
            <input type="text" id="custom_document_type" x-model="formData.custom_document_type"
                class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.custom_document_type}">
            <p x-show="errors.custom_document_type" x-text="errors.custom_document_type"
                class="mt-1 text-sm text-red-600"></p>
        </div>

        <div class="space-y-2">
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea id="description" x-model="formData.description" rows="3"
                class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.description}"></textarea>
            <p x-show="errors.description" x-text="errors.description" class="mt-1 text-sm text-red-600"></p>
        </div>

        <div class="space-y-2">
            <label for="expiry_date" class="block text-sm font-medium text-gray-700">Expiry Date (if applicable)</label>
            <input type="date" id="expiry_date" x-model="formData.expiry_date"
                class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.expiry_date}">
            <p x-show="errors.expiry_date" x-text="errors.expiry_date" class="mt-1 text-sm text-red-600"></p>
        </div>

        <div class="space-y-2">
            <label for="tags" class="block text-sm font-medium text-gray-700">Tags (comma separated)</label>
            <input type="text" id="tags" x-model="formData.tags" placeholder="important, archived, etc."
                class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
        </div>
    </div>
</div>

<x-slot name="controlSlot">
    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end space-x-3">
        <button type="button"
            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
            data-cancel-modal="{{ $id }}">
            Cancel
        </button>

        <button type="button"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed"
            @click="submitForm" :disabled="isSubmitting">
            <span x-show="!isSubmitting">Save</span>
            <span x-show="isSubmitting" class="flex items-center">
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                Processing...
            </span>
        </button>
    </div>
</x-slot>