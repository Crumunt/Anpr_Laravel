<div class="flex-1 md:ml-64 p-6 pt-24">
    <div class="max-w-6xl mx-auto">
        <!-- Page Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <a href="{{ route('admin.settings') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </a>
                        <h1 class="text-2xl font-bold text-gray-900">Applicant Types</h1>
                    </div>
                    <p class="text-gray-600">Manage applicant types and their required documents</p>
                </div>
                <button
                    wire:click="openTypeModal"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors duration-200 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Add Applicant Type
                </button>
            </div>
        </div>

        <div class="space-y-6">
            <!-- Flash Messages -->
            @if (session()->has('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- Applicant Types Accordion -->
    <div class="space-y-4">
        @forelse($applicantTypes as $type)
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <!-- Accordion Header -->
                <div
                    class="flex items-center justify-between p-4 cursor-pointer hover:bg-gray-50 transition-colors duration-150"
                    wire:click="toggleAccordion('{{ $type->id }}')">
                    <div class="flex items-center gap-4">
                        <!-- Expand/Collapse Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5 text-gray-400 transition-transform duration-200 {{ in_array($type->id, $expandedTypes) ? 'rotate-180' : '' }}"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>

                        <!-- Type Info -->
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-base font-semibold text-gray-900">{{ $type->label }}</h3>
                                @if(!$type->is_active)
                                    <span class="px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-600 rounded-full">Inactive</span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-500">
                                {{ $type->requiredDocuments->count() }} required document{{ $type->requiredDocuments->count() !== 1 ? 's' : '' }}
                                <span class="text-gray-300 mx-2">|</span>
                                <code class="text-xs bg-gray-100 px-1.5 py-0.5 rounded">{{ $type->name }}</code>
                            </p>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-2" wire:click.stop>
                        <button
                            wire:click="openTypeModal('{{ $type->id }}')"
                            class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors duration-150"
                            title="Edit type">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                        <button
                            wire:click="confirmDeleteType('{{ $type->id }}')"
                            class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-150"
                            title="Delete type">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Accordion Content -->
                @if(in_array($type->id, $expandedTypes))
                    <div class="border-t border-gray-100 bg-gray-50/50 p-4">
                        <!-- Type Settings Summary -->
                        <div class="mb-4 flex flex-wrap gap-2">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium rounded-full {{ $type->requires_clsu_id ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                </svg>
                                CLSU ID {{ $type->requires_clsu_id ? 'Required' : 'Optional' }}
                            </span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium rounded-full {{ $type->requires_department ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                Department {{ $type->requires_department ? 'Required' : 'Optional' }}
                            </span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium rounded-full {{ $type->requires_position ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Position {{ $type->requires_position ? 'Required' : 'Optional' }}
                            </span>
                        </div>

                        @if($type->description)
                            <p class="text-sm text-gray-600 mb-4">{{ $type->description }}</p>
                        @endif

                        <!-- Documents List -->
                        <div class="space-y-2">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="text-sm font-semibold text-gray-700">Required Documents</h4>
                                <button
                                    wire:click="openDocumentModal('{{ $type->id }}')"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-green-600 hover:text-green-700 hover:bg-green-50 rounded-lg transition-colors duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Add Document
                                </button>
                            </div>

                            @forelse($type->requiredDocuments as $document)
                                <div class="flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0 w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <p class="text-sm font-medium text-gray-900">{{ $document->label }}</p>
                                                @if($document->is_required)
                                                    <span class="px-1.5 py-0.5 text-xs font-medium bg-red-100 text-red-600 rounded">Required</span>
                                                @else
                                                    <span class="px-1.5 py-0.5 text-xs font-medium bg-gray-100 text-gray-600 rounded">Optional</span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-gray-500">
                                                {{ strtoupper($document->accepted_formats) }} • Max {{ $document->max_file_size_display }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <button
                                            wire:click="openDocumentModal('{{ $type->id }}', '{{ $document->id }}')"
                                            class="p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded transition-colors duration-150"
                                            title="Edit document">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button
                                            wire:click="deleteDocument('{{ $document->id }}')"
                                            wire:confirm="Are you sure you want to remove this document requirement?"
                                            class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded transition-colors duration-150"
                                            title="Remove document">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-6 text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto mb-2 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="text-sm">No documents required for this type yet.</p>
                                    <button
                                        wire:click="openDocumentModal('{{ $type->id }}')"
                                        class="mt-2 text-sm text-green-600 hover:text-green-700 font-medium">
                                        Add the first document requirement
                                    </button>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <div class="text-center py-12 bg-white border border-gray-200 rounded-xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-1">No Applicant Types</h3>
                <p class="text-sm text-gray-500 mb-4">Get started by creating your first applicant type.</p>
                <button
                    wire:click="openTypeModal"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Add Applicant Type
                </button>
            </div>
        @endforelse
    </div>

    <!-- Create/Edit Type Modal -->
    @if($showTypeModal)
        <div class="fixed inset-0 z-[150] overflow-y-auto">
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="fixed inset-0 bg-gray-900/50 transition-opacity" wire:click="closeTypeModal"></div>

                <div class="relative bg-white rounded-xl shadow-xl w-full max-w-lg">
                    <form wire:submit.prevent="saveType">
                        <!-- Header -->
                        <div class="flex items-center justify-between p-5 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">
                                {{ $editingTypeId ? 'Edit Applicant Type' : 'Add Applicant Type' }}
                            </h3>
                            <button type="button" wire:click="closeTypeModal" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Body -->
                        <div class="p-5 space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Display Label <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        wire:model.live="typeLabel"
                                        wire:blur="updateTypeLabel"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        placeholder="e.g., Faculty Member">
                                    @error('typeLabel') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        System Name <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        wire:model="typeName"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent font-mono text-sm"
                                        placeholder="e.g., faculty_member">
                                    <p class="text-xs text-gray-500 mt-1">Lowercase letters and underscores only</p>
                                    @error('typeName') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                    <textarea
                                        wire:model="typeDescription"
                                        rows="2"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        placeholder="Optional description..."></textarea>
                                    @error('typeDescription') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="border-t border-gray-200 pt-4">
                                <h4 class="text-sm font-medium text-gray-900 mb-3">Required Fields</h4>
                                <div class="space-y-3">
                                    <label class="flex items-center gap-3 cursor-pointer">
                                        <input type="checkbox" wire:model="requiresClsuId" class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                        <span class="text-sm text-gray-700">Requires CLSU ID Number</span>
                                    </label>
                                    <label class="flex items-center gap-3 cursor-pointer">
                                        <input type="checkbox" wire:model="requiresDepartment" class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                        <span class="text-sm text-gray-700">Requires Department/College/Unit</span>
                                    </label>
                                    <label class="flex items-center gap-3 cursor-pointer">
                                        <input type="checkbox" wire:model="requiresPosition" class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                        <span class="text-sm text-gray-700">Requires Position/Designation</span>
                                    </label>
                                </div>
                            </div>

                            <div class="border-t border-gray-200 pt-4">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" wire:model="isActive" class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                    <div>
                                        <span class="text-sm font-medium text-gray-700">Active</span>
                                        <p class="text-xs text-gray-500">Inactive types won't appear in the application form</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="flex items-center justify-end gap-3 p-5 border-t border-gray-200 bg-gray-50 rounded-b-xl">
                            <button type="button" wire:click="closeTypeModal" class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors">
                                {{ $editingTypeId ? 'Update' : 'Create' }} Applicant Type
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Create/Edit Document Modal -->
    @if($showDocumentModal)
        <div class="fixed inset-0 z-[150] overflow-y-auto">
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="fixed inset-0 bg-gray-900/50 transition-opacity" wire:click="closeDocumentModal"></div>

                <div class="relative bg-white rounded-xl shadow-xl w-full max-w-lg">
                    <form wire:submit.prevent="saveDocument">
                        <!-- Header -->
                        <div class="flex items-center justify-between p-5 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">
                                {{ $editingDocumentId ? 'Edit Document Requirement' : 'Add Document Requirement' }}
                            </h3>
                            <button type="button" wire:click="closeDocumentModal" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Body -->
                        <div class="p-5 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Document Label <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    wire:model.live="documentLabel"
                                    wire:blur="updateDocumentLabel"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    placeholder="e.g., Certificate of Employment">
                                @error('documentLabel') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    System Name <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    wire:model="documentName"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent font-mono text-sm"
                                    placeholder="e.g., certificate_of_employment">
                                <p class="text-xs text-gray-500 mt-1">Lowercase letters and underscores only</p>
                                @error('documentName') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Description / Help Text</label>
                                <textarea
                                    wire:model="documentDescription"
                                    rows="2"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    placeholder="Instructions or requirements for this document..."></textarea>
                                @error('documentDescription') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Accepted Formats <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        wire:model="acceptedFormats"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        placeholder="pdf,jpg,jpeg,png">
                                    <p class="text-xs text-gray-500 mt-1">Comma-separated extensions</p>
                                    @error('acceptedFormats') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Max File Size (KB) <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="number"
                                        wire:model="maxFileSize"
                                        min="1"
                                        max="51200"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        placeholder="10240">
                                    <p class="text-xs text-gray-500 mt-1">10240 KB = 10 MB</p>
                                    @error('maxFileSize') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="border-t border-gray-200 pt-4">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" wire:model="documentIsRequired" class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                    <div>
                                        <span class="text-sm font-medium text-gray-700">Required Document</span>
                                        <p class="text-xs text-gray-500">Applicants must upload this document to proceed</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="flex items-center justify-end gap-3 p-5 border-t border-gray-200 bg-gray-50 rounded-b-xl">
                            <button type="button" wire:click="closeDocumentModal" class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors">
                                {{ $editingDocumentId ? 'Update' : 'Add' }} Document
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal)
        <div class="fixed inset-0 z-[150] overflow-y-auto">
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="fixed inset-0 bg-gray-900/50 transition-opacity" wire:click="cancelDelete"></div>

                <div class="relative bg-white rounded-xl shadow-xl w-full max-w-md">
                    <div class="p-6 text-center">
                        <div class="w-12 h-12 mx-auto mb-4 rounded-full bg-red-100 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Delete Applicant Type?</h3>
                        <p class="text-sm text-gray-500 mb-6">
                            This will permanently delete this applicant type and all its document requirements. This action cannot be undone.
                        </p>
                        <div class="flex items-center justify-center gap-3">
                            <button
                                wire:click="cancelDelete"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                                Cancel
                            </button>
                            <button
                                wire:click="deleteType"
                                class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
        </div>
    </div>
</div>
