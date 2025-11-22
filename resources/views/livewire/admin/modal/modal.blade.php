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
            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b">
                <h2 class="text-xl font-semibold text-gray-800">
                    Add Applicant
                </h2>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-700 p-2 rounded-full">
                    ✕
                </button>
            </div>

            <!-- Body -->
            <div class="p-6 max-h-[70vh] overflow-y-auto">
                <form wire:submit.prevent="submitForm">
                    <div>
                        @if($showModal)
                        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
                            <div class="bg-white rounded-lg w-full max-w-3xl p-6 relative">

                                <!-- Close button -->
                                <button class="absolute top-3 right-3 text-gray-500 hover:text-gray-800" wire:click="closeModal">&times;</button>

                                <h2 class="text-xl font-semibold mb-4">Add Applicant</h2>

                                <!-- Step 1: Personal Info -->
                                @if($currentStep === 1)
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                                        <input type="text" name="full_name"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                            wire:model="full_name">
                                        @error('full_name') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                                        <input type="text" name="middle_name"
                                            placeholder="Leave blank if not applicable."
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent placeholder:text-gray-500 placeholder:italic placeholder:text-sm"
                                            wire:model="middle_name">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                                        <input type="text" name="address"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                            wire:model="last_name">
                                        @error('last_name') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                        <input type="tel" name="phone" placeholder="09123456789"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent placeholder:text-gray-500 placeholder:italic placeholder:text-sm"
                                            wire:model="phone">
                                        @error('phone') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                        <input type="email" name="email"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                            wire:model="email">
                                        @error('email') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Zip Code</label>
                                        <input type="text" name="college_unit_department"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                            required>

                                    </div>
                                    <div class="space-y-6">
                                        <div class="space-y-2">
                                            <label class="block text-sm font-medium text-gray-700">Region</label>
                                            <select wire:model.live="selectedRegion"
                                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md">
                                                <option value="">Select Region</option>
                                                @foreach ($regions as $code => $region)
                                                    <option value="{{ $code }}">{{ $region['region_name'] }}</option>
                                                @endforeach
                                            </select>
                                            @error('selectedRegion') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                    <div class="space-y-6">
                                        <div class="space-y-2">
                                            <label class="block text-sm font-medium text-gray-700">Province</label>
                                            <select wire:model.live="selectedProvince"
                                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md"
                                                >
                                                <option value="">Select province</option>
                                                @foreach ($provinces as $province)
                                                    <option value="{{ $province }}">{{ $province }}</option>
                                                @endforeach
                                            </select>
                                            @error('selectedProvince') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                    <div class="space-y-6">
                                        <div class="space-y-2">
                                            <label class="block text-sm font-medium text-gray-700">City</label>
                                            <select wire:model.live="selectedMunicipality"
                                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md"
                                                >
                                                <option value="">Select city</option>
                                                @foreach ($municipalities as $municipality)
                                                    <option value="{{ $municipality }}">{{ $municipality }}</option>
                                                @endforeach
                                            </select>
                                            @error('selectedMunicipality') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                    <div class="space-y-6">
                                        <div class="space-y-2">
                                            <label class="block text-sm font-medium text-gray-700">Barangay</label>
                                            <select wire:model.live="selectedBarangay"
                                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md"
                                                >
                                                <option value="">Select barangay</option>
                                                @foreach ($barangays as $barangay)
                                                    <option value="{{ $barangay }}">{{ $barangay }}</option>
                                                @endforeach
                                            </select>
                                            @error('selectedBarangay') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Applicant Type</label>
                                        <select wire:model.live="applicant_type"
                                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md"
                                            >
                                            <option value="">Select applicant type</option>
                                            <option value="student">Student</option>
                                            <option value="faculty">Faculty</option>
                                            <option value="staff">Staff</option>
                                        </select>
                                        @error('applicant_type') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                                    </div>

                                    @if(in_array($applicant_type, ['student','faculty','staff']))
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">CLSU ID Number</label>
                                        <input type="text" name="clsu_id"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                            wire:model="clsu_id">
                                        @error('clsu_id') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">College/Unit/Department</label>
                                        <input type="text" name="college_unit_department"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                            wire:model="department">
                                        @error('department') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Designation/Position</label>
                                        <input type="text" name="college_unit_department"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                            wire:model="position">
                                        @error('position') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                                    </div>
                                    @endif
                                </div>
                                @endif

                                <!-- Step 2: Vehicle Info -->
                                @if($currentStep === 2)
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label>Vehicle Type</label>
                                        <input type="text" class="w-full border rounded px-2 py-1" wire:model="vehicle_type">
                                        @error('vehicle_type') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label>Make</label>
                                        <input type="text" class="w-full border rounded px-2 py-1" wire:model="make">
                                        @error('make') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label>Model</label>
                                        <input type="text" class="w-full border rounded px-2 py-1" wire:model="model">
                                        @error('model') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label>Color</label>
                                        <input type="text" class="w-full border rounded px-2 py-1" wire:model="color">
                                        @error('color') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label>Year</label>
                                        <input type="number" class="w-full border rounded px-2 py-1" wire:model="year">
                                        @error('year') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label>Plate Number</label>
                                        <input type="text" class="w-full border rounded px-2 py-1" wire:model="plate_number">
                                        @error('plate_number') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                                @endif

                                <!-- Step 3: Upload Documents -->
                                @if($currentStep === 3)
                                <div>
                                    <label>Upload Documents</label>
                                    <input type="file" wire:model="files" multiple>
                                    @error('files.*') <p class="text-red-600">{{ $message }}</p> @enderror

                                    @if($files)
                                    <ul class="mt-2">
                                        @foreach($files as $file)
                                        <li>{{ $file->getClientOriginalName() }}</li>
                                        @endforeach
                                    </ul>
                                    @endif
                                </div>
                                @endif

                                <!-- Step 4: Review -->
                                @if($currentStep === 4)
                                <div>
                                    <h3 class="font-semibold text-lg mb-2">Review Information</h3>
                                    <p><strong>Full Name:</strong> {{ $full_name }}</p>
                                    <p><strong>Last Name:</strong> {{ $last_name }}</p>
                                    <p><strong>Email:</strong> {{ $email }}</p>
                                    <p><strong>Applicant Type:</strong> {{ $applicant_type }}</p>
                                    <p><strong>Vehicle Type:</strong> {{ $vehicle_type }}</p>
                                    <p><strong>Plate Number:</strong> {{ $plate_number }}</p>
                                    <p><strong>Files:</strong></p>
                                    <ul>
                                        @foreach($files as $file)
                                        <li>{{ $file->getClientOriginalName() }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif

                                <!-- Navigation buttons -->
                                <div class="flex justify-end gap-2 mt-4">
                                    @if($currentStep > 1)
                                    <button wire:click="prevStep" class="px-4 py-2 border rounded">Back</button>
                                    @endif
                                    @if($currentStep < 4)
                                        <button wire:click="nextStep" class="px-4 py-2 bg-green-600 text-white rounded">Next</button>
                                        @else
                                        <button wire:click="submitForm" class="px-4 py-2 bg-green-600 text-white rounded">Submit</button>
                                        @endif
                                        <button wire:click="closeModal" class="px-4 py-2 border rounded">Cancel</button>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                </form>
            </div>
        </div>
    </div>
    @endif

</div>
