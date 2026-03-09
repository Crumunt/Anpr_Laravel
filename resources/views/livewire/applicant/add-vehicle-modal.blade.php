<div>
    <!-- Modal Backdrop -->
    @if($showModal)
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
                <div class="bg-gradient-to-r from-green-800 via-green-700 to-green-800 px-4 sm:px-6 py-4 sm:py-5 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3 sm:gap-4">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-car text-white text-lg sm:text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg sm:text-xl font-bold text-white" id="modal-title">Register New Vehicle</h3>
                                <p class="text-green-200 text-xs sm:text-sm mt-0.5 font-medium">Step {{ $currentStep }} of {{ $totalSteps }}</p>
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
                                    <div class="h-2 rounded-full transition-all duration-300 {{ $i <= $currentStep ? 'bg-yellow-400 shadow-md' : 'bg-white/25' }}"></div>
                                </div>
                            @endfor
                        </div>
                        <div class="flex justify-between text-[10px] sm:text-xs font-semibold">
                            <span class="transition-colors duration-200 {{ $currentStep >= 1 ? 'text-white' : 'text-green-200' }}">
                                <i class="fas fa-car mr-1"></i><span class="hidden sm:inline">Vehicle </span>Details
                            </span>
                            <span class="transition-colors duration-200 {{ $currentStep >= 2 ? 'text-white' : 'text-green-200' }}">
                                <i class="fas fa-file-alt mr-1"></i>Documents
                            </span>
                            <span class="transition-colors duration-200 {{ $currentStep >= 3 ? 'text-white' : 'text-green-200' }}">
                                <i class="fas fa-check-circle mr-1"></i>Review
                            </span>
                        </div>
                    </div>
                </div>

                <form wire:submit.prevent="submitForm">
                    <!-- Modal Body -->
                    <div class="px-4 sm:px-6 py-4 sm:py-6 max-h-[55vh] sm:max-h-[60vh] overflow-y-auto">
                    <!-- Step 1: Vehicle Details -->
                    @if($currentStep === 1)
                    <div class="space-y-4 sm:space-y-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                            <!-- Vehicle Type -->
                            <div>
                                <label for="vehicle_type" class="block text-sm font-medium text-gray-700 mb-1">
                                    Vehicle Type <span class="text-red-500">*</span>
                                </label>
                                <select
                                    wire:model="vehicle_type"
                                    id="vehicle_type"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('vehicle_type') border-red-500 @enderror">
                                    <option value="">Select type</option>
                                    <option value="motorcycle">Motorcycle</option>
                                    <option value="sedan">Sedan</option>
                                    <option value="suv">SUV</option>
                                    <option value="van">Van</option>
                                    <option value="truck">Truck</option>
                                    <option value="pickup">Pickup</option>
                                    <option value="other">Other</option>
                                </select>
                                @error('vehicle_type')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Plate Number -->
                            <div>
                                <label for="plate_number" class="block text-sm font-medium text-gray-700 mb-1">
                                    Plate Number <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    wire:model="plate_number"
                                    id="plate_number"
                                    placeholder="e.g., ABC 1234"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('plate_number') border-red-500 @enderror">
                                @error('plate_number')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Make -->
                            <div>
                                <label for="make" class="block text-sm font-medium text-gray-700 mb-1">
                                    Make <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    wire:model="make"
                                    id="make"
                                    placeholder="e.g., Toyota, Honda"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('make') border-red-500 @enderror">
                                @error('make')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Model -->
                            <div>
                                <label for="model" class="block text-sm font-medium text-gray-700 mb-1">
                                    Model <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    wire:model="model"
                                    id="model"
                                    placeholder="e.g., Vios, Civic"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('model') border-red-500 @enderror">
                                @error('model')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Year -->
                            <div>
                                <label for="year" class="block text-sm font-medium text-gray-700 mb-1">
                                    Year <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="number"
                                    wire:model="year"
                                    id="year"
                                    placeholder="e.g., 2020"
                                    min="1900"
                                    max="{{ date('Y') + 1 }}"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('year') border-red-500 @enderror">
                                @error('year')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Color -->
                            <div>
                                <label for="color" class="block text-sm font-medium text-gray-700 mb-1">
                                    Color <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    wire:model="color"
                                    id="color"
                                    placeholder="e.g., White, Black, Silver"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('color') border-red-500 @enderror">
                                @error('color')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Step 2: Document Upload -->
                    @if($currentStep === 2)
                    <div class="space-y-6">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex">
                                <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-3"></i>
                                <div class="text-sm text-blue-700">
                                    <p class="font-medium">Required Documents</p>
                                    <p>Please upload the following documents based on your applicant type.</p>
                                </div>
                            </div>
                        </div>

                        @forelse($requiredDocuments as $document)
                            @php
                                $docName = $document['name'];
                                $docLabel = $document['label'];
                                $docDescription = $document['description'] ?? '';
                                $acceptedFormats = $document['accepted_formats'] ?? 'pdf,jpg,jpeg,png';
                                $maxFileSize = $document['max_file_size'] ?? 10240;
                                $isRequired = $document['is_required'] ?? true;
                                $acceptString = '.' . str_replace(',', ',.', $acceptedFormats);
                            @endphp
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-file-alt mr-2 text-green-700"></i>
                                    {{ $docLabel }} @if($isRequired)<span class="text-red-500">*</span>@endif
                                </label>
                                <div class="border-2 border-dashed border-gray-300 rounded-xl p-4 hover:border-green-400 transition-colors @error('files.'.$docName.'.*') border-red-500 @enderror @error('files.'.$docName) border-red-500 @enderror">
                                    <input
                                        type="file"
                                        wire:model="files.{{ $docName }}"
                                        multiple
                                        accept="{{ $acceptString }}"
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                                    @if($docDescription)
                                        <p class="mt-2 text-xs text-gray-500">{{ $docDescription }}</p>
                                    @endif
                                    <p class="mt-1 text-xs text-gray-400">Accepted formats: {{ strtoupper($acceptedFormats) }} (max {{ round($maxFileSize / 1024) }}MB)</p>
                                </div>
                                <!-- Loading Spinner -->
                                <div wire:loading wire:target="files.{{ $docName }}" class="mt-3">
                                    <div class="flex items-center gap-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                        <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-blue-700">Uploading {{ $docLabel }}...</span>
                                    </div>
                                </div>
                                @error('files.'.$docName)
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                                @error('files.'.$docName.'.*')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                                @if(isset($files[$docName]) && count($files[$docName]) > 0)
                                    <div class="mt-2 space-y-1" wire:loading.remove wire:target="files.{{ $docName }}">
                                        @foreach($files[$docName] as $index => $file)
                                            <div class="flex items-center justify-between bg-green-50 border border-green-200 rounded-lg px-3 py-2">
                                                <div class="flex items-center gap-2">
                                                    <svg class="h-5 w-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                    <span class="text-sm text-gray-700 truncate">{{ $file->getClientOriginalName() }}</span>
                                                </div>
                                                <button type="button" wire:click="removeFile('{{ $docName }}', {{ $index }})" class="text-red-500 hover:text-red-700">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                                <div class="flex">
                                    <i class="fas fa-exclamation-triangle text-amber-500 mt-0.5 mr-3"></i>
                                    <div class="text-sm text-amber-700">
                                        <p class="font-medium">No Documents Required</p>
                                        <p>No documents are required for your applicant type. You may proceed to the next step.</p>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                    @endif

                    <!-- Step 3: Review -->
                    @if($currentStep === 3)
                    <div class="space-y-6">
                        <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                            <div class="flex">
                                <svg class="w-5 h-5 text-green-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div class="text-sm text-green-700">
                                    <p class="font-medium">Review Your Application</p>
                                    <p>Please review all details before submitting your vehicle registration.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Vehicle Information Summary -->
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <h4 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                                <i class="fas fa-car text-green-700 mr-2"></i>
                                Vehicle Information
                            </h4>
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-3">
                                <div>
                                    <dt class="text-xs text-gray-500 font-medium">Vehicle Type</dt>
                                    <dd class="text-sm text-gray-900 mt-1">{{ ucfirst($vehicle_type ?? 'N/A') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs text-gray-500 font-medium">Plate Number</dt>
                                    <dd class="text-sm text-gray-900 mt-1 font-mono">{{ strtoupper($plate_number ?? 'N/A') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs text-gray-500 font-medium">Make</dt>
                                    <dd class="text-sm text-gray-900 mt-1">{{ $make ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs text-gray-500 font-medium">Model</dt>
                                    <dd class="text-sm text-gray-900 mt-1">{{ $model ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs text-gray-500 font-medium">Color</dt>
                                    <dd class="text-sm text-gray-900 mt-1">{{ ucfirst($color ?? 'N/A') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs text-gray-500 font-medium">Year</dt>
                                    <dd class="text-sm text-gray-900 mt-1">{{ $year ?? 'N/A' }}</dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Documents Summary -->
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <h4 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                                <i class="fas fa-file-alt text-green-700 mr-2"></i>
                                Uploaded Documents
                            </h4>
                            <ul class="space-y-2">
                                @foreach($requiredDocuments as $document)
                                    @php
                                        $docName = $document['name'];
                                        $docLabel = $document['label'];
                                        $fileCount = isset($files[$docName]) ? count($files[$docName]) : 0;
                                    @endphp
                                    @if($fileCount > 0)
                                    <li class="flex items-center text-sm">
                                        <svg class="h-4 w-4 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="font-medium text-gray-700">{{ $docLabel }}:</span>
                                        <span class="text-gray-600 ml-1">{{ $fileCount }} file(s)</span>
                                    </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>

                        <!-- Terms Notice -->
                        <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                            <div class="flex">
                                <i class="fas fa-exclamation-triangle text-amber-500 mt-0.5 mr-3"></i>
                                <div class="text-sm text-amber-700">
                                    <p class="font-medium">Important Notice</p>
                                    <p>By submitting this application, you confirm that all information provided is accurate and the documents are authentic. False information may result in rejection of your application.</p>
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

                    <!-- Enhanced Modal Footer -->
                    <div class="px-6 py-5 bg-gray-50 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-3 w-full sm:w-auto">
                            @if($currentStep > 1)
                                <button
                                    type="button"
                                    wire:click="prevStep"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 font-semibold focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                    <i class="fas fa-arrow-left text-xs"></i>
                                    <span>Back</span>
                                </button>
                            @endif
                        </div>
                        <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                            <button
                                type="button"
                                wire:click="closeModal"
                                class="px-5 py-2.5 text-gray-600 hover:text-gray-800 font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 rounded-xl">
                                Cancel
                            </button>

                            @if($currentStep < $totalSteps)
                                <button
                                    type="button"
                                    wire:click="nextStep"
                                    wire:loading.attr="disabled"
                                    wire:target="nextStep"
                                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-green-700 to-green-600 text-white font-semibold rounded-xl hover:from-green-800 hover:to-green-700 shadow-md hover:shadow-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                    <span wire:loading.remove wire:target="nextStep">
                                        <span>Next</span>
                                        <i class="fas fa-arrow-right ml-1"></i>
                                    </span>
                                    <span wire:loading wire:target="nextStep" class="flex items-center gap-2">
                                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span>Validating...</span>
                                    </span>
                                </button>
                            @else
                                <button
                                    type="submit"
                                    wire:loading.attr="disabled"
                                    wire:loading.class="opacity-75 cursor-not-allowed"
                                    wire:target="submitForm"
                                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-green-700 to-green-600 text-white font-semibold rounded-xl hover:from-green-800 hover:to-green-700 shadow-md hover:shadow-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                    <span wire:loading.remove wire:target="submitForm" class="flex items-center gap-2">
                                        <i class="fas fa-paper-plane"></i>
                                        <span>Submit Application</span>
                                    </span>
                                    <span wire:loading wire:target="submitForm" class="flex items-center gap-2">
                                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span>Submitting...</span>
                                    </span>
                                </button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
