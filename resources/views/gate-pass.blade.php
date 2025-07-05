@extends('layouts.app-layout')
@section('main-content')
<body style="background-color: #f9fafb;">
<div class="flex-1 md:ml-64 p-6 pt-24">
<div class="w-full bg-white rounded-xl shadow-sm border border-gray-100 transition-all duration-300 hover:shadow-md">
            <div class="p-6">
                <div class="w-full space-y-6">
                    <!-- Header -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <h2 class="text-2xl font-bold text-gray-800">Gate Pass Management</h2>
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
                    <x-dashboard.search-filter :showType="'gatePass'" />
                    
                    <!-- Removed mt-6 since space-y-6 will handle spacing -->
                    <div class="w-full space-y-6">
                        <x-dashboard.application-table
                            :type="'rfid'"
                            context="gate_pass"
                            :rows="[
                                ['gate_pass' => 'GP12345', 'status' => ['label' => 'Active'], 'assigned_to' => 'John Doe'],
                                ['gate_pass' => 'GP67890', 'status' => ['label' => 'Inactive'], 'assigned_to' => 'Jane Smith'],
                                ['gate_pass' => 'GP23456', 'status' => ['label' => 'Active'], 'assigned_to' => 'Michael Johnson'],
                                ['gate_pass' => 'GP78901', 'status' => ['label' => 'Active'], 'assigned_to' => 'Emily Wilson'],
                                ['gate_pass' => 'GP34567', 'status' => ['label' => 'Pending'], 'assigned_to' => 'Robert Thompson'],
                                ['gate_pass' => 'GP89012', 'status' => ['label' => 'Active'], 'assigned_to' => 'Sarah Davis'],
                                ['gate_pass' => 'GP45678', 'status' => ['label' => 'Active'], 'assigned_to' => 'David Martinez'],
                                ['gate_pass' => 'GP90123', 'status' => ['label' => 'Inactive'], 'assigned_to' => 'Jennifer Garcia'],
                                ['gate_pass' => 'GP56789', 'status' => ['label' => 'Lost'], 'assigned_to' => 'Christopher Robinson'],
                                ['gate_pass' => 'GP01234', 'status' => ['label' => 'Active'], 'assigned_to' => 'Lisa Anderson'],
                                ['gate_pass' => 'GP54321', 'status' => ['label' => 'Active'], 'assigned_to' => 'Thomas Wilson'],
                                ['gate_pass' => 'GP09876', 'status' => ['label' => 'Inactive'], 'assigned_to' => 'Maria Rodriguez'],
                                ['gate_pass' => 'GP65432', 'status' => ['label' => 'Active'], 'assigned_to' => 'Daniel Lewis'],
                                ['gate_pass' => 'GP21098', 'status' => ['label' => 'Pending'], 'assigned_to' => 'Patricia Walker'],
                                ['gate_pass' => 'GP76543', 'status' => ['label' => 'Active'], 'assigned_to' => 'James Hall'],
                                ['gate_pass' => 'GP32109', 'status' => ['label' => 'Active'], 'assigned_to' => 'Nancy Allen'],
                                ['gate_pass' => 'GP87654', 'status' => ['label' => 'Inactive'], 'assigned_to' => 'Steven Young']
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