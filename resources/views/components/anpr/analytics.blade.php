@extends('layouts.app-layout')

@section('title', 'CLSU Vehicle Monitoring System - Analytics')

@section('styles')
    <x-anpr.ui.styles />
    <x-anpr.analytics.analytics-styles />
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

@endsection

@section('content')
<div class="flex h-screen overflow-hidden">
    <div id="overlay" class="overlay" onclick="toggleSidebar()"></div>
    
    @include('components.anpr.nav.sidebar')
    
    <div id="main-content" class="flex-1 overflow-auto bg-gray-50">
        @include('components.anpr.nav.header')
        
        <div class="p-4 md:p-8">
            <!-- Statistics Cards -->
            <x-anpr.analytics.analytics-stats-cards 
                :vehicles_scanned="1284"
                :alerts_issued="42"
                :flagged_vehicles="18"
                :rfid_passes="956"
            />
            
            <!-- Analytics Charts -->
            <x-anpr.analytics.analytics-charts 
                :vehicle_activity_data="[156, 189, 203, 178, 195, 98, 65]"
                :vehicle_type_data="[65, 20, 12, 3]"
                :alert_trends_data="[8, 14, 12, 8, 26]"
                :entrance_performance_data="[45, 35, 20]"
            />
            
            <!-- Recent Vehicles Table -->
            <x-anpr.analytics.analytics-table 
                title="Recent Vehicles"
                subtitle="Last 24 hours activity"
                search_placeholder="Search plates..."
                :total_entries="4"
            />
        </div>
    </div>
</div>
@include('components.anpr.anpr-dashboard.modals.confirm-dialog')
@include('components.anpr.anpr-dashboard.modals.notification-toast')
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
<script src="{{ asset('js/anpr/analytics.js') }}"></script>
<script src="//unpkg.com/alpinejs" defer></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection 