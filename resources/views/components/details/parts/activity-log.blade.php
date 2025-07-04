@props([
    'activities' => []
])

<div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden transition-all duration-300 hover:shadow-lg">
  <!-- Enhanced Header -->
  <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-white to-gray-50 flex justify-between items-center">
    <div class="flex items-center space-x-2">
      <h2 class="text-xl font-bold text-gray-800">Activity Log</h2>
      <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ count($activities) }} new</span>
    </div>
    <div class="text-gray-400 hover:text-gray-600 cursor-pointer transition-colors">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
      </svg>
    </div>
  </div>

  <!-- Enhanced Activity List with Timeline -->
  <div class="p-6">
    <ul class="space-y-6 relative">
      <div class="absolute left-4 top-3 bottom-3 w-0.5 bg-gray-200 z-0"></div>
      
      @foreach($activities as $index => $activity)
      <li class="flex items-start relative z-10">
        <!-- Enhanced Activity Icon -->
        <div class="flex-shrink-0 h-9 w-9 rounded-full {{ $activity['bgColor'] }} flex items-center justify-center shadow-sm ring-4 ring-white">
          <svg xmlns="http://www.w3.org/2000/svg" class="{{ $activity['iconColor'] }} h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            @if(strpos($activity['icon'], 'fa-user') !== false)
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            @elseif(strpos($activity['icon'], 'fa-key') !== false)
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
            @elseif(strpos($activity['icon'], 'fa-door-open') !== false)
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
            @elseif(strpos($activity['icon'], 'fa-cog') !== false)
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            @elseif(strpos($activity['icon'], 'fa-exclamation') !== false)
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            @else
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            @endif
          </svg>
        </div>

        <!-- Enhanced Activity Content -->
        <div class="ml-4 flex-1">
          <div class="bg-gray-50 rounded-lg p-3 border border-gray-100 shadow-sm hover:shadow transition-shadow duration-200">
            <p class="text-sm font-medium text-gray-800">{!! $activity['description'] !!}</p>
            <div class="mt-1 flex items-center space-x-2">
              <p class="text-xs text-gray-500">{{ $activity['timestamp'] }}</p>
              @if(isset($activity['priority']) && $activity['priority'] === 'high')
                <span class="inline-flex items-center rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700">
                  High Priority
                </span>
              @endif
            </div>
          </div>
          
          <!-- Conditionally show action buttons for certain activities -->
          @if(isset($activity['type']) && in_array($activity['type'], ['alert', 'request']))
          <div class="mt-2 flex space-x-2">
            <button class="text-xs bg-green-50 hover:bg-green-100 text-green-700 font-medium py-1 px-3 rounded-md border border-green-200 transition-colors duration-200 flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
              </svg>
              Approve
            </button>
            <button class="text-xs bg-red-50 hover:bg-red-100 text-red-700 font-medium py-1 px-3 rounded-md border border-red-200 transition-colors duration-200 flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
              Deny
            </button>
          </div>
          @endif
        </div>
      </li>
      @endforeach
    </ul>
    
    <!-- Enhanced Call to Action -->
    <div class="mt-6 flex justify-center">
      <button class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
        </svg>
        View All Activity
      </button>
    </div>
  </div>
</div>