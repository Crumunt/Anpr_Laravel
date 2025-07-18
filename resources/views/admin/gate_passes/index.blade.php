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
                    </div>
                    <!-- RFID Modal -->
                    <x-details.modal id="rfid-modal" type="rfid" action="add" />
                    
                    <!-- Moved search filter inside the space-y-6 container -->
                    <x-dashboard.search-filter :showType="'gatePass'" />
                    
                    <!-- Removed mt-6 since space-y-6 will handle spacing -->
                    <div class="w-full space-y-6">
                        <x-table.data
                            type="gate_pass"
                            context="gate_pass"
                            :rows="$gatePasses"
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