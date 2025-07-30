@props([
    'title' => 'John Doe',
    'subtitle' => null,
    'initials' => 'JD',
    'avatar' => null,
    'status' => 'Approved',
    'statusClass' => 'bg-green-100 text-green-800',
    'statusIcon' => null,
    'user_id' => 'APP-2023-001',
    'isActive' => true,
    'lastActive' => null,
    'tags' => [],
    'verified' => false
])

<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 space-y-6 md:space-y-0 p-5 bg-white rounded-xl shadow-sm border border-gray-100">
  <div class="flex items-center space-x-5">
    <!-- Enhanced Avatar with Support for Image -->
    <div class="relative">
      @if($avatar)
        <div class="h-20 w-20 rounded-full overflow-hidden ring-4 ring-green-50 shadow-sm">
          <img src="{{ $avatar }}" alt="{{ $title }}" class="h-full w-full object-cover">
        </div>
      @else
        <div class="h-20 w-20 rounded-full bg-gradient-to-br from-green-50 to-green-100 flex items-center justify-center text-green-700 text-2xl font-bold overflow-hidden ring-4 ring-green-50 shadow-sm">
          <span>{{ $initials }}</span>
        </div>
      @endif
      
      <!-- Enhanced Status Indicator -->
      <div class="absolute -bottom-1 -right-1 flex items-center justify-center">
        <span class="relative flex h-5 w-5">
          <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $isActive ? 'bg-green-400' : 'bg-gray-400' }} opacity-50"></span>
          <span class="relative inline-flex rounded-full h-5 w-5 {{ $isActive ? 'bg-green-500' : 'bg-gray-500' }} border-2 border-white"></span>
        </span>
      </div>
    </div>
    
    <!-- Enhanced Profile Information -->
    <div>
      <div class="flex items-center space-x-2">
        <h1 class="text-2xl font-bold text-gray-800">{{ $title }}</h1>
        @if($verified)
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
        @endif
      </div>
      
      @if($subtitle)
        <p class="text-gray-600 mt-0.5">{{ $subtitle }}</p>
      @endif
      
      <div class="flex flex-wrap items-center mt-2 gap-2">
        <!-- Status Badge -->
        <span class="inline-flex items-center {{ $statusClass }} text-xs px-2.5 py-1 rounded-full font-medium">
          @if($statusIcon)
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $statusIcon }}" />
            </svg>
          @endif
          {{ $status }}
        </span>
        
        <!-- ID Badge -->
        <span class="inline-flex items-center bg-gray-100 text-gray-600 text-xs px-2.5 py-1 rounded-full font-medium">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
          </svg>
          {{ $user_id }}
        </span>
        
        <!-- Last Active Indicator if Available -->
        @if($isActive && $lastActive)
        <span class="inline-flex items-center text-xs text-gray-500">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          Active {{ $lastActive }}
        </span>
        @endif
        
        <!-- Tags if Available -->
        @foreach($tags as $tag)
        <span class="inline-flex items-center bg-blue-50 text-blue-700 text-xs px-2.5 py-1 rounded-full font-medium">
          {{ $tag }}
        </span>
        @endforeach
      </div>
    </div>
  </div>
  
  <!-- Enhanced Action Buttons with Dropdown -->
  <div class="flex flex-wrap gap-2">
    <!-- <button id="edit-applicant-btn" class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 text-sm font-medium text-gray-700">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
      </svg>
      Edit
    </button> -->
    
    <button id="print-btn" class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 text-sm font-medium text-gray-700">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
      </svg>
      Print
    </button>
    
    <!-- Enhanced Dropdown Menu -->
    <div class="relative" x-data="{ open: false }">
      <button 
        @click="open = !open" 
        @keydown.escape.window="open = false"
        class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 text-sm font-medium text-gray-700"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
        </svg>
        More Actions
      </button>
      
      <!-- Dropdown Menu with Enhanced Styling -->
      <div 
        x-show="open" 
        @click.away="open = false" 
        x-transition:enter="transition ease-out duration-100" 
        x-transition:enter-start="transform opacity-0 scale-95" 
        x-transition:enter-end="transform opacity-100 scale-100" 
        x-transition:leave="transition ease-in duration-75" 
        x-transition:leave-start="transform opacity-100 scale-100" 
        x-transition:leave-end="transform opacity-0 scale-95" 
        class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg z-10 ring-1 ring-black ring-opacity-5 focus:outline-none"
        style="display: none;"
      >
      <div x-data="{
    async sendEmail() {
        const { value: result } = await Swal.fire({
            title: 'Send Email',
            input: 'textarea',
            inputLabel: 'Message',
            inputPlaceholder: 'Type your message here...',
            showCancelButton: true,
            confirmButtonText: 'Send',
            confirmButtonColor: '#43A047',
            preConfirm: (message) => {
                if (!message) {
                    Swal.showValidationMessage('Message is required')
                }
                return message
            }
        });
        
        if (result) {
            // Add your email sending logic here
            Swal.fire('Sent!', 'Email has been sent successfully.', 'success');
        }
    },

    confirmAction(action, title, icon, confirmColor) {
        Swal.fire({
            title: `Are you sure you want to ${action}?`,
            text: `This action will ${title}.`,
            icon: icon,
            showCancelButton: true,
            confirmButtonColor: confirmColor,
            cancelButtonColor: '#6b7280',
            confirmButtonText: `Yes, ${action}!`
        }).then((result) => {
            if (result.isConfirmed) {
                // Add your backend logic here
                Swal.fire(
                    `${action}d!`,
                    `Item has been ${action}d successfully.`,
                    'success'
                );
            }
        });
    },

    deleteItem() {
        this.confirmAction(
            'delete', 
            'permanently delete this item',
            'warning',
            '#dc2626'
        );
    }
}" class="py-1.5">
    <!-- Send Email Button -->
    <button @click.prevent="sendEmail" class="flex w-full items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 transition-colors duration-150">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
        </svg>
        Send Email
    </button>

    <!-- Approve Button -->
    <button @click.prevent="confirmAction(
        'approve', 
        'mark this item as approved',
        'question',
        '#10b981'
    )" class="flex w-full items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 transition-colors duration-150">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        Approve
    </button>

    <!-- Reject Button -->
    <button @click.prevent="confirmAction(
        'reject', 
        'mark this item as rejected',
        'error',
        '#ef4444'
    )" class="flex w-full items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 transition-colors duration-150">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        Reject
    </button>

    <div class="border-t border-gray-100 my-1.5"></div>

    <!-- Delete Button -->
    <button @click.prevent="deleteItem" class="flex w-full items-center px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors duration-150">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-3 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
        </svg>
        Delete
    </button>
</div>
      </div>
    </div>
    
    <!-- Primary Action Button -->
    <button class="flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 text-sm font-medium">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
      </svg>
      Contact
    </button>
  </div>
</div>