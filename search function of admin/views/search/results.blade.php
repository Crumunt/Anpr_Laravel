@extends('layouts.app-layout')

@section('content')
@php
    $query = request('q', '');
    $sampleData = [
        // Admins
        ['type' => 'Admin', 'name' => 'Admin User', 'email' => 'admin@example.com', 'phone' => '(555) 123-4567', 'role' => 'Super Admin', 'last_login' => 'Today, 9:45 AM', 'status' => 'Active'],
        ['type' => 'Admin', 'name' => 'Moderator One', 'email' => 'mod1@example.com', 'phone' => '(555) 765-4321', 'role' => 'Moderator', 'last_login' => 'Yesterday, 3:20 PM', 'status' => 'Active'],
        ['type' => 'Admin', 'name' => 'Staff Member', 'email' => 'staff@example.com', 'phone' => '(555) 987-6543', 'role' => 'Admin', 'last_login' => 'Jun 15, 2023', 'status' => 'Inactive'],
        ['type' => 'Admin', 'name' => 'System User', 'email' => 'system@example.com', 'phone' => '(555) 456-7890', 'role' => 'Admin', 'last_login' => 'Today, 11:30 AM', 'status' => 'Active'],
        ['type' => 'Admin', 'name' => 'Guest Access', 'email' => 'guest@example.com', 'phone' => '(555) 234-5678', 'role' => 'Moderator', 'last_login' => 'May 22, 2023', 'status' => 'Inactive'],
        // Applicants
        ['type' => 'Applicant', 'name' => 'John Doe', 'email' => 'john.doe@example.com', 'phone' => '(555) 123-4567', 'status' => 'Pending', 'submitted_date' => '2023-05-15', 'rfid_tags' => 2, 'vehicles' => 1],
        ['type' => 'Applicant', 'name' => 'Jane Smith', 'email' => 'jane.smith@example.com', 'phone' => '(555) 765-4321', 'status' => 'Approved', 'submitted_date' => '2023-05-16', 'rfid_tags' => 1, 'vehicles' => 2],
        // Vehicles
        ['type' => 'Vehicle', 'vehicle' => 'Toyota Camry', 'owner' => 'John Doe', 'registration_date' => '2023-05-15'],
        ['type' => 'Vehicle', 'vehicle' => 'Honda Civic', 'owner' => 'Jane Smith', 'registration_date' => '2023-05-16'],
        // RFID
        ['type' => 'RFID', 'rfid_tag' => 'RFID12345', 'status' => 'Active', 'assigned_to' => 'John Doe'],
        ['type' => 'RFID', 'rfid_tag' => 'RFID67890', 'status' => 'Inactive', 'assigned_to' => 'Jane Smith'],
        ['type' => 'RFID', 'rfid_tag' => 'RFID23456', 'status' => 'Active', 'assigned_to' => 'Michael Johnson'],
        ['type' => 'RFID', 'rfid_tag' => 'RFID78901', 'status' => 'Active', 'assigned_to' => 'Emily Wilson'],
    ];

    $filtered = collect($sampleData)->filter(function($item) use ($query) {
        if (!$query) return true;
        foreach ($item as $val) {
            if (is_string($val) && stripos($val, $query) !== false) {
                return true;
            }
        }
        return false;
    });

    $grouped = $filtered->groupBy('type');
@endphp

<main class="flex-1 md:ml-64 p-6 pt-24" style="background-color: #f9fafb;">
    <h1 class="text-2xl font-extrabold mb-8">Results for keyword: <span class="text-green-700 text-2xl">"{{ $query }}"</span></h1>

    @if($grouped->has('Admin'))
        <section aria-label="Admin Results" class="mt-8 mb-4 fade-in">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-2xl font-bold mb-2 mt-2">Admins</h2>
                <div class="table-container w-full overflow-x-auto">
                    <x-dashboard.application-table
                        :type="'admin'"
                        :headers="[
                            ['key' => 'name', 'label' => 'Name'],
                            ['key' => 'email', 'label' => 'Email'],
                            ['key' => 'phone', 'label' => 'Phone'],
                            ['key' => 'role', 'label' => 'Role'],
                            ['key' => 'status', 'label' => 'Status'],
                            ['key' => 'last_login', 'label' => 'Last Login']
                        ]"
                        :rows="$grouped['Admin']->toArray()"
                        caption="All Administrators"
                        role="table"
                        aria-describedby="caption-admin"
                    />
                </div>
            </div>
        </section>
    @endif

    @if($grouped->has('Applicant'))
        <section aria-label="Applicant Results" class="mt-8 mb-4 fade-in">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-2xl font-bold mb-2 mt-2">Applicants</h2>
                <div class="table-container w-full overflow-x-auto">
                    <x-dashboard.application-table
                        :type="'applicant'"
                        :headers="[
                            ['key' => 'name', 'label' => 'Name'],
                            ['key' => 'email', 'label' => 'Email'],
                            ['key' => 'phone', 'label' => 'Phone'],
                            ['key' => 'status', 'label' => 'Status'],
                            ['key' => 'submitted_date', 'label' => 'Submitted Date'],
                            ['key' => 'rfid_tags', 'label' => 'RFID Tags'],
                            ['key' => 'vehicles', 'label' => 'Vehicles']
                        ]"
                        :rows="$grouped['Applicant']->toArray()"
                        caption=""
                        role="table"
                        aria-describedby="caption-applicant"
                    />
                </div>
            </div>
        </section>
    @endif

    @if($grouped->has('Vehicle'))
        <section aria-label="Vehicle Results" class="mt-8 mb-4 fade-in">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-2xl font-bold mb-2 mt-2">Vehicles</h2>
                <div class="table-container w-full overflow-x-auto">
                    <x-dashboard.application-table
                        :type="'vehicle'"
                        :headers="[
                            ['key' => 'vehicle', 'label' => 'Vehicle'],
                            ['key' => 'owner', 'label' => 'Owner'],
                            ['key' => 'registration_date', 'label' => 'Registration Date']
                        ]"
                        :rows="$grouped['Vehicle']->toArray()"
                        caption="Registered Vehicles list"
                        role="table"
                        aria-describedby=""
                    />
                </div>
            </div>
        </section>
    @endif

    @if($grouped->has('RFID'))
        <section aria-label="RFID Results" class="mt-8 mb-4 fade-in">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-2xl font-bold mb-2 mt-2">RFID Tags</h2>
                <div class="table-container w-full overflow-x-auto">
                    <x-dashboard.application-table
                        :type="'rfid'"
                        :headers="[
                            ['key' => 'rfid_tag', 'label' => 'RFID Tag'],
                            ['key' => 'status', 'label' => 'Status'],
                            ['key' => 'assigned_to', 'label' => 'Assigned To']
                        ]"
                        :rows="$grouped['RFID']->toArray()"
                        caption="RFID Tags Management list"
                        role="table"
                        aria-describedby=""
                    />
                </div>
            </div>
        </section>
    @endif

    @if($grouped->isEmpty())
        <div class="text-gray-500 mt-4">No results found.</div>
    @endif
</main>

<script src="https://unpkg.com/tippy.js@6"></script>
<script>
    // Make table rows clickable
    const rows = document.querySelectorAll('tr[data-url]');
    rows.forEach(row => {
        row.addEventListener('click', function() {
            window.location.href = this.dataset.url;
        });
    });

    // Initialize tooltips
    tippy('[title]', {
        content: (reference) => reference.getAttribute('title'),
        placement: 'top',
    });
</script>

<style>
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }
    @media (max-width: 768px) {
        .table-container {
            overflow-x: auto;
        }
    }
</style>
@endsection