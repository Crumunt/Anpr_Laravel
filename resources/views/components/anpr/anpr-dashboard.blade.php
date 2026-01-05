@extends('layouts.app-layout')

@section('title', 'CLSU Vehicle Monitoring System')

@section('styles')
    <x-anpr.ui.styles />
    <x-anpr.anpr-dashboard.styles />
@endsection

@section('content')
<div class="flex h-screen overflow-hidden">
    <div id="overlay" class="overlay" onclick="toggleSidebar()"></div>
    
    @include('components.anpr.nav.sidebar')
    
    <div id="main-content" class="flex-1 overflow-auto bg-gray-50">
        @include('components.anpr.nav.header')
        
        @include('components.anpr.anpr-dashboard.cards.stats-cards')
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 px-4 md:px-8 pb-4 md:pb-8">
            @include('components.anpr.anpr-dashboard.ui.live-camera-feed')
            @include('components.anpr.anpr-dashboard.table.vehicle-details')
        </div>
        
        @include('components.anpr.anpr-dashboard.table.recent-vehicles-table')
    </div>
</div>

@include('components.anpr.anpr-dashboard.modals.confirm-dialog')
@include('components.anpr.anpr-dashboard.modals.notification-toast')
@include('components.anpr.anpr-dashboard.modals.camera-settings')
@include('components.anpr.anpr-dashboard.modals.vehicle-details-modal')
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
<script src="{{ asset('js/anpr/dashboard.js') }}"></script>
<script src="{{ asset('js/dashboard.js') }}"></script>
<script src="//unpkg.com/alpinejs" defer></script>
@endsection 