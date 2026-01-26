@extends('layouts.applicant')

@section('title', 'Activity Log')

@section('content')
    <!-- Enhanced Page Header -->
    <div class="mb-8 lg:mb-10">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 tracking-tight mb-2">Activity Log</h1>
        <p class="text-base text-gray-600 leading-relaxed">Track your account activities and vehicle registration history</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        @if($activities->count() > 0)
            <!-- Enhanced Activity List with Timeline -->
            <div class="divide-y divide-gray-100">
                @foreach($activities as $index => $activity)
                    <div class="p-5 lg:p-6 hover:bg-gradient-to-r hover:from-gray-50 hover:to-transparent transition-all duration-200 group">
                        <div class="flex items-start gap-4 lg:gap-6">
                            <!-- Enhanced Icon with Timeline -->
                            <div class="relative flex-shrink-0">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-sm ring-4 ring-white group-hover:scale-110 transition-transform duration-200
                                    {{ $activity['color'] === 'blue' ? 'bg-gradient-to-br from-blue-100 to-blue-200' : '' }}
                                    {{ $activity['color'] === 'green' ? 'bg-gradient-to-br from-emerald-100 to-emerald-200' : '' }}
                                    {{ $activity['color'] === 'purple' ? 'bg-gradient-to-br from-purple-100 to-purple-200' : '' }}
                                    {{ $activity['color'] === 'amber' ? 'bg-gradient-to-br from-amber-100 to-amber-200' : '' }}
                                    {{ $activity['color'] === 'red' ? 'bg-gradient-to-br from-red-100 to-red-200' : '' }}
                                    {{ !isset($activity['color']) ? 'bg-gradient-to-br from-gray-100 to-gray-200' : '' }}">
                                    <i class="fas fa-{{ $activity['icon'] ?? 'circle' }} text-lg
                                        {{ $activity['color'] === 'blue' ? 'text-blue-600' : '' }}
                                        {{ $activity['color'] === 'green' ? 'text-emerald-600' : '' }}
                                        {{ $activity['color'] === 'purple' ? 'text-purple-600' : '' }}
                                        {{ $activity['color'] === 'amber' ? 'text-amber-600' : '' }}
                                        {{ $activity['color'] === 'red' ? 'text-red-600' : '' }}
                                        {{ !isset($activity['color']) ? 'text-gray-600' : '' }}"></i>
                                </div>
                                @if(!$loop->last)
                                    <div class="absolute top-12 left-1/2 transform -translate-x-1/2 w-0.5 h-full bg-gray-200"></div>
                                @endif
                            </div>

                            <!-- Enhanced Content -->
                            <div class="flex-1 min-w-0">
                                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 group-hover:border-gray-300 group-hover:shadow-sm transition-all duration-200">
                                    <p class="font-bold text-gray-900 mb-1.5">{{ $activity['title'] }}</p>
                                    <p class="text-sm text-gray-600 leading-relaxed">{{ $activity['description'] }}</p>
                                </div>
                            </div>

                            <!-- Enhanced Timestamp -->
                            <div class="flex-shrink-0 text-right">
                                <p class="text-sm font-bold text-gray-900 mb-1">{{ $activity['date'] }}</p>
                                <p class="text-xs font-medium text-gray-500">{{ $activity['time'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Enhanced Empty State -->
            <div class="p-12 lg:p-16 text-center">
                <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-sm">
                    <i class="fas fa-history text-gray-400 text-4xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">No activity yet</h3>
                <p class="text-gray-600 mb-6 max-w-md mx-auto leading-relaxed">
                    Your activity log will appear here once you start using the system. Activities include vehicle registrations, profile updates, and account changes.
                </p>
                <a href="{{ route('applicant.dashboard') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white font-semibold rounded-xl hover:from-emerald-700 hover:to-emerald-800 shadow-md hover:shadow-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back to Dashboard</span>
                </a>
            </div>
        @endif
    </div>
@endsection
