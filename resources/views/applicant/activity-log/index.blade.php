@extends('layouts.applicant')

@section('title', 'Activity Log')

@section('content')
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Activity Log</h1>
        <p class="text-gray-500 mt-1">Track your account activities and vehicle registration history.</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        @if($activities->count() > 0)
            <div class="divide-y divide-gray-100">
                @foreach($activities as $activity)
                    <div class="p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start space-x-4">
                            <!-- Icon -->
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center
                                    {{ $activity['color'] === 'blue' ? 'bg-blue-100' : '' }}
                                    {{ $activity['color'] === 'green' ? 'bg-emerald-100' : '' }}
                                    {{ $activity['color'] === 'purple' ? 'bg-purple-100' : '' }}
                                    {{ $activity['color'] === 'amber' ? 'bg-amber-100' : '' }}
                                    {{ $activity['color'] === 'red' ? 'bg-red-100' : '' }}">
                                    <i class="fas fa-{{ $activity['icon'] }}
                                        {{ $activity['color'] === 'blue' ? 'text-blue-600' : '' }}
                                        {{ $activity['color'] === 'green' ? 'text-emerald-600' : '' }}
                                        {{ $activity['color'] === 'purple' ? 'text-purple-600' : '' }}
                                        {{ $activity['color'] === 'amber' ? 'text-amber-600' : '' }}
                                        {{ $activity['color'] === 'red' ? 'text-red-600' : '' }}"></i>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900">{{ $activity['title'] }}</p>
                                <p class="text-sm text-gray-500">{{ $activity['description'] }}</p>
                            </div>

                            <!-- Timestamp -->
                            <div class="flex-shrink-0 text-right">
                                <p class="text-sm font-medium text-gray-900">{{ $activity['date'] }}</p>
                                <p class="text-xs text-gray-500">{{ $activity['time'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="p-12 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-history text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No activity yet</h3>
                <p class="text-gray-500">Your activity log will appear here once you start using the system.</p>
            </div>
        @endif
    </div>
@endsection
