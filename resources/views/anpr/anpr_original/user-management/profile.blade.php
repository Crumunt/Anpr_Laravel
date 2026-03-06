@extends('layouts.app-layout')

@section('title', 'CLSU Vehicle Monitoring System - Personnel Profile')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<style>
    .info-label {
        font-size: 0.8rem;
        letter-spacing: 0.02em;
    }

    .info-value {
        font-size: 1rem;
        font-weight: 600;
    }

    .section-divider {
        height: 1px;
        background: linear-gradient(90deg, rgba(0, 99, 0, 0), rgba(0, 99, 0, 0.3), rgba(0, 99, 0, 0));
    }
    .tab-button.active {
        border-bottom-color: #16a34a;
        color: #16a34a;
    }
    .tab-content {
        display: block;
    }
    .tab-content.hidden {
        display: none;
    }
</style>
@endsection

@section('content')
@php
    // Get profile data from database
    $fullName = $user->full_name ?? ($user->first_name . ' ' . ($user->middle_name ? $user->middle_name . ' ' : '') . $user->last_name);
    $role = $details->designation ?? 'Security Personnel';
    $department = $details->department ?? 'Not Assigned';
    $employeeId = $details->employee_id ?? 'N/A';
    $badgeId = $details->badge_id ?? 'N/A';
    $permissionLevel = $details->authorization_level ?? 'Level 1 — Standard';
    $email = $user->email ?? 'N/A';
    $phone = $details->phone_number ?? 'N/A';
    $extension = $details->extension ?? 'N/A';
    $photo = $details->profile_photo ? asset('storage/' . $details->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($fullName) . '&background=006633&color=fff&size=200';
    $lastLogin = $details->last_login_at ? $details->last_login_at->format('M d, Y \a\t H:i') : 'Never';
    $status = $details->is_active ?? true ? 'Active on Duty' : 'Inactive';
    $statusClass = ($details->is_active ?? true) ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700';
    $assignedGates = $details->assigned_gates ?? [];
    $workSchedule = $details->work_schedule ?? [];
    $shifts = $details->shifts ?? [];

    $personalDetails = [
        ['label' => 'Full Name', 'value' => $fullName],
        ['label' => 'Employee ID', 'value' => $employeeId],
        ['label' => 'Department', 'value' => $department],
        ['label' => 'Designation / Role', 'value' => $role],
        ['label' => 'Badge ID', 'value' => $badgeId],
        ['label' => 'Permission Level', 'value' => $permissionLevel],
    ];

    $contactDetails = [
        ['label' => 'Email Address', 'value' => $email],
        ['label' => 'Phone Number', 'value' => $phone],
        ['label' => 'Extension', 'value' => $extension],
    ];

    $notificationPreferences = [
        ['key' => 'entry_exit_alerts', 'label' => 'Entry / Exit Alerts', 'description' => 'Real-time alerts for assigned gates', 'enabled' => $settings->entry_exit_alerts ?? true],
        ['key' => 'violation_reports', 'label' => 'Violation Reports', 'description' => 'Escalations for high-priority incidents', 'enabled' => $settings->violation_reports ?? true],
        ['key' => 'system_maintenance', 'label' => 'System Maintenance', 'description' => 'Advance notice for scheduled downtime', 'enabled' => $settings->maintenance_notifications ?? false],
        ['key' => 'emergency_alerts', 'label' => 'Emergency Broadcasts', 'description' => 'Campus-wide emergency bulletins', 'enabled' => $settings->emergency_alerts ?? true],
    ];

    $timezoneDisplay = $settings->timezone ?? 'Asia/Manila';
    $timezoneOffset = 'UTC +08:00';
    if ($timezoneDisplay === 'Asia/Manila') {
        $timezoneOffset = 'UTC +08:00 (Manila)';
    }

    $workingPreferences = [
        ['key' => 'default_gate_view', 'label' => 'Default Gate View', 'value' => $settings->default_gate_view ?? 'Not Set'],
        ['key' => 'timezone', 'label' => 'Time Zone', 'value' => $timezoneOffset],
        ['key' => 'language', 'label' => 'Language', 'value' => $settings->language ?? 'English'],
        ['key' => 'dashboard_layout', 'label' => 'Dashboard Layout', 'value' => ucfirst($settings->dashboard_layout ?? 'default')],
    ];

    // Format schedule blocks from work_schedule and shifts
    $scheduleBlocks = [];
    if (!empty($workSchedule) && is_array($workSchedule)) {
        foreach ($workSchedule as $schedule) {
            if (is_array($schedule)) {
                $scheduleBlocks[] = [
                    'day' => $schedule['day'] ?? 'N/A',
                    'shift' => $schedule['shift'] ?? 'N/A',
                    'location' => $schedule['location'] ?? 'N/A',
                ];
            }
        }
    }
    if (empty($scheduleBlocks)) {
        $scheduleBlocks = [
            ['day' => 'Not Scheduled', 'shift' => 'N/A', 'location' => 'N/A'],
        ];
    }
@endphp

<div class="flex h-screen overflow-hidden bg-gray-50">
    <div id="overlay" class="overlay" onclick="toggleSidebar()"></div>

    @include('components.anpr.nav.sidebar')

    <div id="main-content" class="flex-1 overflow-auto">
        @include('components.anpr.nav.header', [
            'pageTitle' => 'Personnel Profile',
            'searchPlaceholder' => 'Search personnel records',
            'searchAriaLabel' => 'Search personnel',
            'userInitials' => $user->name_initial ?? 'U',
            'userName' => $fullName,
        ])

        <div class="p-4 md:p-8 space-y-6">
            <div class="glass-card p-6">
                <div class="flex flex-col lg:flex-row items-start lg:items-center gap-6">
                    <div class="flex items-center gap-4">
                        <div class="w-24 h-24 rounded-2xl overflow-hidden border-4 border-white shadow-lg flex-shrink-0">
                            <img src="{{ $photo }}" alt="{{ $fullName }}" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-[0.25em] text-gray-400">Security Personnel</p>
                            <h2 class="text-2xl font-bold text-gray-900">{{ $fullName }}</h2>
                            <p class="text-sm text-gray-500">{{ $role }} · {{ $department }}</p>
                            <div class="mt-3 flex flex-wrap gap-3">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">Badge {{ $badgeId }}</span>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700">{{ $permissionLevel }}</span>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">{{ $status }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-4 w-full">
                        <div class="p-4 border border-gray-100 rounded-xl bg-gray-50">
                            <p class="info-label text-gray-500">Assigned Gates</p>
                            <p class="info-value text-gray-900">{{ !empty($assignedGates) ? implode(', ', $assignedGates) : 'Not Assigned' }}</p>
                        </div>
                        <div class="p-4 border border-gray-100 rounded-xl bg-gray-50">
                            <p class="info-label text-gray-500">Work Schedule</p>
                            <p class="info-value text-gray-900">{{ !empty($scheduleBlocks) ? $scheduleBlocks[0]['day'] : 'Not Scheduled' }}</p>
                            <p class="text-xs text-gray-500">{{ !empty($scheduleBlocks) ? $scheduleBlocks[0]['shift'] : 'N/A' }}</p>
                        </div>
                        <div class="p-4 border border-gray-100 rounded-xl bg-gray-50">
                            <p class="info-label text-gray-500">Last Login</p>
                            <p class="info-value text-gray-900">{{ $lastLogin }}</p>
                            <span class="text-xs text-emerald-600">Secure session validated</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Status Section -->
            <div class="glass-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-xs uppercase tracking-[0.3em] text-gray-400">Account Information</p>
                        <h3 class="text-xl font-semibold text-gray-900">Account Status</h3>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="p-4 border border-gray-100 rounded-xl bg-gray-50">
                        <p class="info-label text-gray-500 mb-1">Account Status</p>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $accountStatus['status_class'] }}">{{ $accountStatus['status'] }}</span>
                    </div>
                    <div class="p-4 border border-gray-100 rounded-xl bg-gray-50">
                        <p class="info-label text-gray-500 mb-1">Account Created</p>
                        <p class="info-value text-gray-900">{{ $accountStatus['created_at']->format('M d, Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $accountStatus['created_at']->diffForHumans() }}</p>
                    </div>
                    <div class="p-4 border border-gray-100 rounded-xl bg-gray-50">
                        <p class="info-label text-gray-500 mb-1">Last Activity</p>
                        <p class="info-value text-gray-900">{{ $accountStatus['last_activity']->format('M d, Y H:i') }}</p>
                        <p class="text-xs text-gray-500">{{ $accountStatus['last_activity']->diffForHumans() }}</p>
                    </div>
                </div>
            </div>

            <!-- Tab Navigation -->
            <div class="glass-card">
                <div class="border-b border-gray-200">
                    <nav class="flex space-x-8 px-6" aria-label="Profile Tabs">
                        <button onclick="switchTab('personal')" id="tab-personal" class="tab-button active py-4 px-1 border-b-2 border-green-600 font-medium text-sm text-green-600">
                            <i class="fas fa-user mr-2"></i>Personal Information
                        </button>
                        <button onclick="switchTab('settings')" id="tab-settings" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            <i class="fas fa-cog mr-2"></i>Settings
                        </button>
                        <button onclick="switchTab('activity')" id="tab-activity" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            <i class="fas fa-history mr-2"></i>Activity History
                        </button>
                        <button onclick="switchTab('security')" id="tab-security" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            <i class="fas fa-shield-alt mr-2"></i>Security
                        </button>
                        <button onclick="switchTab('help')" id="tab-help" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            <i class="fas fa-question-circle mr-2"></i>Help & Support
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="p-6">
                    <!-- Personal Information Tab -->
                    <div id="content-personal" class="tab-content">
                        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <div class="xl:col-span-2 space-y-6">
                    <div class="glass-card p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-gray-400">Profile & Identity</p>
                                <h3 class="text-xl font-semibold text-gray-900">Personal Details</h3>
                            </div>
                            <button onclick="document.getElementById('editProfileModal').classList.remove('hidden')" class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                                <i class="fas fa-pen mr-2 text-xs"></i>Edit Profile
                            </button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            @foreach($personalDetails as $detail)
                                <div>
                                    <p class="info-label text-gray-500 mb-1">{{ $detail['label'] }}</p>
                                    <p class="info-value text-gray-900">{{ $detail['value'] }}</p>
                                </div>
                            @endforeach
                        </div>
                        <div class="section-divider my-6"></div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            @foreach($contactDetails as $detail)
                                <div>
                                    <p class="info-label text-gray-500 mb-1">{{ $detail['label'] }}</p>
                                    <p class="info-value text-gray-900">{{ $detail['value'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="glass-card p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-gray-400">Access Management</p>
                                <h3 class="text-xl font-semibold text-gray-900">Authorization Levels</h3>
                            </div>
                            <button class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-200 rounded-lg hover:bg-gray-50">
                                <i class="fas fa-shield-alt mr-2 text-xs text-emerald-600"></i>Update Permissions
                            </button>
                        </div>
                        <div class="overflow-hidden rounded-xl border border-gray-100">
                            <table class="min-w-full divide-y divide-gray-100 text-sm">
                                <thead class="bg-gray-50 text-gray-500 uppercase tracking-wide text-xs">
                                    <tr>
                                        <th class="px-4 py-3 text-left">Module</th>
                                        <th class="px-4 py-3 text-center">View</th>
                                        <th class="px-4 py-3 text-center">Edit</th>
                                        <th class="px-4 py-3 text-center">Delete</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach($authorizationLevels as $level)
                                        <tr>
                                            <td class="px-4 py-4">
                                                <p class="font-semibold text-gray-900">{{ $level['module'] }}</p>
                                                <p class="text-xs text-gray-500">Module ID: {{ \Illuminate\Support\Str::slug($level['module'], '-') }}</p>
                                            </td>
                                            @foreach(['view', 'edit', 'delete'] as $action)
                                                <td class="px-4 py-4 text-center">
                                                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-full {{ $level[$action] ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-gray-50 text-gray-400 border border-gray-100' }}">
                                                        <i class="fas {{ $level[$action] ? 'fa-check' : 'fa-minus' }}"></i>
                                                    </span>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="glass-card p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-gray-400">Duty Rotation</p>
                                <h3 class="text-xl font-semibold text-gray-900">Schedule & Gates</h3>
                            </div>
                            <span class="text-xs font-semibold px-3 py-1 rounded-full bg-emerald-50 text-emerald-700">Active Schedule</span>
                        </div>
                        <div class="space-y-4">
                            @foreach($scheduleBlocks as $block)
                                <div class="border border-gray-100 rounded-xl p-4 bg-gray-50">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">{{ $block['day'] }}</p>
                                            <p class="text-sm text-gray-500">{{ $block['shift'] }}</p>
                                        </div>
                                        <span class="text-xs font-semibold px-3 py-1 rounded-full bg-amber-50 text-amber-700">{{ $block['location'] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-6">
                            <p class="text-xs uppercase tracking-[0.3em] text-gray-400 mb-3">Assigned Checkpoints</p>
                            <div class="flex flex-wrap gap-2">
                                @if(!empty($assignedGates))
                                    @foreach($assignedGates as $gate)
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-white border border-gray-100 shadow-sm">{{ $gate }}</span>
                                    @endforeach
                                @else
                                    <span class="text-sm text-gray-500">No gates assigned</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('anpr.user-management.profile.settings.update') }}" method="POST" id="settingsForm">
                        @csrf
                        <div class="glass-card p-6">
                            <p class="text-xs uppercase tracking-[0.3em] text-gray-400">Settings</p>
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">Notification Preferences</h3>
                            <div class="space-y-3">
                                @foreach($notificationPreferences as $preference)
                                    <label class="w-full flex items-center justify-between px-4 py-3 border border-gray-100 rounded-xl bg-gray-50 hover:bg-white transition cursor-pointer">
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">{{ $preference['label'] }}</p>
                                            <p class="text-xs text-gray-500">{{ $preference['description'] }}</p>
                                        </div>
                                        <input type="checkbox" name="{{ $preference['key'] }}" value="1" {{ $preference['enabled'] ? 'checked' : '' }} class="hidden toggle-checkbox" data-key="{{ $preference['key'] }}">
                                        <span class="inline-flex items-center h-6 w-11 rounded-full transition {{ $preference['enabled'] ? 'bg-emerald-500' : 'bg-gray-300' }}" onclick="toggleNotification(this)">
                                            <span class="w-5 h-5 bg-white rounded-full shadow transform transition {{ $preference['enabled'] ? 'translate-x-5' : 'translate-x-0.5' }}"></span>
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                            <div class="mt-6 flex items-center justify-between text-xs text-gray-500">
                                <span>Last updated: {{ $settings->updated_at ? $settings->updated_at->format('M d, Y') : 'Never' }}</span>
                            </div>
                        </div>
                    </form>

                    <form action="{{ route('anpr.user-management.profile.settings.update') }}" method="POST" id="preferencesForm">
                        @csrf
                        <div class="glass-card p-6">
                            <p class="text-xs uppercase tracking-[0.3em] text-gray-400">Preferences</p>
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">Working Preferences</h3>
                            <div class="space-y-4">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-500 mb-1">Default Gate View</p>
                                        <input type="text" name="default_gate_view" value="{{ $settings->default_gate_view ?? '' }}" placeholder="Enter default gate view" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                    </div>
                                </div>
                                <div class="section-divider my-2"></div>
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-500 mb-1">Time Zone</p>
                                        <select name="timezone" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                            <option value="Asia/Manila" {{ ($settings->timezone ?? 'Asia/Manila') === 'Asia/Manila' ? 'selected' : '' }}>UTC +08:00 (Manila)</option>
                                            <option value="UTC" {{ ($settings->timezone ?? '') === 'UTC' ? 'selected' : '' }}>UTC +00:00</option>
                                            <option value="America/New_York" {{ ($settings->timezone ?? '') === 'America/New_York' ? 'selected' : '' }}>UTC -05:00 (EST)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="section-divider my-2"></div>
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-500 mb-1">Language</p>
                                        <select name="language" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                            <option value="English" {{ ($settings->language ?? 'English') === 'English' ? 'selected' : '' }}>English</option>
                                            <option value="Filipino" {{ ($settings->language ?? '') === 'Filipino' ? 'selected' : '' }}>Filipino</option>
                                            <option value="English / Filipino" {{ ($settings->language ?? '') === 'English / Filipino' ? 'selected' : '' }}>English / Filipino</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="section-divider my-2"></div>
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-500 mb-1">Dashboard Layout</p>
                                        <select name="dashboard_layout" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                            <option value="default" {{ ($settings->dashboard_layout ?? 'default') === 'default' ? 'selected' : '' }}>Default</option>
                                            <option value="incident-first" {{ ($settings->dashboard_layout ?? '') === 'incident-first' ? 'selected' : '' }}>Incident-First (Custom)</option>
                                            <option value="gate-focused" {{ ($settings->dashboard_layout ?? '') === 'gate-focused' ? 'selected' : '' }}>Gate-Focused</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-6 flex gap-3">
                                <button type="submit" class="flex-1 px-4 py-2 text-sm font-semibold text-white bg-green-600 rounded-lg hover:bg-green-700">Save Preferences</button>
                                <button type="button" onclick="resetPreferences()" class="px-4 py-2 text-sm font-semibold text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50">Reset</button>
                            </div>
                        </div>
                    </form>
                        </div>
                    </div>
                </div>
                    </div>

                    <!-- Settings Tab -->
                    <div id="content-settings" class="tab-content hidden">
                        <div class="space-y-6">
                            <form action="{{ route('anpr.user-management.profile.settings.update') }}" method="POST" id="settingsForm">
                                @csrf
                                <div class="glass-card p-6">
                                    <p class="text-xs uppercase tracking-[0.3em] text-gray-400">Settings</p>
                                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Notification Preferences</h3>
                                    <div class="space-y-3">
                                        @foreach($notificationPreferences as $preference)
                                            <label class="w-full flex items-center justify-between px-4 py-3 border border-gray-100 rounded-xl bg-gray-50 hover:bg-white transition cursor-pointer">
                                                <div>
                                                    <p class="text-sm font-semibold text-gray-900">{{ $preference['label'] }}</p>
                                                    <p class="text-xs text-gray-500">{{ $preference['description'] }}</p>
                                                </div>
                                                <input type="checkbox" name="{{ $preference['key'] }}" value="1" {{ $preference['enabled'] ? 'checked' : '' }} class="hidden toggle-checkbox" data-key="{{ $preference['key'] }}">
                                                <span class="inline-flex items-center h-6 w-11 rounded-full transition {{ $preference['enabled'] ? 'bg-emerald-500' : 'bg-gray-300' }}" onclick="toggleNotification(this)">
                                                    <span class="w-5 h-5 bg-white rounded-full shadow transform transition {{ $preference['enabled'] ? 'translate-x-5' : 'translate-x-0.5' }}"></span>
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                    <div class="mt-6 flex items-center justify-between text-xs text-gray-500">
                                        <span>Last updated: {{ $settings->updated_at ? $settings->updated_at->format('M d, Y') : 'Never' }}</span>
                                    </div>
                                </div>
                            </form>

                            <form action="{{ route('anpr.user-management.profile.settings.update') }}" method="POST" id="preferencesForm">
                                @csrf
                                <div class="glass-card p-6">
                                    <p class="text-xs uppercase tracking-[0.3em] text-gray-400">Preferences</p>
                                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Working Preferences</h3>
                                    <div class="space-y-4">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <p class="text-sm text-gray-500 mb-1">Default Gate View</p>
                                                <input type="text" name="default_gate_view" value="{{ $settings->default_gate_view ?? '' }}" placeholder="Enter default gate view" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                            </div>
                                        </div>
                                        <div class="section-divider my-2"></div>
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <p class="text-sm text-gray-500 mb-1">Time Zone</p>
                                                <select name="timezone" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                                    <option value="Asia/Manila" {{ ($settings->timezone ?? 'Asia/Manila') === 'Asia/Manila' ? 'selected' : '' }}>UTC +08:00 (Manila)</option>
                                                    <option value="UTC" {{ ($settings->timezone ?? '') === 'UTC' ? 'selected' : '' }}>UTC +00:00</option>
                                                    <option value="America/New_York" {{ ($settings->timezone ?? '') === 'America/New_York' ? 'selected' : '' }}>UTC -05:00 (EST)</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="section-divider my-2"></div>
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <p class="text-sm text-gray-500 mb-1">Language</p>
                                                <select name="language" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                                    <option value="English" {{ ($settings->language ?? 'English') === 'English' ? 'selected' : '' }}>English</option>
                                                    <option value="Filipino" {{ ($settings->language ?? '') === 'Filipino' ? 'selected' : '' }}>Filipino</option>
                                                    <option value="English / Filipino" {{ ($settings->language ?? '') === 'English / Filipino' ? 'selected' : '' }}>English / Filipino</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="section-divider my-2"></div>
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <p class="text-sm text-gray-500 mb-1">Dashboard Layout</p>
                                                <select name="dashboard_layout" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                                    <option value="default" {{ ($settings->dashboard_layout ?? 'default') === 'default' ? 'selected' : '' }}>Default</option>
                                                    <option value="incident-first" {{ ($settings->dashboard_layout ?? '') === 'incident-first' ? 'selected' : '' }}>Incident-First (Custom)</option>
                                                    <option value="gate-focused" {{ ($settings->dashboard_layout ?? '') === 'gate-focused' ? 'selected' : '' }}>Gate-Focused</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-6 flex gap-3">
                                        <button type="submit" class="flex-1 px-4 py-2 text-sm font-semibold text-white bg-green-600 rounded-lg hover:bg-green-700">Save Preferences</button>
                                        <button type="button" onclick="resetPreferences()" class="px-4 py-2 text-sm font-semibold text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50">Reset</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Activity History Tab -->
                    <div id="content-activity" class="tab-content hidden">
                        <div class="space-y-6">
                            <!-- Usage Statistics -->
                            <div class="glass-card p-6">
                                <p class="text-xs uppercase tracking-[0.3em] text-gray-400 mb-4">System Usage</p>
                                <h3 class="text-xl font-semibold text-gray-900 mb-4">Usage Statistics</h3>
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                    <div class="p-4 border border-gray-100 rounded-xl bg-gray-50">
                                        <p class="info-label text-gray-500 mb-1">Total Logins</p>
                                        <p class="info-value text-gray-900">{{ $usageStats['total_logins'] }}</p>
                                    </div>
                                    <div class="p-4 border border-gray-100 rounded-xl bg-gray-50">
                                        <p class="info-label text-gray-500 mb-1">Total Actions</p>
                                        <p class="info-value text-gray-900">{{ $usageStats['total_actions'] }}</p>
                                    </div>
                                    <div class="p-4 border border-gray-100 rounded-xl bg-gray-50">
                                        <p class="info-label text-gray-500 mb-1">Last 30 Days</p>
                                        <p class="info-value text-gray-900">{{ $usageStats['last_30_days_actions'] }}</p>
                                    </div>
                                    <div class="p-4 border border-gray-100 rounded-xl bg-gray-50">
                                        <p class="info-label text-gray-500 mb-1">Most Used Feature</p>
                                        <p class="info-value text-gray-900">{{ $usageStats['most_used_feature'] }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Recent Actions -->
                            <div class="glass-card p-6">
                                <p class="text-xs uppercase tracking-[0.3em] text-gray-400 mb-4">Activity Log</p>
                                <h3 class="text-xl font-semibold text-gray-900 mb-4">Recent Actions</h3>
                                <div class="space-y-3">
                                    @forelse($activityLogs->take(20) as $log)
                                        <div class="flex items-start gap-4 p-4 border border-gray-100 rounded-xl bg-gray-50">
                                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                                                <i class="fas fa-{{ $log->action_type === 'login' ? 'sign-in-alt' : ($log->action_type === 'logout' ? 'sign-out-alt' : 'edit') }} text-green-600"></i>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-semibold text-gray-900">{{ $log->action_title }}</p>
                                                <p class="text-xs text-gray-500">{{ $log->action_description }}</p>
                                                <p class="text-xs text-gray-400 mt-1">{{ $log->created_at->format('M d, Y H:i') }} · {{ $log->ip_address ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-8 text-gray-500">
                                            <i class="fas fa-history text-4xl mb-2"></i>
                                            <p>No activity logs found</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Login History -->
                            <div class="glass-card p-6">
                                <p class="text-xs uppercase tracking-[0.3em] text-gray-400 mb-4">Authentication</p>
                                <h3 class="text-xl font-semibold text-gray-900 mb-4">Login History</h3>
                                <div class="space-y-3">
                                    @forelse($loginHistory as $login)
                                        <div class="flex items-center justify-between p-4 border border-gray-100 rounded-xl bg-gray-50">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center">
                                                    <i class="fas fa-sign-in-alt text-emerald-600"></i>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-semibold text-gray-900">Successful Login</p>
                                                    <p class="text-xs text-gray-500">{{ $login->ip_address ?? 'N/A' }} · {{ $login->user_agent ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                            <p class="text-xs text-gray-500">{{ $login->created_at->format('M d, Y H:i') }}</p>
                                        </div>
                                    @empty
                                        <div class="text-center py-8 text-gray-500">
                                            <p>No login history available</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Profile Update History -->
                            <div class="glass-card p-6">
                                <p class="text-xs uppercase tracking-[0.3em] text-gray-400 mb-4">Profile Changes</p>
                                <h3 class="text-xl font-semibold text-gray-900 mb-4">Profile Update History</h3>
                                <div class="space-y-3">
                                    @forelse($profileUpdates as $update)
                                        <div class="flex items-center justify-between p-4 border border-gray-100 rounded-xl bg-gray-50">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                    <i class="fas fa-user-edit text-blue-600"></i>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-semibold text-gray-900">{{ $update->action_title }}</p>
                                                    <p class="text-xs text-gray-500">{{ $update->action_description }}</p>
                                                </div>
                                            </div>
                                            <p class="text-xs text-gray-500">{{ $update->created_at->format('M d, Y H:i') }}</p>
                                        </div>
                                    @empty
                                        <div class="text-center py-8 text-gray-500">
                                            <p>No profile updates recorded</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Security Settings Tab -->
                    <div id="content-security" class="tab-content hidden">
                        <div class="space-y-6">
                            <div class="glass-card p-6">
                                <p class="text-xs uppercase tracking-[0.3em] text-gray-400 mb-4">Password Management</p>
                                <h3 class="text-xl font-semibold text-gray-900 mb-4">Change Password</h3>
                                <form action="{{ route('anpr.user-management.profile.password.update') }}" method="POST" class="space-y-4">
                                    @csrf
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                                        <input type="password" name="current_password" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                                        <input type="password" name="password" required minlength="8" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                                        <p class="text-xs text-gray-500 mt-1">Must be at least 8 characters long</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                                        <input type="password" name="password_confirmation" required minlength="8" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                                    </div>
                                    <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-green-600 rounded-lg hover:bg-green-700">Update Password</button>
                                </form>
                            </div>

                            <div class="glass-card p-6">
                                <p class="text-xs uppercase tracking-[0.3em] text-gray-400 mb-4">Session Management</p>
                                <h3 class="text-xl font-semibold text-gray-900 mb-4">Active Sessions</h3>
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between p-4 border border-gray-100 rounded-xl bg-gray-50">
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">Current Session</p>
                                            <p class="text-xs text-gray-500">{{ request()->ip() }} · {{ request()->userAgent() }}</p>
                                        </div>
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">Active</span>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <button class="px-4 py-2 text-sm font-semibold text-red-600 border border-red-200 rounded-lg hover:bg-red-50">Logout from All Devices</button>
                                </div>
                            </div>

                            <div class="glass-card p-6">
                                <p class="text-xs uppercase tracking-[0.3em] text-gray-400 mb-4">Two-Factor Authentication</p>
                                <h3 class="text-xl font-semibold text-gray-900 mb-4">2FA Settings</h3>
                                <div class="flex items-center justify-between p-4 border border-gray-100 rounded-xl bg-gray-50">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">Two-Factor Authentication</p>
                                        <p class="text-xs text-gray-500">Add an extra layer of security to your account</p>
                                    </div>
                                    <button class="px-4 py-2 text-sm font-semibold text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50">Enable 2FA</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Help & Support Tab -->
                    <div id="content-help" class="tab-content hidden">
                        <div class="space-y-6">
                            <div class="glass-card p-6">
                                <p class="text-xs uppercase tracking-[0.3em] text-gray-400 mb-4">Documentation</p>
                                <h3 class="text-xl font-semibold text-gray-900 mb-4">Help Documentation</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <a href="#" class="p-4 border border-gray-100 rounded-xl bg-gray-50 hover:bg-white transition">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                <i class="fas fa-book text-blue-600"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">User Guide</p>
                                                <p class="text-xs text-gray-500">Complete guide to using the ANPR system</p>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="p-4 border border-gray-100 rounded-xl bg-gray-50 hover:bg-white transition">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                                <i class="fas fa-video text-green-600"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">Video Tutorials</p>
                                                <p class="text-xs text-gray-500">Step-by-step video instructions</p>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="p-4 border border-gray-100 rounded-xl bg-gray-50 hover:bg-white transition">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                                                <i class="fas fa-question-circle text-purple-600"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">FAQ</p>
                                                <p class="text-xs text-gray-500">Frequently asked questions</p>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="p-4 border border-gray-100 rounded-xl bg-gray-50 hover:bg-white transition">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center">
                                                <i class="fas fa-graduation-cap text-amber-600"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">Training Resources</p>
                                                <p class="text-xs text-gray-500">Training materials and courses</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>

                            <div class="glass-card p-6">
                                <p class="text-xs uppercase tracking-[0.3em] text-gray-400 mb-4">Support</p>
                                <h3 class="text-xl font-semibold text-gray-900 mb-4">Get Help</h3>
                                <div class="space-y-4">
                                    <a href="#" class="flex items-center justify-between p-4 border border-gray-100 rounded-xl bg-gray-50 hover:bg-white transition">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                                <i class="fas fa-ticket-alt text-indigo-600"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">Create Support Ticket</p>
                                                <p class="text-xs text-gray-500">Submit a support request</p>
                                            </div>
                                        </div>
                                        <i class="fas fa-chevron-right text-gray-400"></i>
                                    </a>
                                    <a href="#" class="flex items-center justify-between p-4 border border-gray-100 rounded-xl bg-gray-50 hover:bg-white transition">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                                                <i class="fas fa-headset text-red-600"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">Live Chat Support</p>
                                                <p class="text-xs text-gray-500">Chat with our support team</p>
                                            </div>
                                        </div>
                                        <i class="fas fa-chevron-right text-gray-400"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="glass-card p-6">
                                <p class="text-xs uppercase tracking-[0.3em] text-gray-400 mb-4">Contact Information</p>
                                <h3 class="text-xl font-semibold text-gray-900 mb-4">Technical Support</h3>
                                <div class="space-y-3">
                                    <div class="flex items-center gap-3 p-4 border border-gray-100 rounded-xl bg-gray-50">
                                        <i class="fas fa-envelope text-gray-400"></i>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">Email</p>
                                            <p class="text-xs text-gray-500">support@clsu.edu.ph</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 p-4 border border-gray-100 rounded-xl bg-gray-50">
                                        <i class="fas fa-phone text-gray-400"></i>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">Phone</p>
                                            <p class="text-xs text-gray-500">+63 (44) 456-0707</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 p-4 border border-gray-100 rounded-xl bg-gray-50">
                                        <i class="fas fa-clock text-gray-400"></i>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">Support Hours</p>
                                            <p class="text-xs text-gray-500">Monday - Friday, 8:00 AM - 5:00 PM</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div id="editProfileModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
            <h3 class="text-xl font-semibold text-gray-900">Edit Profile</h3>
            <button onclick="document.getElementById('editProfileModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form action="{{ route('anpr.user-management.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                    <input type="text" name="first_name" value="{{ $user->first_name }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                    <input type="text" name="middle_name" value="{{ $user->middle_name ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                    <input type="text" name="last_name" value="{{ $user->last_name }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ $user->email }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Employee ID</label>
                    <input type="text" name="employee_id" value="{{ $details->employee_id ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                    <input type="text" name="department" value="{{ $details->department ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Designation / Role</label>
                    <input type="text" name="designation" value="{{ $details->designation ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Badge ID</label>
                    <input type="text" name="badge_id" value="{{ $details->badge_id ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                    <input type="text" name="phone_number" value="{{ $details->phone_number ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Extension</label>
                    <input type="text" name="extension" value="{{ $details->extension ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Authorization Level</label>
                    <input type="text" name="authorization_level" value="{{ $details->authorization_level ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Profile Photo</label>
                    <input type="file" name="profile_photo" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
            </div>
            <div class="flex gap-3 pt-4">
                <button type="submit" class="flex-1 px-4 py-2 text-sm font-semibold text-white bg-green-600 rounded-lg hover:bg-green-700">Save Changes</button>
                <button type="button" onclick="document.getElementById('editProfileModal').classList.add('hidden')" class="px-4 py-2 text-sm font-semibold text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
<script src="{{ asset('js/dashboard.js') }}"></script>
<script>
    function toggleNotification(element) {
        const checkbox = element.previousElementSibling;
        checkbox.checked = !checkbox.checked;

        // Update visual state
        if (checkbox.checked) {
            element.classList.remove('bg-gray-300');
            element.classList.add('bg-emerald-500');
            element.querySelector('span').classList.remove('translate-x-0.5');
            element.querySelector('span').classList.add('translate-x-5');
        } else {
            element.classList.remove('bg-emerald-500');
            element.classList.add('bg-gray-300');
            element.querySelector('span').classList.remove('translate-x-5');
            element.querySelector('span').classList.add('translate-x-0.5');
        }

        // Auto-submit settings form
        document.getElementById('settingsForm').submit();
    }

    function resetPreferences() {
        if (confirm('Are you sure you want to reset all preferences to default values?')) {
            document.getElementById('preferencesForm').reset();
            document.getElementById('preferencesForm').submit();
        }
    }

    // Show success/error messages
    @if(session('success'))
        alert('{{ session('success') }}');
    @endif

    @if(session('error'))
        alert('{{ session('error') }}');
    @endif

    // Tab switching functionality
    function switchTab(tabName) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });

        // Remove active class from all tabs
        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.remove('active', 'border-green-600', 'text-green-600');
            button.classList.add('border-transparent', 'text-gray-500');
        });

        // Show selected tab content
        const selectedContent = document.getElementById('content-' + tabName);
        if (selectedContent) {
            selectedContent.classList.remove('hidden');
        }

        // Add active class to selected tab
        const selectedTab = document.getElementById('tab-' + tabName);
        if (selectedTab) {
            selectedTab.classList.add('active', 'border-green-600', 'text-green-600');
            selectedTab.classList.remove('border-transparent', 'text-gray-500');
        }
    }
</script>
@endsection

