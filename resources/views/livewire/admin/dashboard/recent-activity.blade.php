<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Recent Applicants -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100 bg-gradient-to-r from-emerald-50 to-white">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Recent Applicants</h3>
                <a href="{{ route('admin.applicant') }}" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">View All</a>
            </div>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($recentApplicants as $applicant)
                <a href="{{ route('admin.applicant.show', $applicant['id']) }}" class="flex items-center gap-3 p-4 hover:bg-emerald-50/50 transition-colors">
                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-emerald-100 to-emerald-200 flex items-center justify-center text-emerald-700 font-semibold text-sm flex-shrink-0">
                        {{ $applicant['avatar_initials'] }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $applicant['name'] }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ $applicant['email'] }}</p>
                    </div>
                    <span class="text-xs text-gray-400">{{ $applicant['created_at'] }}</span>
                </a>
            @empty
                <div class="p-6 text-center text-gray-500">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <p class="text-sm">No recent applicants</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Recent Applications -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100 bg-gradient-to-r from-emerald-50 to-white">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Recent Applications</h3>
                <a href="{{ route('admin.applicant') }}" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">View All</a>
            </div>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($recentApplications as $application)
                <div class="p-4 hover:bg-emerald-50/50 transition-colors">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm font-medium text-gray-900">{{ $application['applicant_name'] }}</p>
                        @php
                            $statusColors = [
                                'pending' => 'bg-amber-100 text-amber-800',
                                'approved' => 'bg-emerald-100 text-emerald-800',
                                'rejected' => 'bg-red-100 text-red-800',
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$application['status']] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $application['status_label'] }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">{{ $application['applicant_type'] }}</span>
                        <span class="text-xs text-gray-400">{{ $application['created_at'] }}</span>
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-gray-500">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-sm">No recent applications</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Recent Vehicles -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100 bg-gradient-to-r from-emerald-50 to-white">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Recent Vehicles</h3>
                <a href="{{ route('admin.vehicles') }}" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">View All</a>
            </div>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($recentVehicles as $vehicle)
                <a href="{{ route('admin.vehicles.show', $vehicle['id']) }}" class="block p-4 hover:bg-emerald-50/50 transition-colors">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm font-medium text-gray-900">{{ $vehicle['plate_number'] }}</p>
                        @php
                            $vehicleStatusColors = [
                                'pending' => 'bg-amber-100 text-amber-800',
                                'approved' => 'bg-emerald-100 text-emerald-800',
                                'rejected' => 'bg-red-100 text-red-800',
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $vehicleStatusColors[$vehicle['status']] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $vehicle['status_label'] }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">{{ $vehicle['make_model'] ?: 'N/A' }} • {{ $vehicle['owner_name'] }}</span>
                        <span class="text-xs text-gray-400">{{ $vehicle['created_at'] }}</span>
                    </div>
                </a>
            @empty
                <div class="p-6 text-center text-gray-500">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                    <p class="text-sm">No recent vehicles</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
