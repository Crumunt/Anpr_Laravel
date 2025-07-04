@extends('layouts.app-layout')
@section('main-content')
<body style="background-color: #f9fafb;">
<div class="flex-1 md:ml-64 p-6 pt-24">
<div class="w-full bg-white rounded-xl shadow-sm border border-gray-100 transition-all duration-300 hover:shadow-md">
            <div class="p-6">
                <div class="w-full space-y-6">
                    <!-- Header -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <h2 class="text-2xl font-bold text-gray-800">RFID Management</h2>
                        <div class="flex space-x-2">
                            <!-- <x-dashboard.buttons
                                :icon="false"
                                class="bg-emerald-600 hover:bg-emerald-700"
                                data-open-modal="rfid-modal">
                                <span slot="icon" class="mr-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <path d="M8 12h8"></path>
                                        <path d="M12 8v8"></path>
                                    </svg>
                                </span>
                                <span>Add RFID</span>
                            </x-dashboard.buttons> -->
                        </div>
                    </div>
                    <!-- RFID Modal -->
                    <x-details.modal id="rfid-modal" type="rfid" action="add" />
                    
                    <!-- Moved search filter inside the space-y-6 container -->
                    <x-dashboard.search-filter :showType="'rfid'" />
                    
                    <!-- Removed mt-6 since space-y-6 will handle spacing -->
                    <div class="w-full space-y-6">
                        <x-dashboard.application-table
                            :type="'rfid'"
                            :headers="[
                                ['key' => 'rfid_tag', 'label' => 'RFID Tag', 'width' => '200px'],
                                ['key' => 'status', 'label' => 'Status', 'width' => '120px'],
                                ['key' => 'assigned_to', 'label' => 'Assigned To', 'width' => '200px']
                            ]"
                            :rows="[
                                ['rfid_tag' => 'RFID12345', 'status' => 'Active', 'assigned_to' => 'John Doe'],
                                ['rfid_tag' => 'RFID67890', 'status' => 'Inactive', 'assigned_to' => 'Jane Smith'],
                                ['rfid_tag' => 'RFID23456', 'status' => 'Active', 'assigned_to' => 'Michael Johnson'],
                                ['rfid_tag' => 'RFID78901', 'status' => 'Active', 'assigned_to' => 'Emily Wilson'],
                                ['rfid_tag' => 'RFID34567', 'status' => 'Pending', 'assigned_to' => 'Robert Thompson'],
                                ['rfid_tag' => 'RFID89012', 'status' => 'Active', 'assigned_to' => 'Sarah Davis'],
                                ['rfid_tag' => 'RFID45678', 'status' => 'Active', 'assigned_to' => 'David Martinez'],
                                ['rfid_tag' => 'RFID90123', 'status' => 'Inactive', 'assigned_to' => 'Jennifer Garcia'],
                                ['rfid_tag' => 'RFID56789', 'status' => 'Lost', 'assigned_to' => 'Christopher Robinson'],
                                ['rfid_tag' => 'RFID01234', 'status' => 'Active', 'assigned_to' => 'Lisa Anderson'],
                                ['rfid_tag' => 'RFID54321', 'status' => 'Active', 'assigned_to' => 'Thomas Wilson'],
                                ['rfid_tag' => 'RFID09876', 'status' => 'Inactive', 'assigned_to' => 'Maria Rodriguez'],
                                ['rfid_tag' => 'RFID65432', 'status' => 'Active', 'assigned_to' => 'Daniel Lewis'],
                                ['rfid_tag' => 'RFID21098', 'status' => 'Pending', 'assigned_to' => 'Patricia Walker'],
                                ['rfid_tag' => 'RFID76543', 'status' => 'Active', 'assigned_to' => 'James Hall'],
                                ['rfid_tag' => 'RFID32109', 'status' => 'Active', 'assigned_to' => 'Nancy Allen'],
                                ['rfid_tag' => 'RFID87654', 'status' => 'Inactive', 'assigned_to' => 'Steven Young']
                            ]"
                            caption="RFID Tags Management list" />
                        <x-dashboard.pagination></x-dashboard.pagination>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-footer.footer></x-footer.footer>
</body>
@endsection