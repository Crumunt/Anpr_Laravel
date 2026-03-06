<div
    x-data="{
    show: false,
    currentDocument: null,
    showRejectDropdown: false,
    selectedRejectionReason: '',
    customRejectionReason: '',
    rejectionReasons: [
      'Invalid Document',
      'Expired Document',
      'Poor Image Quality',
      'Incorrect Document Type',
      'Missing Information',
      'Document Not Legible',
      'Other (Please specify)'
    ],
    approveDocument() {
      // Handled by wire:click on the button
    },
    rejectDocument() {
      if (!this.selectedRejectionReason) {
        alert('Please select a rejection reason');
        return;
      }
      if (this.selectedRejectionReason === 'Other (Please specify)' && !this.customRejectionReason.trim()) {
        alert('Please provide a custom rejection reason');
        return;
      }
      const reason = this.selectedRejectionReason === 'Other (Please specify)'
        ? this.customRejectionReason
        : this.selectedRejectionReason;
      // Handle reject action
      $wire.rejectDocument();
      this.resetRejectForm();
    },
    markAsPending() {
      // Handle mark as pending action
      $wire.markAsPending();
    },
    resetRejectForm() {
      this.showRejectDropdown = false;
      this.selectedRejectionReason = '';
      this.customRejectionReason = '';
    }
  }"
    @open-document-viewer.window="
    currentDocument = $event.detail.document;
    show = true;
    resetRejectForm();
  "
    @keydown.escape.window="if (show) { show = false; resetRejectForm(); }"
    x-show="$wire.show"
    x-cloak
    class="fixed inset-0 z-[150] overflow-y-auto"
    style="display: none;">
    <!-- Backdrop -->
    <div
        x-show="$wire.show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"
        @click="$wire.closeAndClear()"></div>

    <!-- Modal Content -->
    <div class="flex min-h-full items-center justify-center p-4">
        <div
            x-show="show"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative bg-white rounded-lg shadow-xl max-w-6xl w-full max-h-[90vh] overflow-hidden flex flex-col"
            @click.stop>
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex-1 min-w-0">
                    <h3 class="text-lg font-semibold text-gray-900 truncate">
                        {{ $this->currentDocument['name'] ?? 'Document Viewer' }}
                    </h3>
                    <div class="flex items-center space-x-3 mt-1">
                        <span class="text-sm text-gray-500" x-text="currentDocument?.application"></span>
                        <span class="text-gray-300">•</span>
                        <span class="text-sm text-gray-500" x-text="currentDocument?.applicantType"></span>
                        <span class="text-gray-300">•</span>
                        <span class="text-sm text-gray-500" x-text="currentDocument?.uploaded"></span>
                    </div>
                </div>
                <button
                    @click="$wire.closeAndClear()"
                    class="ml-4 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-lg p-1 transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Body - Document Viewer -->
            <div class="flex-1 overflow-y-auto bg-gray-100 p-4">
                <div class="h-full flex items-center justify-center">

                    <!-- Image Viewer -->
                    @if($this->isImage)
                    <img
                        src="{{ $this->currentDocument['url'] }}"
                        alt="{{ $this->currentDocument['name'] }}"
                        class="max-w-full max-h-full object-contain rounded-lg shadow-lg bg-white" />
                    @endif

                    <!-- PDF Viewer -->
                    @if ($this->isPDF)
                    <object
                        data="{{ $this->currentDocument['url'] }}"
                        key="{{ $this->currentDocument['document_id'] }}"
                        type="application/pdf"
                        class="w-full h-full min-h-[600px] bg-white rounded-lg shadow-inner">
                        <!-- Fallback if PDF can't be embedded -->
                        <div class="flex flex-col items-center justify-center h-full p-8 text-center">
                            <svg class="h-20 w-20 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Cannot display PDF in browser</h3>
                            <p class="text-sm text-gray-500 mb-4">Your browser doesn't support embedded PDFs or the file cannot be displayed.</p>
                        </div>
                    </object>
                    @endif

                    <!-- Fallback for unsupported file types -->
                    @if(!$this->isImage && !$this->isPDF)
                    <div class="flex flex-col items-center justify-center h-full p-8 text-center">
                        <svg class="h-20 w-20 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Preview not available</h3>
                        <p class="text-sm text-gray-500 mb-4">This file type cannot be previewed in the browser.</p>
                        <div class="flex space-x-3">
                            <a
                                href="{{ $this->currentDocument['url'] ?? '#' }}"
                                download
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Download File
                            </a>
                            <a
                                href="{{ $this->currentDocument['url'] ?? '#' }}"
                                target="_blank"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                                Open in New Tab
                            </a>
                        </div>
                    </div>
                    @endif

                </div>
            </div>

            <!-- Modal Footer - Action Buttons -->
            <div class="border-t border-gray-200 bg-white px-6 py-4">
                <!-- Reject Reason Dropdown -->
                <div
                    x-show="showRejectDropdown"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <label class="block text-sm font-medium text-gray-900 mb-2">
                        Select Rejection Reason <span class="text-red-500">*</span>
                    </label>
                    <select
                        x-model="selectedRejectionReason"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm">
                        <option value="">-- Select a reason --</option>
                        <template x-for="reason in rejectionReasons" :key="reason">
                            <option :value="reason" x-text="reason"></option>
                        </template>
                    </select>

                    <!-- Custom Reason Input -->
                    <div
                        x-show="selectedRejectionReason === 'Other (Please specify)'"
                        x-transition
                        class="mt-3">
                        <label class="block text-sm font-medium text-gray-900 mb-2">
                            Please specify the reason <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            x-model="customRejectionReason"
                            rows="3"
                            placeholder="Enter detailed rejection reason..."
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm resize-none"></textarea>
                    </div>

                    <div class="mt-3 flex items-center justify-end space-x-3">
                        <button
                            @click="showRejectDropdown = false; selectedRejectionReason = ''; customRejectionReason = '';"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                            Cancel
                        </button>
                        <button
                            @click="rejectDocument()"
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                            Confirm Rejection
                        </button>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div
                    x-show="!showRejectDropdown"
                    class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <!-- Download Button -->
                        <a
                            :href="currentDocument?.url"
                            download
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Download
                        </a>

                        <!-- Open in New Tab -->
                        <a
                            :href="currentDocument?.url"
                            target="_blank"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                            Open in New Tab
                        </a>
                    </div>

                    <div class="flex items-center space-x-3">
                        <!-- Mark as Pending -->
                        <button
                            @click="markAsPending()"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-yellow-700 bg-yellow-50 border border-yellow-300 rounded-md hover:bg-yellow-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors">
                            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Mark as Pending
                        </button>

                        <!-- Reject -->
                        <button
                            @click="showRejectDropdown = true"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Reject
                        </button>

                        <!-- Approve -->
                        <button
                            wire:click="approveDocument()"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors shadow-sm">
                            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Approve
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@script
<script>
    Livewire.on('clear-cached-document', () => {
        setTimeout(() => {
            $wire.currentDocument = null;
            console.log($wire.currentDocument)
        }, 300);
    })

    Livewire.on('documetUpdated', (data)=> {
        console.log(data);
    })
</script>
@endscript
