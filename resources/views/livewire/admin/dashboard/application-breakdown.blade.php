<div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Applicant Type Breakdown -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-b border-gray-100 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-800">Applicant Types</h3>
                <p class="text-sm text-gray-500">Distribution by applicant category</p>
            </div>
            <div class="p-6">
                @if(count($applicantTypes) > 0)
                <div class="space-y-4">
                    @php
                    $colors = ['bg-blue-500', 'bg-green-500', 'bg-amber-500', 'bg-purple-500', 'bg-pink-500', 'bg-indigo-500'];
                    @endphp
                    @foreach($applicantTypes as $index => $type)
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">{{ $type['type'] }}</span>
                            <span class="text-sm text-gray-500">{{ $type['count'] }} ({{ $type['percentage'] }}%)</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="{{ $colors[$index % count($colors)] }} h-2.5 rounded-full transition-all duration-500" style="width: {{ $type['percentage'] }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <p class="text-sm">No data available</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Monthly Application Trend -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-b border-gray-100 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-800">Application Trend</h3>
                <p class="text-sm text-gray-500">Last 6 months</p>
            </div>
            <div class="p-6">
                @if(count($monthlyTrend) > 0)
                @php
                $maxCount = max(array_column($monthlyTrend, 'count')) ?: 1;
                @endphp
                <div class="flex items-end justify-between h-40 gap-2">
                    @foreach($monthlyTrend as $month)
                    <div class="flex flex-col items-center flex-1">
                        <div class="w-full flex flex-col items-center justify-end h-32">
                            <span class="text-xs font-medium text-gray-700 mb-1">{{ $month['count'] }}</span>
                            <div class="w-full max-w-[40px] bg-green-500 rounded-t transition-all duration-500 hover:bg-green-600" style="height: {{ ($month['count'] / $maxCount) * 100 }}%"></div>
                        </div>
                        <span class="text-xs text-gray-500 mt-2">{{ $month['month'] }}</span>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                    </svg>
                    <p class="text-sm">No trend data available</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Admin Team Breakdown -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="p-4 border-b border-gray-100 bg-gray-50">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Admin Team</h3>
                    <p class="text-sm text-gray-500">Staff distribution by role</p>
                </div>
                <a href="{{ route('admin.admins') }}" class="text-sm text-green-600 hover:text-green-700 font-medium">Manage Admins</a>
            </div>
        </div>
        <div class="p-6">
            @if(count($admins) > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4">
                @php
                $roleColors = [
                'Super Admin' => 'from-purple-500 to-purple-600',
                'Admin Editor' => 'from-blue-500 to-blue-600',
                'Admin Viewer' => 'from-cyan-500 to-cyan-600',
                'Encoder' => 'from-green-500 to-green-600',
                'Maintenance' => 'from-amber-500 to-amber-600',
                ];
                $roleIcons = [
                'Super Admin' => '
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />',
                'Admin Editor' => '
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />',
                'Admin Viewer' => '
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />',
                'Encoder' => '
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />',
                'Maintenance' => '
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />',
                ];
                @endphp
                @foreach($admins as $admin)
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto rounded-full bg-gradient-to-br {{ $roleColors[$admin['role']] ?? 'from-gray-500 to-gray-600' }} flex items-center justify-center mb-2">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            {!! $roleIcons[$admin['role']] ?? '
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />' !!}
                        </svg>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">{{ $admin['count'] }}</p>
                    <p class="text-xs text-gray-500">{{ $admin['role'] }}</p>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8 text-gray-500">
                <p class="text-sm">No admin data available</p>
            </div>
            @endif
        </div>
    </div>
</div>
