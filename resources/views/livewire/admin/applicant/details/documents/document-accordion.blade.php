<div class="border border-gray-200 rounded-lg overflow-hidden">
    <!-- Accordion Header -->
    <button
        @click=" openApplication=openApplication==={{ $index }} ? null : {{ $index }}"
        class="w-full flex items-center justify-between px-4 py-3 bg-gray-50 hover:bg-gray-100 transition-colors duration-150">
        <div class="flex items-center space-x-3 min-w-0 flex-1">
            <!-- Chevron Icon -->
            <svg
                class="h-5 w-5 text-gray-500 transition-transform duration-200"
                :class="{ 'rotate-90': openApplication === {{ $index }} }"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>

            <!-- Application Info -->
            <div class="flex flex-col items-start min-w-0 flex-1">
                <div class="flex items-center space-x-2">
                    <span class="text-sm font-semibold text-gray-900">{{ $application['application_number'] }}</span>
                    <span class="text-xs text-gray-500">({{ $application['applicant_type'] }})</span>
                </div>
                <div class="flex items-center space-x-2 mt-1">
                    <!-- Status Badge -->
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                      @if($application['status'] === 'approved') bg-green-100 text-green-800
                      @elseif($application['status'] === 'under_review') bg-yellow-100 text-yellow-800
                      @elseif($application['status'] === 'rejected') bg-red-100 text-red-800
                      @else bg-gray-100 text-gray-800
                      @endif">
                        {{ Str::headline($application['status']) }}
                    </span>
                    <span class="text-xs text-gray-500">{{ $application['date'] }}</span>
                </div>
            </div>

            <!-- Document Count -->
            <span class="text-xs text-gray-500 whitespace-nowrap">
                {{ count($application['documents']) }} {{ Str::plural('doc', count($application['documents'])) }}
            </span>
        </div>
    </button>

    <!-- Accordion Content -->
    <div
        x-show="openApplication === {{ $index }}"
        x-collapse
        class="bg-white">
        <div class="px-4 py-3 space-y-2">
            @foreach($application['documents'] as $doc)
            <div class="group flex items-center justify-between p-2 rounded-lg hover:bg-gray-50 transition-colors duration-150">
                <div class="flex items-center space-x-3 min-w-0 flex-1">
                    <!-- Document Icon -->
                    @php
                    $documentColor = match($doc['status']) {
                    "under_review" => "bg-yellow-100",
                    "approved" => "bg-green-100",
                    "rejected" => "bg-red-100",
                    };

                    $documentIconColor = match($doc['status']){
                    "under_review" => "text-yellow-500",
                    "approved" => "text-green-500",
                    "rejected" => "text-red-500",
                    }
                    @endphp

                    <div class="flex-shrink-0 h-10 w-10 {{ $documentColor ?? 'bg-gray-100' }} rounded-lg flex items-center justify-center">
                        <svg class="{{ $documentIconColor ?? 'text-gray-500' }} h-5 w-5" fill="currentColor" viewBox="0 0 384 512">
                            <path d="M320 464c8.8 0 16-7.2 16-16V160H256c-17.7 0-32-14.3-32-32V48H64c-8.8 0-16 7.2-16 16V448c0 8.8 7.2 16 16 16H320zM0 64C0 28.7 28.7 0 64 0H229.5c17 0 33.3 6.7 45.3 18.7l90.5 90.5c12 12 18.7 28.3 18.7 45.3V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V64z" />
                        </svg>
                    </div>

                    <!-- Document Info -->
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $doc['label'] }} <span><small>({{ Str::headline($doc['status']) }})</small></span></p>
                        <p class="text-xs text-gray-500">{{ $doc['uploaded'] ?? 'No date' }}</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center space-x-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                    <button
                        @click="$dispatch('open-document-viewer', {
                                    document: {
                                        document_id: '{{ $doc['document_id'] }}',
                                        name: '{{ $doc['label'] }}',
                                        uploaded: '{{ $doc['uploaded'] ?? 'No date' }}',
                                        url: '{{ $doc['url'] ?? '#' }}',
                                        mime_type: '{{ $doc['mime_type'] ?? null }}',
                                        application: '{{ $application['application_number'] }}',
                                        applicantType: '{{ $application['applicant_type'] }}',
                                        status: '{{ $doc['status'] ?? 'pending' }}'
                                    }
                                    })"
                        class="p-1.5 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-colors duration-200"
                        title="View Document">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
