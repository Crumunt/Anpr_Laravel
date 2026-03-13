<div class="flex-1 md:ml-64 p-6 pt-24">
    <div class="max-w-6xl mx-auto">
        <!-- Page Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">System Settings</h1>
                    <p class="text-gray-600 mt-1">Manage system configuration, users, and maintenance</p>
                </div>
                @if(auth()->user()->hasRole('super_admin'))
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    Super Admin
                </span>
                @endif
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Sidebar Navigation -->
            <div class="lg:w-56 flex-shrink-0">
                <nav class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden sticky top-24">
                    <ul class="p-2">
                        <li>
                            <button wire:click="setTab('general')"
                                class="w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ $activeTab === 'general' ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                General
                            </button>
                        </li>
                        <li>
                            <button wire:click="setTab('users')"
                                class="w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ $activeTab === 'users' ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                Users & Roles
                            </button>
                        </li>
                        @role('super_admin')
                        <li>
                            <button wire:click="setTab('health')"
                                class="w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ $activeTab === 'health' ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                System Health
                            </button>
                        </li>
                        <li>
                            <button wire:click="setTab('maintenance')"
                                class="w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ $activeTab === 'maintenance' ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Maintenance
                            </button>
                        </li>
                        @endrole
                        <li class="border-t border-gray-200 mt-2 pt-2">
                            <a href="{{ route('admin.settings.applicant-types') }}"
                                class="w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors text-gray-700 hover:bg-gray-50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Applicant Types
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="flex-1 space-y-6">
                <!-- General Tab -->
                @if($activeTab === 'general')
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Application Information</h3>
                        <p class="text-sm text-gray-500 mt-1">Basic information about this application</p>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Application Name</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $appName }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Timezone</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $appTimezone }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Laravel Version</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ app()->version() }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">PHP Version</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ phpversion() }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Environment</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ app()->environment('production') ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst(app()->environment()) }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Debug Mode</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ config('app.debug') ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                        {{ config('app.debug') ? 'Enabled' : 'Disabled' }}
                                    </span>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Email Configuration -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Email Configuration</h3>
                        <p class="text-sm text-gray-500 mt-1">Current email sending configuration</p>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Mail Driver</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $mailDriver }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Mail Host</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $mailHost ?: 'Not configured' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">From Address</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $mailFromAddress ?: 'Not configured' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">From Name</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $mailFromName ?: 'Not configured' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
                @endif

                <!-- Users & Roles Tab -->
                @if($activeTab === 'users')
                <!-- User Statistics -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                        <div class="text-2xl font-bold text-gray-900">{{ $stats['total_users'] ?? 0 }}</div>
                        <div class="text-sm text-gray-500">Total Users</div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                        <div class="text-2xl font-bold text-green-600">{{ $stats['active_users'] ?? 0 }}</div>
                        <div class="text-sm text-gray-500">Active Users</div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                        <div class="text-2xl font-bold text-blue-600">{{ $stats['total_admins'] ?? 0 }}</div>
                        <div class="text-sm text-gray-500">Administrators</div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                        <div class="text-2xl font-bold text-amber-600">{{ $stats['pending_users'] ?? 0 }}</div>
                        <div class="text-sm text-gray-500">Pending Setup</div>
                    </div>
                </div>

                <!-- Roles Overview -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Roles Overview</h3>
                        <p class="text-sm text-gray-500 mt-1">User distribution across roles</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @foreach($roles as $role)
                        <div class="px-6 py-4 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center">
                                    <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $role['display_name'] }}</div>
                                    <div class="text-xs text-gray-500">{{ $role['permissions_count'] }} permissions</div>
                                </div>
                            </div>
                            <div class="text-sm font-semibold text-gray-700">
                                {{ $role['users_count'] }} {{ $role['users_count'] === 1 ? 'user' : 'users' }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Pending Invitations -->
                @if(count($pendingUsers) > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Pending Invitations</h3>
                        <p class="text-sm text-gray-500 mt-1">Users who haven't set up their password yet</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @foreach($pendingUsers as $pendingUser)
                        <div class="px-6 py-4 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-full bg-amber-100 flex items-center justify-center text-amber-700 text-xs font-semibold">
                                    {{ strtoupper(substr($pendingUser['name'], 0, 2)) }}
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $pendingUser['name'] }}</div>
                                    <div class="text-xs text-gray-500">{{ $pendingUser['email'] }} · {{ $pendingUser['created_at'] }}</div>
                                </div>
                            </div>
                            <button
                                wire:click="resendInvitation('{{ $pendingUser['id'] }}')"
                                wire:loading.attr="disabled"
                                class="text-sm font-medium text-green-600 hover:text-green-700">
                                Resend
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                @endif

                <!-- System Health Tab -->
                @role('super_admin')
                @if($activeTab === 'health')
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">System Health</h3>
                            <p class="text-sm text-gray-500 mt-1">Status of critical system components</p>
                        </div>
                        <button wire:click="$refresh" class="text-sm text-green-600 hover:text-green-700 font-medium">
                            Refresh
                        </button>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @foreach($systemHealth as $component => $health)
                        <div class="px-6 py-4 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                @if($health['status'] === 'ok')
                                <div class="h-10 w-10 rounded-lg bg-green-100 flex items-center justify-center">
                                    <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                @elseif($health['status'] === 'warning')
                                <div class="h-10 w-10 rounded-lg bg-amber-100 flex items-center justify-center">
                                    <svg class="h-5 w-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                @else
                                <div class="h-10 w-10 rounded-lg bg-red-100 flex items-center justify-center">
                                    <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </div>
                                @endif
                                <div>
                                    <div class="text-sm font-medium text-gray-900 capitalize">{{ $component }}</div>
                                    <div class="text-xs text-gray-500">{{ $health['message'] }}</div>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $health['status'] === 'ok' ? 'bg-green-100 text-green-800' : ($health['status'] === 'warning' ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($health['status']) }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>

                @endif
                @endrole

                <!-- Maintenance Tab -->
                @role('super_admin')
                @if($activeTab === 'maintenance')
                <!-- Maintenance Mode -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Maintenance Mode</h3>
                        <p class="text-sm text-gray-500 mt-1">Take the application offline for maintenance</p>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    @if($maintenanceMode)
                                    <span class="inline-flex items-center gap-1.5 text-amber-600 font-medium">
                                        <span class="relative flex h-2 w-2">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                                        </span>
                                        Application is in maintenance mode
                                    </span>
                                    @else
                                    <span class="inline-flex items-center gap-1.5 text-green-600 font-medium">
                                        <span class="relative flex h-2 w-2">
                                            <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                                        </span>
                                        Application is live
                                    </span>
                                    @endif
                                </p>
                            </div>
                            <!-- <button
                                wire:click="toggleMaintenanceMode"
                                wire:loading.attr="disabled"
                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg transition-colors disabled:opacity-50
                                    {{ $maintenanceMode ? 'text-green-700 bg-green-100 hover:bg-green-200' : 'text-amber-700 bg-amber-100 hover:bg-amber-200' }}">
                                {{ $maintenanceMode ? 'Go Live' : 'Enable Maintenance' }}
                            </button> -->

                            {{-- Trigger Button --}}
                            <button
                                wire:click="$set('showMaintenanceModal', true)"
                                wire:loading.attr="disabled"
                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg transition-colors disabled:opacity-50
        {{ $maintenanceMode ? 'text-green-700 bg-green-100 hover:bg-green-200' : 'text-amber-700 bg-amber-100 hover:bg-amber-200' }}">
                                {{ $maintenanceMode ? 'Go Live' : 'Enable Maintenance' }}
                            </button>

                            {{-- Maintenance Mode Confirmation Modal --}}
                            @if($showMaintenanceModal)
                            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/30"
                                wire:click.self="$set('showMaintenanceModal', false)">
                                <div class="bg-white rounded-xl shadow-lg w-full max-w-md overflow-hidden border border-gray-200">

                                    {{-- Header --}}
                                    <div class="flex items-center gap-3 px-6 py-5 border-b"
                                        style="background-color: #EAF3DE; border-color: #C8E0A8;">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center"
                                            style="background-color: #F9A825;">
                                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                                                stroke="#1B5E20" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                                                <line x1="12" y1="9" x2="12" y2="13" />
                                                <line x1="12" y1="17" x2="12.01" y2="17" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-base leading-tight" style="color: #1B5E20;">Enable maintenance mode</p>
                                            <p class="text-xs mt-0.5" style="color: #4A7C2A;">Central Luzon State University</p>
                                        </div>
                                    </div>

                                    {{-- Body --}}
                                    <div class="px-6 py-5 bg-white">
                                        <p class="text-sm text-gray-700 mb-3 leading-relaxed">
                                            You are about to put the application into
                                            <span class="font-medium" style="color: #1B5E20;">maintenance mode</span>. During this time:
                                        </p>

                                        <ul class="space-y-2 mb-4">
                                            @foreach([
                                            'All users will be redirected to the maintenance page',
                                            'Active sessions will be terminated',
                                            'Administrators can still access the system',
                                            ] as $item)
                                            <li class="flex items-start gap-2.5 text-sm text-gray-500 leading-relaxed">
                                                <span class="mt-1.5 w-1.5 h-1.5 rounded-full flex-shrink-0"
                                                    style="background-color: #F9A825;"></span>
                                                {{ $item }}
                                            </li>
                                            @endforeach
                                        </ul>

                                        <div class="rounded-r-md px-3 py-2.5 mb-5 text-xs leading-relaxed"
                                            style="background-color: #FFFDE7; border-left: 3px solid #F9A825; color: #795548;">
                                            Are you sure you want to proceed? This action will affect all active users immediately.
                                        </div>

                                        {{-- Actions --}}
                                        <div class="flex justify-end gap-2.5">
                                            <button
                                                wire:click="$set('showMaintenanceModal', false)"
                                                class="px-4 py-2 text-sm font-medium rounded-lg border border-gray-200 bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors">
                                                Cancel
                                            </button>
                                            <button
                                                wire:click="toggleMaintenanceMode"
                                                wire:loading.attr="disabled"
                                                class="px-4 py-2 text-sm font-medium rounded-lg text-white transition-colors hover:opacity-90 disabled:opacity-60"
                                                style="background-color: #1B5E20;">
                                                <span wire:loading.remove wire:target="toggleMaintenanceMode">Enable maintenance</span>
                                                <span wire:loading wire:target="toggleMaintenanceMode">Processing...</span>
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Cache Management -->
                <!-- <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Cache Management</h3>
                        <p class="text-sm text-gray-500 mt-1">Clear application caches to refresh data</p>
                    </div>
                    <div class="p-6 flex flex-wrap gap-3">
                        <button
                            wire:click="clearCache"
                            wire:loading.attr="disabled"
                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors disabled:opacity-50">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Clear All Cache
                        </button>
                        <button
                            wire:click="optimizeApplication"
                            wire:loading.attr="disabled"
                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-green-700 bg-green-100 rounded-lg hover:bg-green-200 transition-colors disabled:opacity-50">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            Optimize for Production
                        </button>
                    </div>
                </div> -->

                <!-- Danger Zone -->
                @if(auth()->user()->hasRole('super_admin'))
                <div class="bg-white rounded-xl shadow-sm border border-red-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-red-200 bg-red-50">
                        <h3 class="text-lg font-semibold text-red-900">Danger Zone</h3>
                        <p class="text-sm text-red-600 mt-1">Irreversible and destructive actions</p>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-gray-600 mb-4">
                            These actions are potentially destructive and cannot be undone. Proceed with caution.
                        </p>
                        <div class="flex flex-wrap gap-3">
                            <button
                                onclick="Swal.fire({
                                    title: 'Clear All Data?',
                                    text: 'This will reset the database. This action cannot be undone!',
                                    icon: 'error',
                                    showCancelButton: true,
                                    confirmButtonColor: '#dc2626',
                                    cancelButtonColor: '#6b7280',
                                    confirmButtonText: 'I understand, proceed'
                                }).then((result) => { if (result.isConfirmed) { alert('Feature disabled for safety'); } })"
                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-red-700 bg-red-100 rounded-lg hover:bg-red-200 transition-colors">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                Reset Database (Disabled)
                            </button>
                        </div>
                    </div>
                </div>
                @endif
                @endif
                @endrole
            </div>
        </div>

        <!-- Loading Overlay -->
        <div wire:loading.flex wire:target="clearCache,toggleMaintenanceMode,retryFailedJobs,flushFailedJobs,optimizeApplication,resendInvitation"
            class="fixed inset-0 bg-black bg-opacity-25 items-center justify-center z-50" style="display: none;">
            <div class="bg-white rounded-lg p-4 flex items-center gap-3 shadow-xl">
                <svg class="animate-spin h-5 w-5 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-sm font-medium text-gray-700">Processing...</span>
            </div>
        </div>
    </div>
</div>
