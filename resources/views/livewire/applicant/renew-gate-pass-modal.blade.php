<div>
    <!-- Modal Backdrop -->
    @if($showModal && $vehicle)
    <div
        class="fixed inset-0 z-[60] overflow-y-auto"
        aria-labelledby="modal-title"
        role="dialog"
        aria-modal="true"
        x-data="{ initialized: true }"
        x-init="document.body.classList.add('overflow-hidden')"
        x-on:keydown.escape.window="$wire.closeModal()"
        @close-modal.window="$wire.closeModal()">

        <!-- Background overlay -->
        <div
            class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"
            wire:click="closeModal"
            aria-hidden="true"></div>

        <div class="flex min-h-full items-center justify-center p-2 sm:p-4">
            <!-- Modal panel -->
            <div
                class="relative transform overflow-hidden rounded-2xl bg-white shadow-2xl transition-all w-full max-w-[calc(100vw-1rem)] sm:max-w-2xl"
                @click.stop>
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-cyan-600 via-cyan-700 to-cyan-600 px-4 sm:px-6 py-4 sm:py-5 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3 sm:gap-4">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-sync text-white text-lg sm:text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg sm:text-xl font-bold text-white" id="modal-title">Renew Gate Pass</h3>
                                <p class="text-cyan-100 text-xs sm:text-sm mt-0.5 font-medium">Step {{ $currentStep }} of {{ $totalSteps }}</p>
                            </div>
                        </div>
                        <button
                            type="button"
                            wire:click="closeModal"
                            class="w-9 h-9 flex items-center justify-center text-white/80 hover:text-white hover:bg-white/20 rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-white/50"
                            aria-label="Close modal">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>

                    <!-- Progress bar -->
                    <div class="mt-5">
                        <div class="flex items-center gap-2 mb-3">
                            @for($i = 1; $i <= $totalSteps; $i++)
                                <div class="flex-1">
                                    <div class="h-2.5 rounded-full transition-all duration-300 {{ $i <= $currentStep ? 'bg-white shadow-md' : 'bg-white/30' }}"></div>
                                </div>
                            @endfor
                        </div>
                        <div class="flex justify-between text-[10px] sm:text-xs font-semibold">
                            <span class="transition-colors duration-200 {{ $currentStep >= 1 ? 'text-white' : 'text-cyan-100' }}">
                                <i class="fas fa-file-alt mr-1"></i><span class="hidden sm:inline">Upload </span>Documents
                            </span>
                            <span class="transition-colors duration-200 {{ $currentStep >= 2 ? 'text-white' : 'text-cyan-100' }}">
                                <i class="fas fa-check-circle mr-1"></i>Review<span class="hidden sm:inline"> & Submit</span>
                            </span>
                        </div>
                    </div>
                </div>

                <form wire:submit.prevent="submitRenewal">
                    <!-- Modal Body -->
                    <div class="px-4 sm:px-6 py-4 sm:py-6 max-h-[55vh] sm:max-h-[60vh] overflow-y-auto">
                        <!-- Vehicle Info Banner -->
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 sm:p-4 mb-4 sm:mb-6">
                            <h4 class="text-sm font-semibold text-gray-900 mb-2 sm:mb-3 flex items-center">
                                <i class="fas fa-car text-cyan-600 mr-2"></i>
                                Vehicle Being Renewed
                            </h4>
                            <div class="grid grid-cols-2 gap-2 sm:grid-cols-4 sm:gap-3">
                                <div>
                                    <dt class="text-xs text-gray-500 font-medium">Plate Number</dt>
                                    <dd class="text-sm text-gray-900 mt-1 font-mono font-bold">{{ $vehicle->plate_number ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs text-gray-500 font-medium">Vehicle</dt>
                                    <dd class="text-sm text-gray-900 mt-1">{{ $vehicle->vehicle_info ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs text-gray-500 font-medium">Gate Pass</dt>
                                    <dd class="text-sm text-gray-900 mt-1 font-mono">{{ $vehicle->assigned_gate_pass ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs text-gray-500 font-medium">Expires</dt>
                                    <dd class="text-sm mt-1">
                                        @if($vehicle->expires_at)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $vehicle->expiration_status['class'] }}">
                                                {{ $vehicle->expires_at->format('M d, Y') }}
                                            </span>
                                        @else
                                            <span class="text-gray-500">Not Set</span>
                                        @endif
                                    </dd>
                                </div>
                            </div>
                        </div>

                        <!-- Step 1: Document Upload -->
                        @if($currentStep === 1)
                        <div class="space-y-6">
                            <div class="bg-cyan-50 border border-cyan-200 rounded-lg p-4">
                                <div class="flex">
                                    <i class="fas fa-info-circle text-cyan-500 mt-0.5 mr-3"></i>
                                    <div class="text-sm text-cyan-700">
                                        <p class="font-medium">Renewal Requirements</p>
                                        <p>Please upload updated documents to renew your gate pass. Your existing vehicle information will be carried over.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Vehicle Registration -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-file-alt mr-2 text-cyan-600"></i>
                                    Vehicle Registration (OR/CR) <span class="text-red-500">*</span>
                                </label>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 hover:border-cyan-400 transition-colors @error('files.vehicle_registration.*') border-red-500 @enderror @error('files.vehicle_registration') border-red-500 @enderror">
                                    <input
                                        type="file"
                                        wire:model="files.vehicle_registration"
                                        multiple
                                        accept=".pdf,.jpg,.jpeg,.png"
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-cyan-50 file:text-cyan-700 hover:file:bg-cyan-100">
                                    <p class="mt-2 text-xs text-gray-500">Upload your updated Official Receipt (OR) and Certificate of Registration (CR)</p>
                                </div>
                                <div wire:loading wire:target="files.vehicle_registration" class="mt-3">
                                    <div class="flex items-center gap-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                        <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-blue-700">Uploading...</span>
                                    </div>
                                </div>
                                @error('files.vehicle_registration')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                                @error('files.vehicle_registration.*')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                                @if(isset($files['vehicle_registration']) && count($files['vehicle_registration']) > 0)
                                    <div class="mt-2 space-y-1" wire:loading.remove wire:target="files.vehicle_registration">
                                        @foreach($files['vehicle_registration'] as $index => $file)
                                            <div class="flex items-center justify-between bg-cyan-50 border border-cyan-200 rounded-lg px-3 py-2">
                                                <div class="flex items-center gap-2">
                                                    <svg class="h-5 w-5 text-cyan-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                    <span class="text-sm text-gray-700 truncate">{{ $file->getClientOriginalName() }}</span>
                                                </div>
                                                <button type="button" wire:click="removeFile('vehicle_registration', {{ $index }})" class="text-red-500 hover:text-red-700">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <!-- Driver's License -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-id-card mr-2 text-cyan-600"></i>
                                    Driver's License <span class="text-red-500">*</span>
                                </label>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 hover:border-cyan-400 transition-colors @error('files.license.*') border-red-500 @enderror @error('files.license') border-red-500 @enderror">
                                    <input
                                        type="file"
                                        wire:model="files.license"
                                        multiple
                                        accept=".pdf,.jpg,.jpeg,.png"
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-cyan-50 file:text-cyan-700 hover:file:bg-cyan-100">
                                    <p class="mt-2 text-xs text-gray-500">Upload a clear copy of your valid driver's license (front and back)</p>
                                </div>
                                <div wire:loading wire:target="files.license" class="mt-3">
                                    <div class="flex items-center gap-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                        <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-blue-700">Uploading...</span>
                                    </div>
                                </div>
                                @error('files.license')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                                @error('files.license.*')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                                @if(isset($files['license']) && count($files['license']) > 0)
                                    <div class="mt-2 space-y-1" wire:loading.remove wire:target="files.license">
                                        @foreach($files['license'] as $index => $file)
                                            <div class="flex items-center justify-between bg-cyan-50 border border-cyan-200 rounded-lg px-3 py-2">
                                                <div class="flex items-center gap-2">
                                                    <svg class="h-5 w-5 text-cyan-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                    <span class="text-sm text-gray-700 truncate">{{ $file->getClientOriginalName() }}</span>
                                                </div>
                                                <button type="button" wire:click="removeFile('license', {{ $index }})" class="text-red-500 hover:text-red-700">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <!-- Proof of Identification -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-user-check mr-2 text-cyan-600"></i>
                                    Proof of Identification <span class="text-red-500">*</span>
                                </label>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 hover:border-cyan-400 transition-colors @error('files.proof_of_identification.*') border-red-500 @enderror @error('files.proof_of_identification') border-red-500 @enderror">
                                    <input
                                        type="file"
                                        wire:model="files.proof_of_identification"
                                        multiple
                                        accept=".pdf,.jpg,.jpeg,.png"
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-cyan-50 file:text-cyan-700 hover:file:bg-cyan-100">
                                    <p class="mt-2 text-xs text-gray-500">Upload a valid government ID (CLSU ID, School ID, etc.)</p>
                                </div>
                                <div wire:loading wire:target="files.proof_of_identification" class="mt-3">
                                    <div class="flex items-center gap-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                        <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-blue-700">Uploading...</span>
                                    </div>
                                </div>
                                @error('files.proof_of_identification')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                                @error('files.proof_of_identification.*')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                                @if(isset($files['proof_of_identification']) && count($files['proof_of_identification']) > 0)
                                    <div class="mt-2 space-y-1" wire:loading.remove wire:target="files.proof_of_identification">
                                        @foreach($files['proof_of_identification'] as $index => $file)
                                            <div class="flex items-center justify-between bg-cyan-50 border border-cyan-200 rounded-lg px-3 py-2">
                                                <div class="flex items-center gap-2">
                                                    <svg class="h-5 w-5 text-cyan-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                    <span class="text-sm text-gray-700 truncate">{{ $file->getClientOriginalName() }}</span>
                                                </div>
                                                <button type="button" wire:click="removeFile('proof_of_identification', {{ $index }})" class="text-red-500 hover:text-red-700">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Step 2: Review -->
                        @if($currentStep === 2)
                        <div class="space-y-6">
                            <div class="bg-cyan-50 border border-cyan-200 rounded-lg p-4">
                                <div class="flex">
                                    <svg class="w-5 h-5 text-cyan-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div class="text-sm text-cyan-700">
                                        <p class="font-medium">Review Your Renewal Request</p>
                                        <p>Please review all details before submitting your gate pass renewal request.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Documents Summary -->
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <h4 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                                    <i class="fas fa-file-alt text-cyan-600 mr-2"></i>
                                    Uploaded Documents
                                </h4>
                                <div class="space-y-3">
                                    @foreach(['vehicle_registration' => 'Vehicle Registration (OR/CR)', 'license' => "Driver's License", 'proof_of_identification' => 'Proof of Identification'] as $key => $label)
                                        <div class="flex items-start justify-between">
                                            <div class="flex items-center gap-2">
                                                <i class="fas fa-check-circle text-cyan-600"></i>
                                                <span class="text-sm text-gray-700">{{ $label }}</span>
                                            </div>
                                            <span class="text-xs text-gray-500">
                                                {{ isset($files[$key]) ? count($files[$key]) : 0 }} file(s)
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Renewal Info -->
                            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                                <div class="flex">
                                    <i class="fas fa-exclamation-triangle text-amber-500 mt-0.5 mr-3"></i>
                                    <div class="text-sm text-amber-700">
                                        <p class="font-medium">Important Notice</p>
                                        <p>Once approved, your gate pass will be renewed for another {{ config('anpr.gate_pass.default_validity_years', 4) }} years from the approval date. Your current gate pass number will be retained.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- UBAP Office Verification Notice -->
                            <div class="bg-cyan-50 border border-cyan-200 rounded-lg p-4">
                                <div class="flex">
                                    <i class="fas fa-building text-cyan-500 mt-0.5 mr-3"></i>
                                    <div class="text-sm text-cyan-700">
                                        <p class="font-medium">Document Verification Required</p>
                                        <p>Please bring a <strong>xerox copy</strong> of all submitted documents to the <strong>UBAP Office</strong> for verification.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Modal Footer -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
                        @if($currentStep > 1)
                            <button
                                type="button"
                                wire:click="prevStep"
                                class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 transition-colors">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Previous
                            </button>
                        @else
                            <button
                                type="button"
                                wire:click="closeModal"
                                class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 transition-colors">
                                Cancel
                            </button>
                        @endif

                        @if($currentStep < $totalSteps)
                            <button
                                type="button"
                                wire:click="nextStep"
                                wire:loading.attr="disabled"
                                class="inline-flex items-center px-5 py-2.5 text-sm font-semibold text-white bg-cyan-600 rounded-lg hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                Next
                                <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        @else
                            <button
                                type="submit"
                                wire:loading.attr="disabled"
                                class="inline-flex items-center px-5 py-2.5 text-sm font-semibold text-white bg-cyan-600 rounded-lg hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                <span wire:loading.remove wire:target="submitRenewal">
                                    <i class="fas fa-sync mr-2"></i>
                                    Submit Renewal Request
                                </span>
                                <span wire:loading wire:target="submitRenewal" class="flex items-center gap-2">
                                    <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Submitting...
                                </span>
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
