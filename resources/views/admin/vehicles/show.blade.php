<x-details.layout title="Vehicle Details" type="vehicle" :id="$id">
  @php
  $breadcrumbItems = [
      ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
      ['label' => 'Vehicles', 'url' => route('admin.vehicles')],
      ['label' => 'Toyota Fortuner (ABC-1234)']
  ];
  
  $vehicleInfo = [
      ['label' => 'Make & Model', 'value' => 'Toyota Fortuner'],
      ['label' => 'Year', 'value' => '2020'],
      ['label' => 'Color', 'value' => 'White'],
      ['label' => 'License Plate', 'value' => 'ABC-1234'],
      ['label' => 'VIN', 'value' => 'JT3HP10V5W7123456'],
      ['label' => 'Engine Number', 'value' => 'EN12345678']
  ];
  
  $registrationInfo = [
      ['label' => 'Registration Date', 'value' => 'January 15, 2023'],
      ['label' => 'Expiration Date', 'value' => 'January 15, 2024'],
      ['label' => 'Status', 'value' => 'Active'],
      ['label' => 'Registered By', 'value' => 'Admin User'],
      ['label' => 'Last Updated', 'value' => 'January 15, 2023']
  ];
  
  $ownerInfo = [
      ['label' => 'Owner Name', 'value' => 'John Doe'],
      ['label' => 'Contact Number', 'value' => '(555) 123-4567'],
      ['label' => 'Email', 'value' => 'john.doe@example.com'],
      ['label' => 'Relationship', 'value' => 'Primary Owner']
  ];
  
  $rfidHeaders = ['Tag Number', 'Status', 'Issue Date', 'Expiry Date'];
  $rfidRows = [
      [
          'tag' => 'RFID-001-2023', 
          'status' => ['label' => 'Active', 'class' => 'bg-green-100 text-green-800'], 
          'issueDate' => 'Feb 5, 2023', 
          'expiryDate' => 'Feb 5, 2024'
      ]
  ];
  
  $accessRecords = [
      [
          'datetime' => 'May 20, 2023 - 08:15 AM',
          'location' => 'Main Gate',
          'direction' => 'Entry',
          'rfid_tag' => 'RFID-001-2023',
          'rfid_id' => '1',
          'status' => 'Granted'
      ],
      [
          'datetime' => 'May 20, 2023 - 05:30 PM',
          'location' => 'Main Gate',
          'direction' => 'Exit',
          'rfid_tag' => 'RFID-001-2023',
          'rfid_id' => '1',
          'status' => 'Granted'
      ],
      [
          'datetime' => 'May 21, 2023 - 08:22 AM',
          'location' => 'Main Gate',
          'direction' => 'Entry',
          'rfid_tag' => 'RFID-001-2023',
          'rfid_id' => '1',
          'status' => 'Granted'
      ],
      [
          'datetime' => 'May 21, 2023 - 04:45 PM',
          'location' => 'Main Gate',
          'direction' => 'Exit',
          'rfid_tag' => 'RFID-001-2023',
          'rfid_id' => '1',
          'status' => 'Granted'
      ],
      [
          'datetime' => 'May 22, 2023 - 08:05 AM',
          'location' => 'East Gate',
          'direction' => 'Entry',
          'rfid_tag' => 'RFID-001-2023',
          'rfid_id' => '1',
          'status' => 'Denied'
      ]
  ];
  
  $activities = [
      [
          'description' => 'Access denied at <span class="font-medium">East Gate</span>',
          'timestamp' => 'May 22, 2023 at 08:05 AM',
          'bgColor' => 'bg-red-100',
          'icon' => 'fas fa-times',
          'iconColor' => 'text-red-500'
      ],
      [
          'description' => 'Access granted at <span class="font-medium">Main Gate</span>',
          'timestamp' => 'May 21, 2023 at 04:45 PM',
          'bgColor' => 'bg-green-100',
          'icon' => 'fas fa-check',
          'iconColor' => 'text-green-500'
      ],
      [
          'description' => 'RFID tag <span class="font-medium">RFID-001-2023</span> assigned',
          'timestamp' => 'February 5, 2023 at 10:15 AM',
          'bgColor' => 'bg-blue-100',
          'icon' => 'fas fa-tag',
          'iconColor' => 'text-blue-500'
      ],
      [
          'description' => 'Vehicle registration approved',
          'timestamp' => 'January 15, 2023 at 11:30 AM',
          'bgColor' => 'bg-green-100',
          'icon' => 'fas fa-check-circle',
          'iconColor' => 'text-green-500'
      ],
      [
          'description' => 'Vehicle registered by <span class="font-medium">Admin User</span>',
          'timestamp' => 'January 15, 2023 at 10:45 AM',
          'bgColor' => 'bg-purple-100',
          'icon' => 'fas fa-car',
          'iconColor' => 'text-purple-500'
      ]
  ];
  
  $documents = [
      [
          'name' => 'Vehicle Registration.pdf',
          'uploadDate' => 'Uploaded on Jan 15, 2023',
          'bgColor' => 'bg-blue-100',
          'icon' => 'fas fa-file-pdf',
          'iconColor' => 'text-blue-500'
      ],
      [
          'name' => 'Insurance Certificate.jpg',
          'uploadDate' => 'Uploaded on Jan 15, 2023',
          'bgColor' => 'bg-green-100',
          'icon' => 'fas fa-file-image',
          'iconColor' => 'text-green-500'
      ],
      [
          'name' => 'Vehicle Photo.jpg',
          'uploadDate' => 'Uploaded on Jan 15, 2023',
          'bgColor' => 'bg-yellow-100',
          'icon' => 'fas fa-file-image',
          'iconColor' => 'text-yellow-500'
      ]
  ];
  @endphp
  
  <x-slot name="breadcrumb">
    <x-details.parts.breadcrumb :items="$breadcrumbItems" />
  </x-slot>
  
  <x-slot name="header">
    <x-details.parts.profile-header 
      title="Toyota Fortuner (ABC-1234)" 
      initials="TF" 
      status="Active" 
      statusClass="bg-green-100 text-green-800" 
      id="VEH-2023-001" 
      :isActive="true" 
    />
  </x-slot>
  
  <x-slot name="mainContent">
    <!-- Vehicle Information Card -->
    <x-details.parts.info-card title="Vehicle Information" editId="edit-vehicle-info-btn">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($vehicleInfo as $info)
          <x-details.parts.info-field :label="$info['label']" :value="$info['value']" />
        @endforeach
      </div>
    </x-details.parts.info-card>
    
    <!-- Registration Information Card -->
    <x-details.parts.info-card title="Registration Information" editId="edit-registration-info-btn">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($registrationInfo as $info)
          <x-details.parts.info-field :label="$info['label']" :value="$info['value']" />
        @endforeach
      </div>
    </x-details.parts.info-card>
    
    <!-- Owner Information Card -->
    <x-details.parts.info-card title="Owner Information" :editButton="false">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($ownerInfo as $info)
          <x-details.parts.info-field :label="$info['label']" :value="$info['value']" />
        @endforeach
      </div>
      <div class="mt-4 flex justify-end">
        <a href="{{ route('admin.applicant.show-details', ['id' => 1]) }}" class="text-green-600 hover:text-green-700 text-sm font-medium">
          <i class="fas fa-external-link-alt mr-1"></i> View Owner Details
        </a>
      </div>
    </x-details.parts.info-card>
    
    <!-- RFID Tags Card -->
    <x-details.parts.info-card title="RFID Tags" editButton="false">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-sm font-medium text-gray-700">Assigned Tags</h3>
        <button id="add-rfid-btn" class="text-green-600 hover:text-green-700 text-sm font-medium">
          <i class="fas fa-plus mr-1"></i> Add Tag
        </button>
      </div>
      <x-details.parts.data-table :headers="$rfidHeaders" :rows="$rfidRows" />
    </x-details.parts.info-card>
    
    <!-- Access History Card -->
    <x-details.parts.access-history 
      title="Access History" 
      :accessRecords="$accessRecords" 
      :currentPage="2" 
      :totalPages="10" 
    />
  </x-slot>
  
  <x-slot name="sideContent">
    <!-- Status Card -->
    <x-details.parts.status-card 
      status="Active" 
      statusClass="bg-green-100 text-green-800" 
      applicationDate="January 15, 2023" 
      approvalDate="January 15, 2023" 
      approvedBy="Admin User" 
      :progress="100" 
    />
    
    <!-- Documents Card -->
    <x-details.parts.documents-card :documents="$documents" />
    
    <!-- Activity Log Card -->
    <x-details.parts.activity-log :activities="$activities" />
  </x-slot>
</x-details.layout>