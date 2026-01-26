@extends('layouts.anpr-layout')

@section('title', 'Dashboard')
@section('page-title', 'ANPR Dashboard')
@section('page-subtitle', 'Real-time Vehicle Monitoring System')

@push('styles')
    <x-anpr.ui.styles />
    <x-anpr.anpr-dashboard.styles />
@endpush

@section('content')
    @include('components.anpr.anpr-dashboard.cards.stats-cards')

    <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 mb-6">
        @include('components.anpr.anpr-dashboard.ui.live-camera-feed')
        @php
            // Get the most recent vehicle scan for vehicle details panel
            $vehicle = !empty($recentScans) ? $recentScans[0] : [];
        @endphp
        @include('components.anpr.anpr-dashboard.table.vehicle-details', ['vehicle' => $vehicle])
    </div>

    @include('components.anpr.anpr-dashboard.table.recent-vehicles-table')

    @include('components.anpr.anpr-dashboard.modals.confirm-dialog')
    @include('components.anpr.anpr-dashboard.modals.notification-toast')
    @include('components.anpr.anpr-dashboard.modals.camera-settings')
    @include('components.anpr.anpr-dashboard.modals.vehicle-details-modal')
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
<script src="{{ asset('js/anpr/dashboard.js') }}"></script>
<script src="{{ asset('js/dashboard.js') }}"></script>
<script src="//unpkg.com/alpinejs" defer></script>
@endpush
