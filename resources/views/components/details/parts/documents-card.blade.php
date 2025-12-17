@props([
'applications' => []
])

<div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden transition-all duration-300 hover:shadow-lg">
    <!-- Header -->
    <div class="flex items-center justify-between px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-white to-gray-50">
        <div class="flex items-center">
            <h2 class="text-xl font-bold text-gray-800">Documents</h2>
            <span class="ml-2 bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                {{ count($applications) }} {{ Str::plural('Application', count($applications)) }}
            </span>
        </div>
    </div>

    <!-- Main Content -->
    <div class="p-6">
        @if(count($applications) > 0)
        <!-- First 5 Applications Accordion -->
        <div class="space-y-3" x-data="{ openApplication: null }">
            @foreach(array_slice($applications, 0, 5) as $index => $application)

            <livewire:admin.applicant.details.documents.document-accordion :index="$index" :application="$application" :application_id="$application['application_id']" />
            @endforeach
        </div>

        <!-- View All Button -->
        @if(count($applications) > 0)
        <div x-data="{ showModal: false }">
            <button
                @click="showModal = true; $dispatch('open-all-documents')"
                class="mt-4 w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                View All Documents
                <svg class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
        @endif

        @else
        <!-- Empty State -->
        <div class="flex flex-col items-center justify-center py-12">
            <svg class="h-20 w-20 text-gray-200 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="text-lg font-medium text-gray-900">No documents yet</h3>
            <p class="text-sm text-gray-500 mt-1 text-center max-w-sm">Documents will appear here once applications are submitted.</p>
        </div>
        @endif
    </div>
</div>

<!-- All Documents Modal -->
<div
    x-data="{ show: false, openApplication: null }"
    @open-all-documents.window="show = true"
    @keydown.escape.window="if (show) show = false"
    x-show="show"
    x-cloak
    class="fixed inset-0 z-50 overflow-y-auto"
    style="display: none;">
    <!-- Backdrop -->
    <div
        x-show="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
        @click="show = false"></div>

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
            class="relative bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[80vh] overflow-hidden"
            @click.stop>
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-900">All Documents</h3>
                <button
                    @click="show = false"
                    class="text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-lg p-1">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="px-6 py-4 overflow-y-auto max-h-[calc(80vh-120px)]">
                <div class="space-y-3">
                    @foreach($applications as $index => $application)
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <!-- Accordion Header -->
                        <button
                            @click="openApplication = openApplication === {{ $index }} ? null : {{ $index }}"
                            class="w-full flex items-center justify-between px-4 py-3 bg-gray-50 hover:bg-gray-100 transition-colors duration-150">
                            <div class="flex items-center space-x-3 min-w-0 flex-1">
                                <svg
                                    class="h-5 w-5 text-gray-500 transition-transform duration-200"
                                    :class="{ 'rotate-90': openApplication === {{ $index }} }"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>

                                <div class="flex flex-col items-start min-w-0 flex-1">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm font-semibold text-gray-900">{{ $application['application_number'] }}</span>
                                        <span class="text-xs text-gray-500">({{ $application['applicant_type'] }})</span>
                                    </div>
                                    <div class="flex items-center space-x-2 mt-1">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                        @if($application['status'] === 'Approved') bg-green-100 text-green-800
                        @elseif($application['status'] === 'Pending') bg-yellow-100 text-yellow-800
                        @elseif($application['status'] === 'Rejected') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                                            {{ $application['status'] }}
                                        </span>
                                        <span class="text-xs text-gray-500">{{ $application['date'] }}</span>
                                    </div>
                                </div>

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
                                        <div class="flex-shrink-0 h-10 w-10 {{ $doc['bgColor'] ?? 'bg-gray-100' }} rounded-lg flex items-center justify-center">
                                            <svg class="{{ $doc['iconColor'] ?? 'text-gray-500' }} h-5 w-5" fill="currentColor" viewBox="0 0 384 512">
                                                <path d="M320 464c8.8 0 16-7.2 16-16V160H256c-17.7 0-32-14.3-32-32V48H64c-8.8 0-16 7.2-16 16V448c0 8.8 7.2 16 16 16H320zM0 64C0 28.7 28.7 0 64 0H229.5c17 0 33.3 6.7 45.3 18.7l90.5 90.5c12 12 18.7 28.3 18.7 45.3V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V64z" />
                                            </svg>
                                        </div>

                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $doc['label'] }}</p>
                                            <p class="text-xs text-gray-500">{{ $doc['uploadDate'] ?? 'No date' }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center space-x-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                        <button
                                            @click="$dispatch('open-document-viewer', {
                            document: {
                              name: '{{ $doc['label'] }}',
                              uploadDate: '{{ $doc['uploadDate'] ?? 'No date' }}',
                              url: '{{ $doc['url'] ?? '#' }}',
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
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] {
        display: none !important;
    }
</style>

<!-- Document Viewer Modal -->
<livewire:admin.applicant.details.documents.document-viewer />
