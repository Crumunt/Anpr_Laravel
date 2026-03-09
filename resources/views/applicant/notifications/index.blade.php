@extends('layouts.applicant')

@section('title', 'Notifications')

@section('content')
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6" aria-label="Breadcrumb">
        <a href="{{ route('applicant.dashboard') }}" class="hover:text-green-700 transition-colors">Dashboard</a>
        <i class="fas fa-chevron-right text-[10px] text-gray-300"></i>
        <span class="text-gray-900 font-medium">Notifications</span>
    </nav>

    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Notifications</h1>
        <p class="text-gray-500 mt-1">View your notifications and manage rejected documents.</p>
    </div>

    <!-- Notifications Panel Component -->
    @livewire('applicant.notifications-panel')
@endsection
