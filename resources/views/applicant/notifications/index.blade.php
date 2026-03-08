@extends('layouts.applicant')

@section('title', 'Notifications')

@section('content')
    <!-- Breadcrumb -->
    <nav class="mb-6" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2 text-sm text-gray-500">
            <li><a href="{{ route('applicant.dashboard') }}" class="hover:text-emerald-600">Dashboard</a></li>
            <li><i class="fas fa-chevron-right text-xs"></i></li>
            <li class="text-gray-900 font-medium">Notifications</li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Notifications</h1>
        <p class="text-gray-600">View your notifications and manage rejected documents.</p>
    </div>

    <!-- Notifications Panel Component -->
    @livewire('applicant.notifications-panel')
@endsection
