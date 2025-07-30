
<x-details.layout title="Applicant Details" type="applicant" :id="$id">
  @php
  $breadcrumbItems = [
      ['label' => 'Dashboard', 'url' => route('admin.welcome')],
      ['label' => 'Applicants', 'url' => route('admin.applicant')],
      ['label' => 'John Doe']
  ];
  
  $personalInfo = [
      ['label' => 'Full Name', 'value' => 'John Michael Doe'],
      ['label' => 'Email Address', 'value' => 'john.doe@example.com'],
      ['label' => 'Phone Number', 'value' => '(555) 123-4567'],
      ['label' => 'Date of Birth', 'value' => 'January 15, 1985'],
      ['label' => 'Gender', 'value' => 'Male'],
      ['label' => 'Nationality', 'value' => 'Filipino'],
      ['label' => 'Civil Status', 'value' => 'Married'],
      ['label' => 'Occupation', 'value' => 'University Professor']
  ];
  
  $addressInfo = [
      ['label' => 'Current Address', 'value' => '123 University Avenue, Science City of Muñoz', 'span' => 2],
      ['label' => 'City', 'value' => 'Science City of Muñoz'],
      ['label' => 'Province', 'value' => 'Nueva Ecija'],
      ['label' => 'Postal Code', 'value' => '3119'],
      ['label' => 'Country', 'value' => 'Philippines']
  ];
  
  $rfidHeaders = ['Tag Number', 'Status', 'Issue Date', 'Expiry Date'];
  $rfidRows = [
      [
          'tag' => 'RFID-001-2023', 
          'status' => ['label' => 'Active', 'class' => 'bg-green-100 text-green-800'], 
          'issueDate' => 'Jan 15, 2023', 
          'expiryDate' => 'Jan 15, 2024'
      ],
      [
          'tag' => 'RFID-002-2023', 
          'status' => ['label' => 'Inactive', 'class' => 'bg-gray-100 text-gray-800'], 
          'issueDate' => 'Mar 10, 2023', 
          'expiryDate' => 'Mar 10, 2024'
      ]
  ];
  
  $vehicleHeaders = ['Plate Number', 'Make & Model', 'Color', 'Registration Date'];
  $vehicleRows = [
      [
          'plate' => 'ABC-1234', 
          'model' => 'Toyota Fortuner', 
          'color' => 'White', 
          'regDate' => 'Jan 15, 2023'
      ]
  ];
  
  $documents = [
      [
          'name' => 'ID Card.pdf',
          'uploadDate' => 'Uploaded on Jan 10, 2023',
          'bgColor' => 'bg-blue-100',
          'icon' => 'fas fa-file-pdf',
          'iconColor' => 'text-blue-500'
      ],
      [
          'name' => 'Vehicle Registration.jpg',
          'uploadDate' => 'Uploaded on Jan 10, 2023',
          'bgColor' => 'bg-green-100',
          'icon' => 'fas fa-file-image',
          'iconColor' => 'text-green-500'
      ],
      [
          'name' => 'Proof of Employment.docx',
          'uploadDate' => 'Uploaded on Jan 10, 2023',
          'bgColor' => 'bg-yellow-100',
          'icon' => 'fas fa-file-alt',
          'iconColor' => 'text-yellow-500'
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
          'description' => 'Application approved by <span class="font-medium">Admin User</span>',
          'timestamp' => 'Jan 15, 2023 at 10:30 AM',
          'bgColor' => 'bg-green-100',
          'icon' => 'fas fa-check',
          'iconColor' => 'text-green-500'
      ],
      [
          'description' => 'RFID tag <span class="font-medium">RFID-001-2023</span> assigned',
          'timestamp' => 'Jan 15, 2023 at 10:25 AM',
          'bgColor' => 'bg-blue-100',
          'icon' => 'fas fa-tag',
          'iconColor' => 'text-blue-500'
      ],
      [
          'description' => 'Vehicle <span class="font-medium">ABC-1234</span> registered',
          'timestamp' => 'Jan 12, 2023 at 2:15 PM',
          'bgColor' => 'bg-purple-100',
          'icon' => 'fas fa-car',
          'iconColor' => 'text-purple-500'
      ],
      [
          'description' => 'Documents verified by <span class="font-medium">Verification Team</span>',
          'timestamp' => 'Jan 11, 2023 at 9:45 AM',
          'bgColor' => 'bg-yellow-100',
          'icon' => 'fas fa-file',
          'iconColor' => 'text-yellow-500'
      ],
      [
          'description' => 'Application submitted',
          'timestamp' => 'Jan 10, 2023 at 3:20 PM',
          'bgColor' => 'bg-indigo-100',
          'icon' => 'fas fa-user',
          'iconColor' => 'text-indigo-500'
      ]
  ];
  @endphp
  
  <x-slot name="breadcrumb">
    <x-details.parts.breadcrumb :items="$breadcrumbItems" />
  </x-slot>
  
  <x-slot name="header">
    <x-details.parts.profile-header 
      title="John Doe" 
      initials="JD" 
      status="Approved" 
      statusClass="bg-green-100 text-green-800" 
      user_id="APP-2023-001" 
      :isActive="true" 
    />
  </x-slot>
  
  <x-slot name="mainContent">
    <!-- Personal Information Card -->
    <x-details.parts.info-card title="Personal Information" editId="edit-personal-info-btn">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($personalInfo as $info)
          <x-details.parts.info-field :label="$info['label']" :value="$info['value']" :span="$info['span'] ?? 1" />
        @endforeach
      </div>
    </x-details.parts.info-card>
    
    <!-- Address Information Card -->
    <x-details.parts.info-card title="Address Information" editId="edit-address-info-btn">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($addressInfo as $info)
          <x-details.parts.info-field :label="$info['label']" :value="$info['value']" :span="$info['span'] ?? 1" />
        @endforeach
      </div>
    </x-details.parts.info-card>
    
    <!-- RFID Tags Card -->
    <x-details.parts.info-card title="RFID Tags" :editButton="false">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-sm font-medium text-gray-700">Assigned Tags</h3>
        <button id="add-rfid-btn" class="text-green-600 hover:text-green-700 text-sm font-medium">
          <i class="fas fa-plus mr-1"></i> Add Tag
        </button>
      </div>
      <x-details.parts.data-table :headers="$rfidHeaders" :rows="$rfidRows" />
    </x-details.parts.info-card>
    <x-details.parts.access-history 
    title="Access History" 
    :accessRecords="$accessRecords" 
    :currentPage="2" 
    :totalPages="10" 
/>
    <!-- Vehicles Card -->
    <x-details.parts.info-card title="Registered Vehicles" :editButton="false">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-sm font-medium text-gray-700">Vehicles</h3>
        <button id="add-vehicle-btn" class="text-green-600 hover:text-green-700 text-sm font-medium">
          <i class="fas fa-plus mr-1"></i> Add Vehicle
        </button>
      </div>
      <x-details.parts.data-table :headers="$vehicleHeaders" :rows="$vehicleRows" />
    </x-details.parts.info-card>
  </x-slot>
  
  <x-slot name="sideContent">
    <!-- Status Card -->
    <x-details.parts.status-card 
      status="Approved" 
      statusClass="bg-green-100 text-green-800" 
      applicationDate="Jan 10, 2023" 
      approvalDate="Jan 15, 2023" 
      approvedBy="Admin User" 
      :progress="100" 
    />
  
    <!-- Documents Card -->
    <x-details.parts.documents-card :documents="$documents" />
    
    <!-- Activity Log Card -->
    <x-details.parts.activity-log :activities="$activities" />
  </x-slot>
</x-details.layout>
