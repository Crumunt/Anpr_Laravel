@props([
    'headers' => [],
    'rows' => [],
    'actions' => true
])
<div class="overflow-hidden rounded-lg border border-gray-200 shadow-sm">
  <div class="overflow-x-auto">
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
          
          @if($actions)
          <th scope="col" class="px-6 py-3.5 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
            Actions
          </th>
          @endif
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
                  <span class="{{ $cell['class'] ?? 'bg-gray-100 text-gray-800' }} inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                    @if(isset($cell['icon']))
                      <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 8 8">
                        <circle cx="4" cy="4" r="3" />
                      </svg>
                    @endif
                    {{ $cell['label'] ?? $cell }}
                  </span>
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
          
          @if($actions)
          <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
            <div class="flex items-center justify-end space-x-3">
              <button class="text-green-600 hover:text-green-900 transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 rounded-full p-1">
                <span class="sr-only">Edit</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
              </button>
              <button class="text-red-600 hover:text-red-900 transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 rounded-full p-1">
                <span class="sr-only">Delete</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
              </button>
              <!-- Fixed dropdown menu with proper overlay -->
              <div class="relative" x-data="{ open: false }">
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
                  x-transition:enter="transition ease-out duration-100" 
                  x-transition:enter-start="transform opacity-0 scale-95" 
                  x-transition:enter-end="transform opacity-100 scale-100" 
                  x-transition:leave="transition ease-in duration-75" 
                  x-transition:leave-start="transform opacity-100 scale-100" 
                  x-transition:leave-end="transform opacity-0 scale-95" 
                  class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                  style="position: absolute; top: 100%; right: 0;"
                >
                  <div class="py-1" role="menu" aria-orientation="vertical">
                    <a href="#" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                      <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        View details
                      </div>
                    </a>
                    <a href="#" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                      <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        Duplicate
                      </div>
                    </a>
                    <a href="#" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                      <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download
                      </div>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </td>
          @endif
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