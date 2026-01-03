@extends('layouts.app-layout')
@section('main-content')
    <div class="flex-1 md:ml-64 p-6 pt-24">
        <div class="w-full bg-white rounded-xl shadow-sm border border-gray-100 transition-all duration-300 hover:shadow-md">
            <div class="p-6 space-y-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Search results</h2>
                        <p class="text-sm text-gray-500 mt-1">Showing results for:
                            <span class="px-2 py-0.5 rounded-md bg-green-50 text-green-700 border border-green-100 font-medium">{{ $query }}</span>
                        </p>
                    </div>
                    <form action="{{ route('admin.search.results') }}" method="get" class="w-full md:w-auto">
                        <div class="relative">
                            <input
                                type="text"
                                name="q"
                                value="{{ $query }}"
                                placeholder="Refine search..."
                                class="w-full md:w-80 pl-10 pr-10 py-2.5 text-sm rounded-xl outline-none border border-gray-200 focus:border-green-500 ring-2 ring-[transparent] focus:ring-green-500 bg-white shadow-sm focus:shadow-md"
                            />
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <button type="submit" class="hidden md:block absolute inset-y-0 right-0 pr-3 text-green-600 hover:text-green-700">
                                <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="p-4 rounded-lg border border-gray-100 bg-gray-50">
                        <div class="text-xs text-gray-500 uppercase">Total Matches</div>
                        <div class="text-2xl font-semibold text-gray-800 mt-1">
                            {{ ($userResults->count() + $vehicleResults->count()) }}
                        </div>
                    </div>
                    <div class="p-4 rounded-lg border border-gray-100 bg-gray-50">
                        <div class="text-xs text-gray-500 uppercase">Applicants</div>
                        <div class="text-2xl font-semibold text-gray-800 mt-1">
                            {{ $userResults->count() }}
                        </div>
                    </div>
                    <div class="p-4 rounded-lg border border-gray-100 bg-gray-50">
                        <div class="text-xs text-gray-500 uppercase">Vehicles</div>
                        <div class="text-2xl font-semibold text-gray-800 mt-1">
                            {{ $vehicleResults->count() }}
                        </div>
                    </div>
                </div>

                @php
                    $hasResults = $userResults->count() > 0 || $vehicleResults->count() > 0;
                @endphp

                @if(!$hasResults)
                    <div class="text-center py-16">
                        <div class="mx-auto w-16 h-16 rounded-full bg-green-50 border border-green-100 flex items-center justify-center">
                            <i class="fas fa-search text-green-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 mt-4">No results found</h3>
                        <p class="text-sm text-gray-500 mt-1">Try different keywords or filters.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-800">Applicants</h3>
                                <span class="text-xs px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 border border-blue-100">{{ $userResults->count() }}</span>
                            </div>
                            <div class="rounded-xl border border-gray-100 overflow-hidden">
                                @forelse($userResults as $u)
                                    <a href="{{ route('admin.applicant.show', $u->id) }}" class="flex items-start gap-3 px-4 py-3 hover:bg-green-50 transition border-b last:border-b-0">
                                        <div class="mt-0.5">
                                            <svg class="h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2">
                                                <div class="font-medium text-gray-800 truncate">{{ $u->details?->full_name ?? $u->email }}</div>
                                                <span class="text-[10px] px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 border border-blue-100">Applicant</span>
                                            </div>
                                            <div class="text-xs text-gray-500 truncate mt-0.5">
                                                @if($u->details?->clsu_id)
                                                    ID: {{ $u->details->clsu_id }} •
                                                @endif
                                                {{ $u->email }}
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <div class="px-4 py-6 text-sm text-gray-500 text-center">No applicants found.</div>
                                @endforelse
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-800">Vehicles</h3>
                                <span class="text-xs px-2 py-0.5 rounded-full bg-yellow-50 text-yellow-700 border border-yellow-100">{{ $vehicleResults->count() }}</span>
                            </div>
                            <div class="rounded-xl border border-gray-100 overflow-hidden">
                                @forelse($vehicleResults as $v)
                                    @php
                                        $vehicleInfo = $v->vehicle_info ?: trim(($v->make ?? '') . ' ' . ($v->model ?? ''));
                                        $ownerName = $v->user?->details?->full_name ?? 'Unknown Owner';
                                        $ownerId = $v->user?->id;
                                    @endphp
                                    <a href="{{ $ownerId ? route('admin.applicant.show', $ownerId) : '#' }}" class="flex items-start gap-3 px-4 py-3 hover:bg-green-50 transition border-b last:border-b-0">
                                        <div class="mt-0.5">
                                            <svg class="h-5 w-5 text-yellow-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <div class="font-medium text-gray-800 truncate">{{ $vehicleInfo ?: 'Unknown Vehicle' }}</div>
                                                <span class="text-[10px] px-2 py-0.5 rounded-full bg-yellow-50 text-yellow-700 border border-yellow-100">Vehicle</span>
                                                @if($v->assigned_gate_pass)
                                                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-green-50 text-green-700 border border-green-100">
                                                        GP: {{ $v->assigned_gate_pass }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="text-xs text-gray-500 truncate mt-0.5">
                                                {{ $v->plate_number ?? 'No plate' }} • Owner: {{ $ownerName }}
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <div class="px-4 py-6 text-sm text-gray-500 text-center">No vehicles found.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <x-footer.footer></x-footer.footer>
@endsection


