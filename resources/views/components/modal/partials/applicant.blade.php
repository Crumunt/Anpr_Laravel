@props(['id'])

<div x-data="locationSelector()">
    <template x-if="currentStep === 1">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <input type="text" name="full_name" x-model="formData.full_name"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.full_name}" required>
                <p x-show="errors.full_name" x-text="errors.full_name" class="mt-1 text-sm text-red-600"></p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                <input type="text" name="middle_name" x-model="formData.middle_name"
                    placeholder="Leave blank if not applicable."
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent placeholder:text-gray-500 placeholder:italic placeholder:text-sm"
                    :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.full_name}">
                <p x-show="errors.full_name" x-text="errors.full_name" class="mt-1 text-sm text-red-600"></p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                <input type="text" name="address" x-model="formData.address"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.address}" required>
                <p x-show="errors.address" x-text="errors.address" class="mt-1 text-sm text-red-600"></p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                <input type="tel" name="phone" x-model="formData.phone" placeholder="09123456789"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent placeholder:text-gray-500 placeholder:italic placeholder:text-sm"
                    :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.phone}" required>
                <p x-show="errors.phone" x-text="errors.phone" class="mt-1 text-sm text-red-600"></p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <input type="email" name="email" x-model="formData.email"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.email}" required>
                <p x-show="errors.email" x-text="errors.email" class="mt-1 text-sm text-red-600"></p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Zip Code</label>
                <input type="text" name="college_unit_department"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.email}" required>
                <p x-show="errors.email" x-text="errors.email" class="mt-1 text-sm text-red-600"></p>
            </div>
            <div class="space-y-6">
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Region</label>
                    <select x-model="selected.region" @change="onRegionChange()"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md"
                        :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.applicant_type}">
                        <option value="">Select Region</option>
                        <template x-for="(region, index) in regions" :key="region.code">
                            <option :value="region.code" x-text="region.region_name"></option>
                        </template>
                    </select>
                    <p x-show="errors.applicant_type" x-text="errors.applicant_type" class="mt-1 text-sm text-red-600">
                    </p>
                </div>
            </div>
            <div class="space-y-6">
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Province</label>
                    <select x-model="selected.province" @change="onProvinceChange()"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md"
                        :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.applicant_type}">
                        <option value="">Select province</option>
                        <template x-for="(province_data, province_name) in provinces" :key="province_name">
                            <option :value="province_name" x-text="province_name"></option>
                        </template>
                    </select>
                    <p x-show="errors.applicant_type" x-text="errors.applicant_type" class="mt-1 text-sm text-red-600">
                    </p>
                </div>
            </div>
            <div class="space-y-6">
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">City</label>
                    <select x-model="selected.citymun" @change="onCityMunChange()"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md"
                        :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.applicant_type}">
                        <option value="">Select city</option>
                        <template x-for="(city_data, city_name) in cityMunicipalities" :key="city_name">
                            <option :value="city_name" x-text="city_name"></option>
                        </template>
                    </select>
                    <p x-show="errors.applicant_type" x-text="errors.applicant_type" class="mt-1 text-sm text-red-600">
                    </p>
                </div>
            </div>
            <div class="space-y-6">
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Barangay</label>
                    <select x-model="selected.barangay"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md"
                        :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.applicant_type}">
                        <option value="">Select barangay</option>
                        <template x-for="barangay_name in barangays" :key="barangay_name">
                            <option :value="barangay_name" x-text="barangay_name"></option>
                        </template>
                    </select>
                    <p x-show="errors.applicant_type" x-text="errors.applicant_type" class="mt-1 text-sm text-red-600">
                    </p>
                </div>
            </div>
            <div class="space-y-6">
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Applicant Type</label>
                    <select x-model="formData.applicant_type"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md"
                        :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.applicant_type}">
                        <option value="">Select applicant type</option>
                        <option value="student">Student</option>
                        <option value="faculty">Faculty</option>
                        <option value="staff">Staff</option>
                    </select>
                    <p x-show="errors.applicant_type" x-text="errors.applicant_type" class="mt-1 text-sm text-red-600">
                    </p>
                </div>
            </div>
            <div x-show="['student','faculty', 'staff'].includes(formData.applicant_type)" x-transition.duration-200>
                <label class="block text-sm font-medium text-gray-700 mb-1">CLSU ID Number</label>
                <input type="text" name="clsu_id" x-model="formData.clsu_id"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.clsu_id}" required>
                <p x-show="errors.clsu_id" x-text="errors.clsu_id" class="mt-1 text-sm text-red-600"></p>
            </div>
            <div x-show="['student','faculty', 'staff'].includes(formData.applicant_type)" x-transition.duration-250>
                <label class="block text-sm font-medium text-gray-700 mb-1">College/Unit/Department</label>
                <input type="text" name="college_unit_department"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.email}" required>
                <p x-show="errors.email" x-text="errors.email" class="mt-1 text-sm text-red-600"></p>
            </div>
            <div x-show="['faculty', 'staff'].includes(formData.applicant_type)" x-transition.duration-300>
                <label class="block text-sm font-medium text-gray-700 mb-1">Designation/Position</label>
                <input type="text" name="college_unit_department"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.email}" required>
                <p x-show="errors.email" x-text="errors.email" class="mt-1 text-sm text-red-600"></p>
            </div>
        </div>
    </template>
    <template x-if="currentStep === 2">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Vehicle Type</label>
                <select name="vehicle_type" x-model="formData.vehicle_type"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.vehicle_type}" required>
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
                <input type="text" name="make" x-model="formData.make"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.make}" required>
                <p x-show="errors.make" x-text="errors.make" class="mt-1 text-sm text-red-600"></p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Model</label>
                <input type="text" name="model" x-model="formData.model"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.model}" required>
                <p x-show="errors.model" x-text="errors.model" class="mt-1 text-sm text-red-600"></p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                <input type="text" name="color" x-model="formData.color"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.color}" required>
                <p x-show="errors.color" x-text="errors.color" class="mt-1 text-sm text-red-600"></p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                <input type="number" name="year" x-model="formData.year" min="1900" max="2099"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.year}" required>
                <p x-show="errors.year" x-text="errors.year" class="mt-1 text-sm text-red-600"></p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Plate Number</label>
                <input type="text" name="plate_number" x-model="formData.plate_number"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.plate_number}" required>
                <p x-show="errors.plate_number" x-text="errors.plate_number" class="mt-1 text-sm text-red-600"></p>
            </div>
        </div>
    </template>
    <template x-if="currentStep === 3">
        <div class="space-y-2">
            <label class="block text-sm font-medium text-gray-700">Upload Document</label>
            <div x-show="filePreview.length === 0"
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
                            <input id="file-upload" type="file" multiple class="sr-only" @change="handleFileChange"
                                accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
                        </label>
                        <p class="pl-1">or drag and drop</p>
                    </div>
                    <p class="text-xs text-gray-500">PDF, Word, Excel, and Image files up to 10MB</p>
                </div>
            </div>
            <!-- File Preview (reuse existing UI) -->
            <div x-show="filePreview.length > 0" class="mt-3 space-y-3">
                <template x-for="(file, index) in filePreview" :key="file.name">
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center">
                                <!-- Icon container -->
                                <div class="flex-shrink-0 h-10 w-10 rounded-lg flex items-center justify-center" :class="{
                            'bg-red-100': file.type === 'pdf',
                            'bg-blue-100': file.type === 'word',
                            'bg-green-100': file.type === 'excel',
                            'bg-purple-100': file.type === 'image',
                            'bg-gray-100': file.type === 'other'
                        }">
                                    <!-- Optional SVG icon here depending on file type -->
                                    <svg class="h-5 w-5 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 4h12v12H4z" />
                                    </svg>
                                </div>

                                <!-- File name and size -->
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900" x-text="file.name"></p>
                                    <p class="text-xs text-gray-500" x-text="file.size"></p>
                                </div>
                            </div>

                            <!-- Remove file button -->
                            <button type="button" @click="removeFile(index)"
                                class="ml-4 flex-shrink-0 text-gray-400 hover:text-gray-500">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>

                        <!-- Change file (optional if you allow editing individual file inputs) -->
                        <button type="button"
                            class="mt-3 text-sm text-green-600 hover:text-green-500 focus:outline-none"
                            @click="$refs.fileInput.click()">Change file</button>
                    </div>
                </template>
            </div>

            <p x-show="errors.file" x-text="errors.file" class="mt-1 text-sm text-red-600"></p>
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
                        <li>
                            <template x-for="(file, index) in filePreview">
                                <div :key="file.name">
                                    <strong>File:</strong> <span x-text="file.name"></span>
                                </div>
                            </template>

                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </template>
    <div class="flex justify-center gap-4 mt-8">
        <button type="button" class="px-6 py-2 bg-gray-200 text-gray-700 rounded shadow-sm hover:bg-gray-300 transition"
            x-show="currentStep === 1" @click="closeModal('{{ $id }}')">Cancel</button>
        <button type="button" class="px-6 py-2 bg-gray-200 text-gray-700 rounded shadow-sm hover:bg-gray-300 transition"
            x-show="currentStep > 1" @click="prevStep">Back</button>
        <button type="button" class="px-6 py-2 bg-green-600 text-white rounded shadow-sm hover:bg-green-700 transition"
            x-show="currentStep < 4" @click="nextStep">Next</button>
        <button type="button" class="px-6 py-2 bg-green-600 text-white rounded shadow-sm hover:bg-green-700 transition"
            x-show="currentStep === 4 && !isSubmitting" @click="submitForm">Save</button>
        <span x-show="isSubmitting" class="flex items-center"><svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>Processing...</span>
    </div>
</div>