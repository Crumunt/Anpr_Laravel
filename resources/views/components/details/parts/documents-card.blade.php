@props([
    'documents' => []
])
<div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden transition-all duration-300 hover:shadow-lg">
  <!-- Enhanced Header with Upload Button -->
  <div class="flex items-center justify-between px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-white to-gray-50">
    <div class="flex items-center">
      <h2 class="text-xl font-bold text-gray-800">Documents</h2>
      <span class="ml-2 bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ count($documents) }}</span>
    </div>
    <div>
      <button 
        data-open-modal="document-upload-modal" 
        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
        </svg>
        Upload New
      </button>
    </div>
  </div>
  <!-- Main Content -->
  <div class="p-6">
    @if(count($documents) > 0)
      <div class="space-y-1">
        @foreach($documents as $doc)
          <!-- Document Item -->
          <div class="group relative p-3 rounded-lg hover:bg-gray-50 transition-colors duration-150">
        <div class="flex justify-between items-center">
          <div class="flex items-center flex-1 min-w-0">
            <!-- Enhanced Document Icon -->
            <div class="flex-shrink-0 h-12 w-12 {{ $doc['bgColor'] }} rounded-lg flex items-center justify-center shadow-sm">
              @if(strpos($doc['icon'], 'fa-file-pdf') !== false)
                <svg class="{{ $doc['iconColor'] }} h-6 w-6" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                  <path d="M320 464c8.8 0 16-7.2 16-16V160H256c-17.7 0-32-14.3-32-32V48H64c-8.8 0-16 7.2-16 16V448c0 8.8 7.2 16 16 16H320zM0 64C0 28.7 28.7 0 64 0H229.5c17 0 33.3 6.7 45.3 18.7l90.5 90.5c12 12 18.7 28.3 18.7 45.3V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V64zm144 168v48c0 13.3-10.7 24-24 24H88c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8h32c13.3 0 24 10.7 24 24v48c0 8.8-7.2 16-16 16s-16-7.2-16-16V376c0-4.4-3.6-8-8-8H72c-8.8 0-16-7.2-16-16s7.2-16 16-16h32c4.4 0 8-3.6 8-8v-16c0-4.4-3.6-8-8-8H72c-8.8 0-16-7.2-16-16s7.2-16 16-16h32c4.4 0 8-3.6 8-8V232c0-8.8 7.2-16 16-16s16 7.2 16 16z"/>
                </svg>
              @elseif(strpos($doc['icon'], 'fa-file-word') !== false)
                <svg class="{{ $doc['iconColor'] }} h-6 w-6" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                  <path d="M320 464c8.8 0 16-7.2 16-16V160H256c-17.7 0-32-14.3-32-32V48H64c-8.8 0-16 7.2-16 16V448c0 8.8 7.2 16 16 16H320zM0 64C0 28.7 28.7 0 64 0H229.5c17 0 33.3 6.7 45.3 18.7l90.5 90.5c12 12 18.7 28.3 18.7 45.3V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V64zm130.2 184.9c-7.6-4.4-17.4-1.8-21.8 5.8l-32 55.4C66.4 327.3 64 347.4 64 368v8c0 13.3 10.7 24 24 24s24-10.7 24-24v-8c0-12.8 1.5-25.5 4.4-37.8l8.3-14.3 7.8 30.3c3.2 12.5 14.3 21.3 27.2 21.3s24-8.8 27.2-21.3l16-62c1.9-7.3-2.2-14.9-9.5-16.8s-14.9 2.2-16.8 9.5l-9.6 37.5L149.9 250C138.5 208 114 171.7 81.5 144.2c-7.4-6.3-18.5-5.4-24.7 2s-5.4 18.5 2 24.7C88.2 196 109.9 227 121.8 262l8.4 27.9z"/>
                </svg>
              @elseif(strpos($doc['icon'], 'fa-file-excel') !== false)
                <svg class="{{ $doc['iconColor'] }} h-6 w-6" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                  <path d="M320 464c8.8 0 16-7.2 16-16V160H256c-17.7 0-32-14.3-32-32V48H64c-8.8 0-16 7.2-16 16V448c0 8.8 7.2 16 16 16H320zM0 64C0 28.7 28.7 0 64 0H229.5c17 0 33.3 6.7 45.3 18.7l90.5 90.5c12 12 18.7 28.3 18.7 45.3V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V64zm97.9 200.1c-9.9-9.9-26-9.9-35.9 0s-9.9 26 0 35.9l87 87c9.9 9.9 26 9.9 35.9 0l87-87c9.9-9.9 9.9-26 0-35.9s-26-9.9-35.9 0L160 329.1l-62.1-65z"/>
                </svg>
              @elseif(strpos($doc['icon'], 'fa-file-image') !== false)
                <svg class="{{ $doc['iconColor'] }} h-6 w-6" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                  <path d="M320 464c8.8 0 16-7.2 16-16V160H256c-17.7 0-32-14.3-32-32V48H64c-8.8 0-16 7.2-16 16V448c0 8.8 7.2 16 16 16H320zM0 64C0 28.7 28.7 0 64 0H229.5c17 0 33.3 6.7 45.3 18.7l90.5 90.5c12 12 18.7 28.3 18.7 45.3V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V64zm128 264c0 13.3-10.7 24-24 24s-24-10.7-24-24s10.7-24 24-24s24 10.7 24 24zm96-120c13.3 0 24 10.7 24 24V328c0 13.3-10.7 24-24 24H104c-13.3 0-24-10.7-24-24V168c0-13.3 10.7-24 24-24H224z"/>
                </svg>
              @else
                <svg class="{{ $doc['iconColor'] }} h-6 w-6" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                  <path d="M320 464c8.8 0 16-7.2 16-16V160H256c-17.7 0-32-14.3-32-32V48H64c-8.8 0-16 7.2-16 16V448c0 8.8 7.2 16 16 16H320zM0 64C0 28.7 28.7 0 64 0H229.5c17 0 33.3 6.7 45.3 18.7l90.5 90.5c12 12 18.7 28.3 18.7 45.3V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V64z"/>
                </svg>
              @endif
            </div>
            <!-- Document Details -->
            <div class="ml-4 flex-1 min-w-0">
              <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-900 truncate">{{ $doc['name'] }}</p>
                @if(isset($doc['fileSize']))
                <span class="text-xs text-gray-500 ml-2">{{ $doc['fileSize'] }}</span>
                @endif
              </div>
              <div class="mt-1 flex items-center">
                <p class="text-xs text-gray-500">{{ $doc['uploadDate'] }}</p>
                @if(isset($doc['uploadedBy']))
                <span class="mx-1.5 text-gray-300">•</span>
                <p class="text-xs text-gray-500">Uploaded by {{ $doc['uploadedBy'] }}</p>
                @endif
                @if(isset($doc['tags']) && count($doc['tags']) > 0)
                <div class="ml-2 flex flex-wrap gap-1">
                  @foreach($doc['tags'] as $tag)
                  <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                    {{ $tag }}
                  </span>
                  @endforeach
                </div>
                @endif
              </div>
            </div>
          </div>
          <!-- Enhanced Action Buttons -->
          <div class="ml-4 flex items-center space-x-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
            <button class="p-1.5 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-md transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-500" title="Preview">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
            </button>
            <button class="p-1.5 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-md transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-500" title="Download">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
              </svg>
            </button>
            <button class="p-1.5 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-md transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-red-500" title="Delete">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
              </svg>
            </button>
          </div>
        </div>
      </div>
      @endforeach
    </div>
    @else
    <!-- Empty State -->
    <div class="flex flex-col items-center justify-center py-12">
      <svg class="h-20 w-20 text-gray-200 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
      </svg>
      <h3 class="text-lg font-medium text-gray-900">No documents yet</h3>
      <p class="text-sm text-gray-500 mt-1 text-center max-w-sm">Upload your first document by clicking the upload button above.</p>
      <button
          data-open-modal="document-upload-modal"
          class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
          </svg>
          Upload your first document
        </button>
      </div>
    @endif
  </div>
</div>

<!-- Document Upload Modal -->

<x-modal.modal id="document-upload-modal" type="document_upload" maxWidth="lg" />