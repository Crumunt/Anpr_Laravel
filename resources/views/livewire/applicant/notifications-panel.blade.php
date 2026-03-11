<div>
    <!-- Rejected Documents Section -->
    @if(count($rejectedDocuments) > 0)
    <div class="mb-8">
        <div class="bg-red-50 border border-red-200 rounded-xl overflow-hidden">
            <div class="px-6 py-4 bg-red-100 border-b border-red-200 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-red-200 rounded-xl flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-red-800">Action Required: Rejected Documents</h3>
                        <p class="text-sm text-red-600">{{ count($rejectedDocuments) }} document(s) need your attention</p>
                    </div>
                </div>
            </div>

            <div class="divide-y divide-red-100">
                @foreach($rejectedDocuments as $document)
                <div class="p-4 hover:bg-red-100/50 transition-colors">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-start gap-4 flex-1">
                            <div class="w-12 h-12 bg-red-200 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-file-alt text-red-600"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-semibold text-gray-900">{{ $document['type_name'] }}</span>
                                    <span class="text-xs px-2 py-0.5 bg-red-200 text-red-800 rounded-full font-medium">Rejected</span>
                                    @if($document['version'] > 1)
                                    <span class="text-xs px-2 py-0.5 bg-gray-200 text-gray-700 rounded-full">v{{ $document['version'] }}</span>
                                    @endif
                                </div>

                                @if($document['vehicle_plate'] !== 'N/A')
                                <p class="text-sm text-gray-600 mb-2">
                                    <i class="fas fa-car mr-1 text-gray-400"></i>
                                    Vehicle: <span class="font-mono font-medium">{{ $document['vehicle_plate'] }}</span>
                                </p>
                                @endif

                                @if($document['rejection_reason'])
                                <div class="bg-white border border-red-200 rounded-lg p-3 mb-3">
                                    <p class="text-xs text-red-600 font-medium uppercase tracking-wide mb-1">
                                        <i class="fas fa-info-circle mr-1"></i> Rejection Reason
                                    </p>
                                    <p class="text-sm text-gray-700">{{ $document['rejection_reason'] }}</p>
                                </div>
                                @endif

                                @if($document['reviewed_at'])
                                <p class="text-xs text-gray-500">
                                    <i class="fas fa-clock mr-1"></i> Reviewed on {{ $document['reviewed_at'] }}
                                </p>
                                @endif
                            </div>
                        </div>

                        <div class="flex flex-col gap-2 flex-shrink-0">
                            <button
                                wire:click="openResubmitModal('{{ $document['id'] }}')"
                                class="inline-flex items-center px-4 py-2 bg-[#1a5c1f] text-white text-sm font-semibold rounded-lg hover:bg-green-800 transition-colors shadow-sm">
                                <i class="fas fa-upload mr-2"></i>
                                Resubmit
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Notifications List -->
    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-bell text-blue-600"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900">Notifications</h3>
                    <p class="text-sm text-gray-500">
                        @if($unreadCount > 0)
                        {{ $unreadCount }} unread notification(s)
                        @else
                        All caught up!
                        @endif
                    </p>
                </div>
            </div>

            @if($unreadCount > 0)
            <button
                wire:click="markAllAsRead"
                class="text-sm text-green-700 hover:text-green-800 font-semibold transition-colors">
                Mark all as read
            </button>
            @endif
        </div>

        @if(count($notifications) > 0)
        <div class="divide-y divide-gray-100 max-h-96 overflow-y-auto">
            @foreach($notifications as $notification)
            <div class="p-4 hover:bg-gray-50 transition-colors {{ $notification['is_unread'] ? 'bg-blue-50/50' : '' }}">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0
                        {{ $notification['type'] === 'document_rejected' ? 'bg-red-100' : ($notification['type'] === 'application_rejected' ? 'bg-orange-100' : 'bg-blue-100') }}">
                        @if($notification['type'] === 'document_rejected')
                        <i class="fas fa-file-times text-red-600"></i>
                        @elseif($notification['type'] === 'application_rejected')
                        <i class="fas fa-times-circle text-orange-600"></i>
                        @else
                        <i class="fas fa-bell text-blue-600"></i>
                        @endif
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <p class="text-sm text-gray-900 {{ $notification['is_unread'] ? 'font-semibold' : '' }}">
                                    {{ $notification['message'] }}
                                </p>
                                @if($notification['rejection_reason'])
                                <p class="text-xs text-gray-600 mt-1">
                                    <span class="font-medium">Reason:</span> {{ $notification['rejection_reason'] }}
                                </p>
                                @endif
                                <p class="text-xs text-gray-400 mt-1">{{ $notification['created_at'] }}</p>
                            </div>

                            @if($notification['is_unread'])
                            <button
                                wire:click="markAsRead('{{ $notification['id'] }}')"
                                class="text-xs text-blue-600 hover:text-blue-700 font-medium flex-shrink-0">
                                Mark read
                            </button>
                            @endif
                        </div>

                        @if($notification['can_resubmit'] && $notification['document_id'])
                        <button
                            wire:click="openResubmitModal('{{ $notification['document_id'] }}')"
                            class="mt-2 inline-flex items-center px-3 py-1.5 bg-green-50 text-green-700 text-xs font-semibold rounded-lg hover:bg-green-100 transition-colors border border-green-100">
                            <i class="fas fa-upload mr-1.5"></i>
                            Resubmit Document
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="p-12 text-center">
            <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4 border-2 border-dashed border-gray-200">
                <i class="fas fa-bell-slash text-gray-300 text-2xl"></i>
            </div>
            <p class="text-gray-500 font-medium">No notifications yet</p>
            <p class="text-sm text-gray-400 mt-1">We'll notify you when there are updates</p>
        </div>
        @endif
    </div>

    <!-- Resubmit Document Modal -->
    @if($showResubmitModal)
    <div
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title"
        role="dialog"
        aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div
                wire:click="closeResubmitModal"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                aria-hidden="true"></div>

            <!-- Modal panel -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form wire:submit.prevent="resubmitDocument">
                    <div class="bg-white px-6 pt-6 pb-4">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-upload text-green-600 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900" id="modal-title">
                                    Resubmit Document
                                </h3>
                                <p class="text-sm text-gray-500">Upload a corrected version</p>
                            </div>
                        </div>

                        @if($selectedDocument)
                        <div class="bg-gray-50 rounded-xl p-4 mb-6">
                            <div class="flex items-center gap-3 mb-3">
                                <i class="fas fa-file-alt text-gray-400"></i>
                                <span class="font-medium text-gray-900">{{ $selectedDocument['type_name'] }}</span>
                            </div>

                            @if($selectedDocument['rejection_reason'])
                            <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                                <p class="text-xs text-red-600 font-medium uppercase tracking-wide mb-1">
                                    Previous Rejection Reason
                                </p>
                                <p class="text-sm text-gray-700">{{ $selectedDocument['rejection_reason'] }}</p>
                            </div>
                            @endif
                        </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Upload New Document <span class="text-red-500">*</span>
                            </label>
                            @if($newDocument)
                            <!-- File selected - show file info with remove button -->
                            <div class="mt-1 p-4 border-2 border-green-300 bg-green-50 rounded-xl" wire:loading.remove wire:target="newDocument">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-check-circle text-xl text-green-600"></i>
                                        <div>
                                            <p class="font-medium text-green-700 truncate">{{ $newDocument->getClientOriginalName() }}</p>
                                            <p class="text-xs text-gray-500">Click "Change" to select a different file</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <label for="newDocumentInput" class="cursor-pointer px-3 py-1.5 text-sm font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-colors">
                                            Change
                                        </label>
                                        <button type="button" wire:click="clearNewDocument" class="p-1.5 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors" title="Remove file">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <input
                                    id="newDocumentInput"
                                    type="file"
                                    wire:model="newDocument"
                                    class="sr-only"
                                    accept=".pdf,.jpg,.jpeg,.png">
                            </div>
                            @else
                            <!-- No file selected - show upload prompt -->
                            <div
                                class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-green-400 transition-colors cursor-pointer"
                                onclick="document.getElementById('newDocumentInput').click()"
                                wire:loading.remove wire:target="newDocument">
                                <div class="space-y-2 text-center">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400"></i>
                                    <div class="flex text-sm text-gray-600">
                                        <span class="font-medium text-green-700 hover:text-green-600">
                                            Click to upload
                                        </span>
                                        <span class="pl-1">or drag and drop</span>
                                    </div>
                                    <p class="text-xs text-gray-500">PDF, JPG, JPEG, PNG up to 10MB</p>
                                </div>
                                <input
                                    id="newDocumentInput"
                                    type="file"
                                    wire:model="newDocument"
                                    class="sr-only"
                                    accept=".pdf,.jpg,.jpeg,.png">
                            </div>
                            @endif
                            <!-- Loading Spinner -->
                            <div wire:loading wire:target="newDocument" class="mt-1">
                                <div class="flex items-center justify-center gap-3 p-6 bg-blue-50 border border-blue-200 rounded-xl">
                                    <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-blue-700">Uploading document...</span>
                                </div>
                            </div>
                            @error('newDocument')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                        <button
                            type="button"
                            wire:click="closeResubmitModal"
                            class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50 cursor-not-allowed"
                            class="px-4 py-2 bg-[#1a5c1f] text-white font-semibold rounded-xl hover:bg-green-800 transition-colors flex items-center gap-2">
                            <span wire:loading.remove wire:target="resubmitDocument">
                                <i class="fas fa-upload mr-1"></i> Resubmit Document
                            </span>
                            <span wire:loading wire:target="resubmitDocument">
                                <i class="fas fa-spinner fa-spin mr-1"></i> Uploading...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
