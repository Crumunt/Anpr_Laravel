<div>
    <x-dashboard.buttons :icon="false" class="bg-emerald-600 hover:bg-emerald-700"
        wire:click="openModal">
        <span slot="icon" class="mr-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="M8 12h8"></path>
                <path d="M12 8v8"></path>
            </svg>
        </span>
        <span>Add applicant</span>
    </x-dashboard.buttons>
    <!-- Modal -->
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800/70">
        <div class="bg-white rounded-xl shadow-3xl w-full max-w-3xl">
            <!-- Body -->
            <div class="p-6 max-h-[70vh] overflow-y-auto">
                <form wire:submit.prevent="submitForm">
                    <div>
                        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
                            <div class="bg-white rounded-lg w-full max-w-4xl max-h-[90vh] flex flex-col">

                                <!-- Fixed Header -->
                                <div class="flex items-center justify-between p-6 border-b">
                                    <div>
                                        <h2 class="text-xl font-semibold">Add Applicant</h2>
                                        <p class="text-sm text-gray-500 mt-1">Step {{ $currentStep }} of 4</p>
                                    </div>
                                    <button type="button" class="text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-all duration-150 rounded-full p-2 focus:outline-none focus:ring-2 focus:ring-green-500" wire:click="closeModal">
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>

                                <!-- Progress Indicator -->
                                <div class="px-6 pt-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-medium {{ $currentStep >= 1 ? 'text-green-600' : 'text-gray-400' }}">Personal Info</span>
                                        <span class="text-xs font-medium {{ $currentStep >= 2 ? 'text-green-600' : 'text-gray-400' }}">Vehicle Info</span>
                                        <span class="text-xs font-medium {{ $currentStep >= 3 ? 'text-green-600' : 'text-gray-400' }}">Documents</span>
                                        <span class="text-xs font-medium {{ $currentStep >= 4 ? 'text-green-600' : 'text-gray-400' }}">Review</span>
                                    </div>
                                    <div class="flex gap-2">
                                        <div class="h-1 flex-1 rounded {{ $currentStep >= 1 ? 'bg-green-600' : 'bg-gray-200' }}"></div>
                                        <div class="h-1 flex-1 rounded {{ $currentStep >= 2 ? 'bg-green-600' : 'bg-gray-200' }}"></div>
                                        <div class="h-1 flex-1 rounded {{ $currentStep >= 3 ? 'bg-green-600' : 'bg-gray-200' }}"></div>
                                        <div class="h-1 flex-1 rounded {{ $currentStep >= 4 ? 'bg-green-600' : 'bg-gray-200' }}"></div>
                                    </div>
                                </div>

                                <!-- Scrollable Content -->
                                <div class="flex-1 overflow-y-auto p-6">

                                    <!-- Step 1: Personal Info -->
                                    @if($currentStep === 1)
                                    <div class="space-y-6" x-data="{type: @entangle('applicant_type').live}">

                                        <div>
                                            <h3 class="text-sm font-semibold text-gray-900 mb-4">Basic Information</h3>
                                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">First Name <span class="text-red-500">*</span></label>
                                                    <input type="text" name="first_name"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                                        wire:model="first_name">
                                                    @error('first_name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                                                    <input type="text" name="middle_name"
                                                        placeholder="Optional"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                                        wire:model="middle_name">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Last Name <span class="text-red-500">*</span></label>
                                                    <input type="text" name="last_name"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                                        wire:model="last_name">
                                                    @error('last_name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Suffix</label>
                                                    <input type="text" name="suffix"
                                                        placeholder="Jr., Sr., III"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                                        wire:model="suffix">
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <h3 class="text-sm font-semibold text-gray-900 mb-4">Contact Information</h3>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number <span class="text-red-500">*</span></label>
                                                    <input type="tel" name="phone" placeholder="09123456789"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                                        wire:model="phone">
                                                    @error('phone') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address <span class="text-red-500">*</span></label>
                                                    <input type="email" name="email"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                                        wire:model="email">
                                                    @error('email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <h3 class="text-sm font-semibold text-gray-900 mb-4">Address</h3>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Region <span class="text-red-500">*</span></label>
                                                    <select wire:model.live="selectedRegion"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                                        <option value="">Select Region</option>
                                                        @foreach ($regions as $code => $region)
                                                        <option value="{{ $code }}">{{ $region['region_name'] }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('selectedRegion') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Province <span class="text-red-500">*</span></label>
                                                    <select wire:model.live="selectedProvince"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                                        <option value="">Select Province</option>
                                                        @foreach ($provinces as $province)
                                                        <option value="{{ $province }}">{{ $province }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('selectedProvince') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">City/Municipality <span class="text-red-500">*</span></label>
                                                    <select wire:model.live="selectedMunicipality"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                                        <option value="">Select City/Municipality</option>
                                                        @foreach ($municipalities as $municipality)
                                                        <option value="{{ $municipality }}">{{ $municipality }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('selectedMunicipality') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Barangay <span class="text-red-500">*</span></label>
                                                    <select wire:model.live="selectedBarangay"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                                        <option value="">Select Barangay</option>
                                                        @foreach ($barangays as $barangay)
                                                        <option value="{{ $barangay }}">{{ $barangay }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('selectedBarangay') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Zip Code</label>
                                                    <input type="text" name="zip_code"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                                        wire:model="zip_code">
                                                    @error('zip_code') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <h3 class="text-sm font-semibold text-gray-900 mb-4">CLSU Information</h3>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div class="md:col-span-2">
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Applicant Type <span class="text-red-500">*</span></label>
                                                    <select x-model="type"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                                        <option value="">Select Applicant Type</option>
                                                        <option value="student">Student</option>
                                                        <option value="faculty">Faculty</option>
                                                        <option value="staff">Staff</option>
                                                    </select>
                                                    @error('applicant_type') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                                </div>
                                                <div x-show="['staff','student', 'faculty'].includes(type)">
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">CLSU ID Number <span class="text-red-500">*</span></label>
                                                    <input type="text" name="clsu_id"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                                        wire:model="clsu_id">
                                                    @error('clsu_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                                </div>
                                                <div x-show="['staff','faculty', 'student'].includes(type)">
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">College/Unit/Department <span class="text-red-500">*</span></label>
                                                    <input type="text" name="college_unit_department"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                                        wire:model="department">
                                                    @error('department') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                                </div>
                                                <div x-show="['staff','faculty'].includes(type)" class="md:col-span-2">
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Designation/Position <span class="text-red-500">*</span></label>
                                                    <input type="text" name="position"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                                        wire:model="position">
                                                    @error('position') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    @endif

                                    <!-- Step 2: Vehicle Info -->
                                    @if($currentStep === 2)
                                    <div class="space-y-6">
                                        <div>
                                            <h3 class="text-sm font-semibold text-gray-900 mb-4">Vehicle Details</h3>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Vehicle Type <span class="text-red-500">*</span></label>
                                                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" wire:model="vehicle_type" placeholder="e.g., Sedan, SUV, Motorcycle">
                                                    @error('vehicle_type') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Plate Number <span class="text-red-500">*</span></label>
                                                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" wire:model="plate_number" placeholder="ABC 1234">
                                                    @error('plate_number') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Make <span class="text-red-500">*</span></label>
                                                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" wire:model="make" placeholder="e.g., Toyota, Honda">
                                                    @error('make') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Model <span class="text-red-500">*</span></label>
                                                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" wire:model="model" placeholder="e.g., Vios, Civic">
                                                    @error('model') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Color <span class="text-red-500">*</span></label>
                                                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" wire:model="color" placeholder="e.g., White, Black">
                                                    @error('color') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Year <span class="text-red-500">*</span></label>
                                                    <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" wire:model="year" placeholder="2024" min="1900" max="2100">
                                                    @error('year') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    <!-- Step 3: Upload Documents -->
                                    @if($currentStep === 3)
                                    <div class="space-y-6">
                                        <div>
                                            <h3 class="text-sm font-semibold text-gray-900 mb-2">Required Documents</h3>
                                            <p class="text-sm text-gray-600 mb-6">Please upload all necessary documents for your application</p>

                                            <!-- Vehicle Registration -->
                                            <div class="mb-6">
                                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                                    Vehicle Registration <span class="text-red-500">*</span>
                                                </label>
                                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 hover:border-green-500 transition-colors">
                                                    <input type="file" wire:model="vehicle_registration" id="vehicleRegistration" class="hidden" accept=".pdf,.png,.jpg,.jpeg">
                                                    <label for="vehicleRegistration" class="cursor-pointer flex items-center gap-3">
                                                        <svg class="h-10 w-10 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                        <div class="text-left">
                                                            <p class="text-sm text-gray-600">Click to upload vehicle registration</p>
                                                            <p class="text-xs text-gray-500">PDF, PNG, JPG up to 10MB</p>
                                                        </div>
                                                    </label>
                                                </div>
                                                @error('vehicle_registration') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                                @if($vehicle_registration)
                                                @foreach ($vehicle_registration as $registration)
                                                <div class="mt-2 flex items-center gap-2 p-2 bg-green-50 border border-green-200 rounded-md">
                                                    <svg class="h-5 w-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                    <span class="text-sm text-gray-700">{{ $registration->getClientOriginalName() }}</span>
                                                </div>
                                                @endforeach
                                                @endif
                                            </div>

                                            <!-- Driver's License -->
                                            <div class="mb-6">
                                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                                    Driver's License <span class="text-red-500">*</span>
                                                </label>
                                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 hover:border-green-500 transition-colors">
                                                    <input type="file" wire:model="license" id="license" class="hidden" accept=".pdf,.png,.jpg,.jpeg">
                                                    <label for="license" class="cursor-pointer flex items-center gap-3">
                                                        <svg class="h-10 w-10 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                        <div class="text-left">
                                                            <p class="text-sm text-gray-600">Click to upload driver's license</p>
                                                            <p class="text-xs text-gray-500">PDF, PNG, JPG up to 10MB</p>
                                                        </div>
                                                    </label>
                                                </div>
                                                @error('license') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                                @if($license)
                                                @foreach ($license as $license_files)
                                                <div class="mt-2 flex items-center gap-2 p-2 bg-green-50 border border-green-200 rounded-md">
                                                    <svg class="h-5 w-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                    <span class="text-sm text-gray-700">{{ $license_files->getClientOriginalName() }}</span>
                                                </div>
                                                @endforeach
                                                @endif
                                            </div>

                                            <!-- Proof of Identification -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                                    Proof of Identification <span class="text-red-500">*</span>
                                                </label>
                                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 hover:border-green-500 transition-colors">
                                                    <input type="file" wire:model="proof_of_identification" id="proofOfId" class="hidden" accept=".pdf,.png,.jpg,.jpeg">
                                                    <label for="proofOfId" class="cursor-pointer flex items-center gap-3">
                                                        <svg class="h-10 w-10 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                        <div class="text-left">
                                                            <p class="text-sm text-gray-600">Click to upload proof of identification</p>
                                                            <p class="text-xs text-gray-500">CLSU ID, National ID, Passport (PDF, PNG, JPG up to 10MB)</p>
                                                        </div>
                                                    </label>
                                                </div>
                                                @error('proof_of_identification') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                                @if($proof_of_identification)
                                                @foreach ($proof_of_identification as $identification)
                                                <div class="mt-2 flex items-center gap-2 p-2 bg-green-50 border border-green-200 rounded-md">
                                                    <svg class="h-5 w-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                    <span class="text-sm text-gray-700">{{ $identification->getClientOriginalName() }}</span>
                                                </div>
                                                @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    <!-- Step 4: Review -->
                                    @if($currentStep === 4)
                                    <div class="space-y-6">
                                        <div>
                                            <h3 class="text-sm font-semibold text-gray-900 mb-4">Review Your Information</h3>
                                            <p class="text-sm text-gray-600 mb-6">Please review all information before submitting</p>

                                            <div class="space-y-6">
                                                <div class="bg-gray-50 rounded-lg p-4">
                                                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Personal Information</h4>
                                                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-3">
                                                        <div>
                                                            <dt class="text-xs text-gray-500">Full Name</dt>
                                                            <dd class="text-sm text-gray-900 mt-1">{{ $first_name }} {{ $middle_name }} {{ $last_name }} {{ $suffix ?? '' }}</dd>
                                                        </div>
                                                        <div>
                                                            <dt class="text-xs text-gray-500">Email</dt>
                                                            <dd class="text-sm text-gray-900 mt-1">{{ $email }}</dd>
                                                        </div>
                                                        <div>
                                                            <dt class="text-xs text-gray-500">Phone</dt>
                                                            <dd class="text-sm text-gray-900 mt-1">{{ $phone }}</dd>
                                                        </div>
                                                        <div>
                                                            <dt class="text-xs text-gray-500">Applicant Type</dt>
                                                            <dd class="text-sm text-gray-900 mt-1 capitalize">{{ $applicant_type }}</dd>
                                                        </div>
                                                        @if($clsu_id)
                                                        <div>
                                                            <dt class="text-xs text-gray-500">CLSU ID</dt>
                                                            <dd class="text-sm text-gray-900 mt-1">{{ $clsu_id }}</dd>
                                                        </div>
                                                        @endif
                                                        @if($department)
                                                        <div>
                                                            <dt class="text-xs text-gray-500">Department</dt>
                                                            <dd class="text-sm text-gray-900 mt-1">{{ $department }}</dd>
                                                        </div>
                                                        @endif
                                                    </dl>
                                                </div>

                                                <div class="bg-gray-50 rounded-lg p-4">
                                                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Vehicle Information</h4>
                                                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-3">
                                                        <div>
                                                            <dt class="text-xs text-gray-500">Vehicle Type</dt>
                                                            <dd class="text-sm text-gray-900 mt-1">{{ $vehicle_type }}</dd>
                                                        </div>
                                                        <div>
                                                            <dt class="text-xs text-gray-500">Plate Number</dt>
                                                            <dd class="text-sm text-gray-900 mt-1">{{ $plate_number }}</dd>
                                                        </div>
                                                        <div>
                                                            <dt class="text-xs text-gray-500">Make & Model</dt>
                                                            <dd class="text-sm text-gray-900 mt-1">{{ $make }} {{ $model }}</dd>
                                                        </div>
                                                        <div>
                                                            <dt class="text-xs text-gray-500">Color & Year</dt>
                                                            <dd class="text-sm text-gray-900 mt-1">{{ $color }}, {{ $year }}</dd>
                                                        </div>
                                                    </dl>
                                                </div>

                                                @if($vehicle_registration || $license || $proof_of_identification)
                                                <div class="bg-gray-50 rounded-lg p-4">
                                                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Documents</h4>
                                                    <ul class="space-y-2">
                                                        @if($vehicle_registration)
                                                        <li class="flex items-center gap-2 text-sm text-gray-700">
                                                            <svg class="h-4 w-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                            </svg>
                                                            <span class="font-medium">Vehicle Registration:</span>
                                                            @foreach ($vehicle_registration as $registration)
                                                            <span class="text-sm text-gray-700">{{ $registration->getClientOriginalName() }}</span>
                                                            @endforeach
                                                        </li>
                                                        @endif
                                                        @if($license)
                                                        <li class="flex items-center gap-2 text-sm text-gray-700">
                                                            <svg class="h-4 w-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                            </svg>
                                                            <span class="font-medium">Driver's License:</span>
                                                            @foreach ($license as $license_files)
                                                            <span class="text-sm text-gray-700">{{ $license_files->getClientOriginalName() }}</span>
                                                            @endforeach
                                                        </li>
                                                        @endif
                                                        @if($proof_of_identification)
                                                        <li class="flex items-center gap-2 text-sm text-gray-700">
                                                            <svg class="h-4 w-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                            </svg>
                                                            <span class="font-medium">Proof of ID:</span>
                                                            @foreach ($proof_of_identification as $identification)

                                                            <span class="text-sm text-gray-700">{{ $identification->getClientOriginalName() }}</span>
                                                            @endforeach
                                                        </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                </div>

                                <!-- Fixed Footer with Navigation -->
                                <div class="border-t p-6 bg-gray-50">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            @if($currentStep > 1)
                                            <button type="button" wire:click="prevStep" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                                Back
                                            </button>
                                            @endif
                                        </div>
                                        <div class="flex gap-2">
                                            <button type="button" wire:click="closeModal" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                                Cancel
                                            </button>
                                            @if($currentStep < 4)
                                                <button type="button" wire:click="nextStep" class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                                Next
                                                </button>
                                                @else
                                                <button type="submit" wire:click="submitForm" class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                                    Submit Application
                                                </button>
                                                @endif
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

</div>
