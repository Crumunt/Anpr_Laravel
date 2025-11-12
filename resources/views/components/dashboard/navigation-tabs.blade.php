@props([
    'activeTab' => 'Applicants',
    'tabs' => ['Applicants', 'Registered Vehicles', 'RFID Management'],
])

<div class="border-b border-gray-200 px-6 py-4">
    <div class="flex border-b border-gray-200">
        <!-- Loop through tabs and generate buttons for each -->
        @foreach ($tabs as $tab)
        <button
            class="px-4 py-2 font-medium transition-colors duration-200"
            :class="{
                'text-green-600 border-b-2 border-green-600': activeTab === '{{ $tab }}',
                'text-gray-600 hover:text-green-600': activeTab !== '{{ $tab }}'
            }"
            @click="activeTab = '{{ $tab }}'; $dispatch('tab-changed', '{{ $tab }}')">
            {{ $tab }}
        </button>
        @endforeach
    </div>
</div>