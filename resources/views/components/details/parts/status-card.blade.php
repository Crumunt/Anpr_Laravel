@props([
    'status' => 'Pending',
    'statusClass' => 'bg-yellow-100 text-yellow-800',
    'statusIcon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', // clock icon path
    'applicationDate' => 'Jan 10, 2023',
    'approvalDate' => 'Jan 15, 2023',
    'approvedBy' => 'Admin User',
])
@php
$steps = [
  'pending' => ['label' => 'Submitted', 'complete' => false, 'current' => false],
  'under_review' => ['label' => 'Under Review', 'complete' => false, 'current' => false],
  'verified' => ['label' => 'Verified', 'complete' => false, 'current' => false],
  'approved' => ['label' => 'Approved', 'complete' => false, 'current' => false]
];

//breaks words with _ into two ex. under_review => under review charan
$normalize = fn($s) => str_replace('_', ' ', strtolower($s));

$keys = array_keys($steps);
$normalizedKeys = array_map($normalize, $keys);
$target = $normalize($status);

//find index from steps array which was normalized, still same index after processing
$index = array_search($target, $normalizedKeys, true);
if ($index === false) {
    $index = -1; //set to -1 if there are no matches
}

foreach($keys as $i => $step) {
  $steps[$step]['complete'] = $i < $index;
  $steps[$step]['current'] = $i === $index;
}

$max_progress = 100;
$base_progress = 20;
$progress_steps = 4;
$increment = ($max_progress - $base_progress) / $progress_steps;
$progress = $base_progress + $index * $increment;
@endphp

<div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden transition-all duration-300 hover:shadow-lg">
  <!-- Enhanced Header with Gradient Background -->
  <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-white to-gray-50">
    <h2 class="text-xl font-bold text-gray-800">Application Status</h2>
  </div>

  <div class="p-6">
    <!-- Status Card with Enhanced Design -->
    <div class="mb-6 rounded-lg bg-gray-50 p-4 border border-gray-100">
      <div class="flex items-center justify-between mb-2">
        <span class="text-sm font-medium text-gray-600">Current Status</span>
        <div class="flex items-center">
          <span class="inline-flex items-center {{ $statusClass }} text-xs px-3 py-1.5 rounded-full font-medium">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $statusIcon }}" />
            </svg>
            {{ $status }}
          </span>
        </div>
      </div>

      <!-- Enhanced Progress Bar -->
      <div class="relative mt-4">
        <div class="overflow-hidden h-2.5 rounded-full bg-gray-200">
          <div style="width: {{ $progress }}%" 
               class="h-2.5 rounded-full {{ $progress == 100 ? 'bg-green-500' : ($progress >= 70 ? 'bg-blue-500' : ($progress >= 40 ? 'bg-yellow-500' : 'bg-orange-500')) }} 
                      transition-all duration-500 ease-in-out"></div>
        </div>
        <div class="mt-1 text-right">
          <span class="text-xs font-medium {{ $progress == 100 ? 'text-green-600' : ($progress >= 70 ? 'text-blue-600' : ($progress >= 40 ? 'text-yellow-600' : 'text-orange-600')) }}">
            {{ $progress }}% Complete
          </span>
        </div>
      </div>
    </div>

    <!-- Enhanced Timeline with Icons -->
    <div class="mb-8 flow-root">
      <ul role="list" class="-mb-8">
        @foreach($steps as $index => $step)
        <li>
          <div class="relative pb-8">
            @if($loop->index < count($steps) - 1)
              <span class="absolute top-5 left-5 -ml-px h-full w-0.5 {{ $step['complete'] ? 'bg-green-500' : 'bg-gray-200' }}" aria-hidden="true"></span>
            @endif
            <div class="relative flex items-start space-x-3">
              <!-- Step Icon -->
              <div>
                <div class="relative px-1">
                  <div class="h-9 w-9 rounded-full {{ $step['complete'] ? 'bg-green-500' : ($step['current'] ? 'bg-blue-500' : 'bg-gray-200') }} flex items-center justify-center">
                    @if($step['complete'])
                      <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                      </svg>
                    @elseif($step['current'])
                      <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                      </svg>
                    @else
                      <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                    @endif
                  </div>
                </div>
              </div>
              
              <!-- Step Content -->
              <div class="min-w-0 flex-1 py-1.5">
                <div class="text-sm font-medium {{ $step['complete'] ? 'text-green-600' : ($step['current'] ? 'text-blue-600' : 'text-gray-500') }}">{{ $step['label'] }}</div>
                
                @if($step['label'] == 'Submitted' && $applicationDate)
                  <div class="mt-1 text-xs text-gray-500">
                    <span class="font-medium">Date:</span> {{ $applicationDate }}
                  </div>
                @elseif($step['label'] == 'Approved' && $approvalDate && $step['complete'])
                  <div class="mt-1 text-xs text-gray-500">
                    <span class="font-medium">Date:</span> {{ $approvalDate }}
                    <span class="inline-block mx-1">•</span>
                    <span class="font-medium">By:</span> {{ $approvedBy }}
                  </div>
                @endif
              </div>
              
              <!-- Step Status -->
              <div class="self-center">
                @if($step['current'])
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    Current
                  </span>
                @endif
              </div>
            </div>
          </div>
        </li>
        @endforeach
      </ul>
    </div>
    
    <!-- Application Details Section -->
    <div class="border-t border-gray-200 pt-4">
      <h3 class="text-base font-medium text-gray-800 mb-4">Application Details</h3>
      <dl class="sm:divide-y sm:divide-gray-200">
        <div class="py-2.5 sm:grid sm:grid-cols-3 sm:gap-4">
          <dt class="text-sm font-medium text-gray-500">Application Date</dt>
          <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $applicationDate }}</dd>
        </div>
        <div class="py-2.5 sm:grid sm:grid-cols-3 sm:gap-4">
          <dt class="text-sm font-medium text-gray-500">Approval Date</dt>
          <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $approvalDate }}</dd>
        </div>
        <div class="py-2.5 sm:grid sm:grid-cols-3 sm:gap-4">
          <dt class="text-sm font-medium text-gray-500">Approved By</dt>
          <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
            <div class="flex items-center">
              <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 font-medium text-xs">
                {{ substr($approvedBy, 0, 2) }}
              </div>
              <div class="ml-3">
                {{ $approvedBy }}
              </div>
            </div>
          </dd>
        </div>
      </dl>
    </div>
  </div>
</div>