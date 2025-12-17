<x-admin.applicant.details.card-body :canEdit="false" :cardTitle="$cardTitle">
    <x-ui.toast/>

    @if($canCreate)
    <x-slot name="tableButton">
        <button id="add-rfid-btn" class="text-green-600 hover:text-green-700 text-sm font-medium">
            <i class="fas fa-plus mr-1"></i> Add Application
        </button>
    </x-slot>
    @endif

    <div class="rounded-lg border border-gray-200 shadow-sm m-5">
      <div class="">
        <table class="min-w-full divide-y divide-gray-200">
          <!-- Enhanced Header -->
          <thead class="bg-gray-50">
            <tr>
              @foreach($headers as $header)
              <th scope="col" class="group px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                <div class="flex items-center">
                  {{ $header }}
                  <svg class="w-4 h-4 ml-1 text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                  </svg>
                </div>
              </th>
              @endforeach

              <th scope="col" class="px-6 py-3.5 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                Actions
              </th>
            </tr>
          </thead>

          <!-- Enhanced Body -->
          <tbody class="bg-white divide-y divide-gray-200">
            @forelse($rows as $rowIndex => $row)
            <tr class="hover:bg-gray-50 transition-colors duration-150">
              @foreach($row as $key => $cell)
                @if($key !== 'actions')
                  <td class="px-6 py-4 whitespace-nowrap {{ $key === 'status' ? '' : ($loop->first ? 'font-medium text-gray-900' : 'text-gray-500') }}">
                    @if($key === 'status')
                     <x-ui.badge :label="$cell" />
                    @elseif($key === 'name' || $loop->first)
                      <div class="flex items-center">
                        @if(isset($cell['avatar']))
                          <div class="flex-shrink-0 h-10 w-10 mr-3">
                            <img class="h-10 w-10 rounded-full object-cover" src="{{ $cell['avatar'] }}" alt="">
                          </div>
                          <div>
                            <div class="text-sm font-medium text-gray-900">{{ $cell['name'] ?? $cell }}</div>
                            @if(isset($cell['email']))
                              <div class="text-sm text-gray-500">{{ $cell['email'] }}</div>
                            @endif
                          </div>
                        @else
                          <span class="text-sm font-medium text-gray-900">{{ $cell }}</span>
                        @endif
                      </div>
                    @elseif($key === 'date')
                      <div class="flex flex-col">
                        <span class="text-sm text-gray-900">{{ $cell }}</span>
                        @if(isset($row['time']))
                          <span class="text-xs text-gray-500">{{ $row['time'] }}</span>
                        @endif
                      </div>
                    @elseif($key === 'amount' || $key === 'price')
                      <span class="text-sm font-medium {{ isset($cell['change']) && $cell['change'] > 0 ? 'text-green-600' : (isset($cell['change']) && $cell['change'] < 0 ? 'text-red-600' : 'text-gray-900') }}">
                        {{ is_array($cell) ? $cell['value'] : $cell }}
                      </span>
                    @else
                      <span class="text-sm text-gray-500">{{ $cell }}</span>
                    @endif
                  </td>
                @endif
              @endforeach

              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <div class="flex items-center justify-end space-x-3">
                      <div class="relative" x-data="{ open: false }"
                           @close-dropdown.window="open = false"
                      >
                          <button
                              @click="open = !open"
                              @keydown.escape.window="open = false"
                              @click.away="open = false"
                              class="text-gray-500 hover:text-gray-700 transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 rounded-full p-1"
                          >
                              <span class="sr-only">More options</span>
                              <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                  <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                              </svg>
                          </button>

                          <div
                              x-show="open"
                              x-cloak
                              x-transition:enter="transition ease-out duration-100"
                              x-transition:enter-start="transform opacity-0 scale-95"
                              x-transition:enter-end="transform opacity-100 scale-100"
                              x-transition:leave="transition ease-in duration-75"
                              x-transition:leave-start="transform opacity-100 scale-100"
                              x-transition:leave-end="transform opacity-0 scale-95"
                              class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                          >
                              <div class="py-1" role="menu" aria-orientation="vertical">
                                  <!-- Approve - REMOVED @click="open = false" -->
                                  <button
                                      wire:click="approveApplication"
                                      wire:loading.attr="disabled"
                                      class="w-full text-left block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 disabled:opacity-50 disabled:cursor-not-allowed"
                                      role="menuitem"
                                  >
                                      <div class="flex items-center">
                                          <svg
                                              wire:loading
                                              wire:target="approveApplication"
                                              class="animate-spin h-4 w-4 mr-3 text-green-500"
                                              xmlns="http://www.w3.org/2000/svg"
                                              fill="none"
                                              viewBox="0 0 24 24"
                                          >
                                              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                          </svg>

                                          <svg
                                              wire:loading.remove
                                              wire:target="approveApplication"
                                              xmlns="http://www.w3.org/2000/svg"
                                              class="h-4 w-4 mr-3 text-green-500"
                                              fill="none"
                                              viewBox="0 0 24 24"
                                              stroke="currentColor"
                                          >
                                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                          </svg>

                                          <span wire:loading.remove wire:target="approveApplication">Approve</span>
                                          <span wire:loading wire:target="approveApplication">Approving...</span>
                                      </div>
                                  </button>

                                  <!-- Reject - REMOVED @click="open = false" -->
                                  <button
                                      wire:click="rejectApplication"
                                      wire:loading.attr="disabled"
                                      class="w-full text-left block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 disabled:opacity-50 disabled:cursor-not-allowed"
                                      role="menuitem"
                                  >
                                      <div class="flex items-center">
                                          <svg
                                              wire:loading
                                              wire:target="rejectApplication"
                                              class="animate-spin h-4 w-4 mr-3 text-red-500"
                                              xmlns="http://www.w3.org/2000/svg"
                                              fill="none"
                                              viewBox="0 0 24 24"
                                          >
                                              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                          </svg>

                                          <svg
                                              wire:loading.remove
                                              wire:target="rejectApplication"
                                              xmlns="http://www.w3.org/2000/svg"
                                              class="h-4 w-4 mr-3 text-red-500"
                                              fill="none"
                                              viewBox="0 0 24 24"
                                              stroke="currentColor"
                                          >
                                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                          </svg>

                                          <span wire:loading.remove wire:target="rejectApplication">Reject</span>
                                          <span wire:loading wire:target="rejectApplication">Rejecting...</span>
                                      </div>
                                  </button>

                                  <div class="border-t border-gray-100"></div>

                                  <!-- Delete - REMOVED @click="open = false" -->
                                  <button
                                      wire:click="deleteApplication"
                                      wire:confirm="Are you sure? This will permanently delete this application."
                                      wire:loading.attr="disabled"
                                      class="w-full text-left block px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 disabled:opacity-50 disabled:cursor-not-allowed"
                                      role="menuitem"
                                  >
                                      <div class="flex items-center">
                                          <svg
                                              wire:loading
                                              wire:target="deleteApplication"
                                              class="animate-spin h-4 w-4 mr-3 text-red-500"
                                              xmlns="http://www.w3.org/2000/svg"
                                              fill="none"
                                              viewBox="0 0 24 24"
                                          >
                                              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                          </svg>

                                          <svg
                                              wire:loading.remove
                                              wire:target="deleteApplication"
                                              xmlns="http://www.w3.org/2000/svg"
                                              class="h-4 w-4 mr-3 text-red-500"
                                              fill="none"
                                              viewBox="0 0 24 24"
                                              stroke="currentColor"
                                          >
                                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                          </svg>

                                          <span wire:loading.remove wire:target="deleteApplication">Delete</span>
                                          <span wire:loading wire:target="deleteApplication">Deleting...</span>
                                      </div>
                                  </button>
                              </div>
                          </div>
                      </div>
                  </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="{{ count($headers) + ($actions ? 1 : 0) }}" class="px-6 py-10 text-center">
                <div class="flex flex-col items-center justify-center">
                  <svg class="h-12 w-12 text-gray-300 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                  </svg>
                  <p class="text-gray-500 text-sm font-medium">No items found</p>
                  <p class="text-gray-400 text-xs mt-1">Try adjusting your search or filter to find what you're looking for.</p>
                </div>
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

</x-admin.applicant.details.card-body>
