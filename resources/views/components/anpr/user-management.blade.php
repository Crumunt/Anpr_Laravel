@extends('layouts.app-layout')

@section('title', 'User Management - CLSU ANPR System')

@section('styles')
    <x-anpr.ui.styles />
    <x-anpr.user-management.styles />
@endsection

@section('content')
<div class="flex h-screen overflow-hidden">
    <div id="overlay" class="overlay" onclick="toggleSidebar()"></div>
    
    @include('components.anpr.nav.sidebar')
    
    <div id="main-content" class="flex-1 overflow-auto bg-gray-50">
        @include('components.anpr.nav.header', [
            'pageTitle' => 'User Management',
            'searchPlaceholder' => 'Search users...',
            'searchAriaLabel' => 'Search users'
        ])
        
        <div class="p-4 md:p-8">
            @include('components.anpr.user-management.stats-cards')
            
            <div class="glass-card p-6 mt-6">
                @include('components.anpr.user-management.filters')
                @include('components.anpr.user-management.users-table')
                @include('components.anpr.user-management.pagination')
            </div>
        </div>
    </div>
</div>

@include('components.anpr.user-management.modals.add-user-modal')
@include('components.anpr.user-management.modals.edit-user-modal')
@include('components.anpr.user-management.modals.delete-user-modal')
@include('components.anpr.user-management.modals.view-user-modal')
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
<script src="{{ asset('js/anpr/user-management.js') }}"></script>
<script src="//unpkg.com/alpinejs" defer></script>
@endsection
