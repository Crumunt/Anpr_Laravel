@extends('layouts.anpr-layout')

@section('title', 'Dashboard')
@section('page-title', 'ANPR Dashboard')
@section('page-subtitle', 'Real-time Vehicle Monitoring System')

@push('styles')
    <x-anpr.ui.styles />
    <x-anpr.anpr-dashboard.styles />
@endpush

@section('content')
    {{-- Dashboard Metric Cards (Livewire Component) --}}
    <livewire:a-n-p-r.dashboard-cards />

    {{-- Recent Vehicles Table (Livewire Component) --}}
    <div class="mt-6">
        <livewire:a-n-p-r.recent-vehicles-table />
    </div>

    {{-- Simple Toast Notification --}}
    <div id="notification-toast" class="fixed bottom-4 right-4 z-50 hidden transform transition-all duration-300 ease-out">
        <div class="flex items-center px-4 py-3 rounded-lg shadow-lg text-white min-w-[300px]">
            <i id="toast-icon" class="fas fa-check-circle mr-3"></i>
            <span id="toast-message" class="text-sm font-medium">Notification message</span>
            <button onclick="hideToast()" class="ml-auto text-white/80 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
<script>
    // Listen for Livewire events and show toast notifications
    document.addEventListener('livewire:init', () => {
        Livewire.on('record-flagged', (data) => {
            showToast(data[0].message, data[0].status ? 'warning' : 'success');
        });

        Livewire.on('record-updated', (data) => {
            showToast(data[0].message, 'success');
        });
    });

    function showToast(message, type = 'info') {
        const toast = document.getElementById('notification-toast');
        const toastMessage = document.getElementById('toast-message');
        const toastIcon = document.getElementById('toast-icon');

        if (toast && toastMessage) {
            toastMessage.textContent = message;

            // Reset classes
            toast.querySelector('div').className = 'flex items-center px-4 py-3 rounded-lg shadow-lg text-white min-w-[300px]';

            const config = {
                'success': { bg: 'bg-green-500', icon: 'fa-check-circle' },
                'error': { bg: 'bg-red-500', icon: 'fa-exclamation-circle' },
                'warning': { bg: 'bg-amber-500', icon: 'fa-exclamation-triangle' },
                'info': { bg: 'bg-blue-500', icon: 'fa-info-circle' }
            }[type] || { bg: 'bg-blue-500', icon: 'fa-info-circle' };

            toast.querySelector('div').classList.add(config.bg);
            toastIcon.className = `fas ${config.icon} mr-3`;

            toast.classList.remove('hidden');
            toast.classList.add('animate-slide-up');

            setTimeout(() => {
                hideToast();
            }, 3000);
        }
    }

    function hideToast() {
        const toast = document.getElementById('notification-toast');
        if (toast) {
            toast.classList.add('hidden');
        }
    }
</script>
<style>
    @keyframes slideUp {
        from {
            transform: translateY(100%);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
    .animate-slide-up {
        animation: slideUp 0.3s ease-out forwards;
    }
</style>
@endpush
