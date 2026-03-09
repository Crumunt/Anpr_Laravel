@extends('layouts.applicant')

@section('title', 'Activity Log')

@section('content')
    <!-- Page Header -->
    <div class="mb-8">
        <nav class="flex items-center gap-2 text-sm text-gray-500 mb-3" aria-label="Breadcrumb">
            <a href="{{ route('applicant.dashboard') }}" class="hover:text-green-700 transition-colors">Dashboard</a>
            <i class="fas fa-chevron-right text-[10px] text-gray-300"></i>
            <span class="text-gray-900 font-medium">Activity Log</span>
        </nav>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Activity Log</h1>
        <p class="text-gray-500 mt-1">Track your account activities and vehicle registration history.</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
        @if($activities->count() > 0)
            <!-- Card Header -->
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="fas fa-timeline text-gray-400 text-sm"></i>
                    <h3 class="text-base font-bold text-gray-900">Recent Activity</h3>
                </div>
                <span class="text-xs font-semibold text-gray-400 bg-gray-50 px-2.5 py-1 rounded-lg border border-gray-100">{{ $activities->count() }} entries</span>
            </div>

            <!-- Timeline -->
            <div class="divide-y divide-gray-50">
                @foreach($activities as $index => $activity)
                    <div class="px-5 py-4 sm:px-6 sm:py-5 hover:bg-gray-50/50 transition-colors group">
                        <div class="flex items-start gap-4">
                            <!-- Timeline Icon -->
                            <div class="relative flex-shrink-0">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center ring-4 ring-white group-hover:scale-105 transition-transform
                                    {{ $activity['color'] === 'blue' ? 'bg-blue-50 text-blue-600' : '' }}
                                    {{ $activity['color'] === 'green' ? 'bg-green-50 text-green-600' : '' }}
                                    {{ $activity['color'] === 'purple' ? 'bg-purple-50 text-purple-600' : '' }}
                                    {{ $activity['color'] === 'amber' ? 'bg-amber-50 text-amber-600' : '' }}
                                    {{ $activity['color'] === 'red' ? 'bg-red-50 text-red-600' : '' }}
                                    {{ !isset($activity['color']) ? 'bg-gray-50 text-gray-500' : '' }}">
                                    <i class="fas fa-{{ $activity['icon'] ?? 'circle' }} text-sm"></i>
                                </div>
                                @if(!$loop->last)
                                    <div class="absolute top-10 left-1/2 -translate-x-1/2 w-px h-[calc(100%+1rem)] bg-gray-100"></div>
                                @endif
                            </div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0 pt-0.5">
                                <p class="font-semibold text-gray-900 text-sm">{{ $activity['title'] }}</p>
                                <p class="text-sm text-gray-500 mt-0.5 leading-relaxed">{{ $activity['description'] }}</p>
                            </div>

                            <!-- Timestamp -->
                            <div class="flex-shrink-0 text-right pt-0.5">
                                <p class="text-sm font-semibold text-gray-700">{{ $activity['date'] }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $activity['time'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="p-12 text-center">
                <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4 border-2 border-dashed border-gray-200">
                    <i class="fas fa-clock-rotate-left text-gray-300 text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">No activity yet</h3>
                <p class="text-gray-500 mb-6 max-w-sm mx-auto text-sm leading-relaxed">
                    Your activity log will appear here once you start using the system.
                </p>
                <a href="{{ route('applicant.dashboard') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#1a5c1f] text-white font-semibold rounded-xl shadow-sm transition-colors hover:bg-green-800 focus-ring text-sm">
                    <i class="fas fa-arrow-left text-xs"></i>
                    <span>Back to Dashboard</span>
                </a>
            </div>
        @endif
    </div>
@endsection
