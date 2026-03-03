<div class="mb-5 bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="p-6">
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
            <!-- Profile Section -->
            <div class="flex items-start gap-4 min-w-0 flex-1">
                <!-- Avatar -->
                <div class="relative flex-shrink-0">
                    @if($avatar)
                    <div class="h-20 w-20 rounded-full overflow-hidden ring-2 ring-gray-100">
                        <img src="{{ $avatar }}" alt="{{ $title }}" class="h-full w-full object-cover">
                    </div>
                    @else
                    <div class="h-20 w-20 rounded-full bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center text-green-700 text-xl font-semibold ring-2 ring-gray-100">
                        <span>{{ $initials }}</span>
                    </div>
                    @endif

                    <!-- Status Indicator (green pulse for active, gray for inactive) -->
                    <div class="absolute -bottom-0.5 -right-0.5">
                        @if($isActive)
                        <span class="relative flex h-4 w-4">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-4 w-4 bg-green-500 ring-2 ring-white"></span>
                        </span>
                        @else
                        <span class="relative flex h-4 w-4">
                            <span class="relative inline-flex rounded-full h-4 w-4 bg-gray-400 ring-2 ring-white"></span>
                        </span>
                        @endif
                    </div>
                </div>

                <!-- Profile Information -->
                <div class="min-w-0 flex-1">
                    <!-- Name & Verification -->
                    <div class="flex items-center gap-2 mb-1">
                        <h1 class="text-2xl font-semibold text-gray-900 truncate">{{ $title }}</h1>
                        @if($verified)
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        @endif
                    </div>

                    <!-- Subtitle -->
                    @if($subtitle)
                    <p class="text-sm text-gray-600 mb-3">{{ $subtitle }}</p>
                    @endif

                    <!-- Metadata Row -->
                    <div class="flex flex-wrap items-center gap-2">
                        <!-- Status Badge - Reactive -->
                        <span class="inline-flex items-center gap-1.5 text-xs px-2.5 py-1 rounded-md font-medium border {{ $isActive ? 'bg-green-100 text-green-800 border-green-200' : 'bg-red-100 text-red-800 border-red-200' }}">
                            @if($isActive)
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                            </svg>
                            @endif
                            {{ $status }}
                        </span>

                        <!-- ID Badge -->
                        <span class="inline-flex items-center gap-1.5 bg-gray-50 text-gray-700 text-xs px-2.5 py-1 rounded-md font-medium border border-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                            </svg>
                            {{ $user_id }}
                        </span>

                        <!-- Last Active -->
                        @if($isActive && $lastActive)
                        <span class="inline-flex items-center gap-1.5 text-xs text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Active {{ $lastActive }}
                        </span>
                        @endif

                        <!-- Tags -->
                        @foreach($tags as $tag)
                        <span class="inline-flex items-center bg-blue-50 text-blue-700 text-xs px-2.5 py-1 rounded-md font-medium border border-blue-100">
                            {{ $tag }}
                        </span>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Action Buttons - Only show if user has any edit permissions -->
            @if($canEdit || $canApprove || $canDelete)
            <div class="flex flex-wrap sm:flex-nowrap gap-2 lg:flex-shrink-0 relative z-50">
                <!-- Send Email Button - Only if can edit -->
                @if($canEdit)
                @livewire('admin.applicant.details.email', ['applicantEmail' => $email ?? '', 'applicantName' => $title])
                @endif

                <!-- Account Actions Dropdown - Only if can edit or delete -->
                @if($canEdit || $canDelete)
                <div class="relative" x-data="{
                    open: false,
                    confirmDeactivate() {
                        this.open = false;
                        Swal.fire({
                            title: 'Deactivate Account?',
                            text: 'The account will be temporarily disabled and can be reactivated later.',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#f59e0b',
                            cancelButtonColor: '#6b7280',
                            confirmButtonText: 'Yes, deactivate',
                            cancelButtonText: 'Cancel'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $wire.dispatchAccountAction('deactivate');
                            }
                        });
                    },
                    confirmActivate() {
                        this.open = false;
                        Swal.fire({
                            title: 'Reactivate Account?',
                            text: 'The account will be enabled and the user can log in again.',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#10b981',
                            cancelButtonColor: '#6b7280',
                            confirmButtonText: 'Yes, reactivate',
                            cancelButtonText: 'Cancel'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $wire.dispatchAccountAction('activate');
                            }
                        });
                    },
                    confirmArchive() {
                        this.open = false;
                        Swal.fire({
                            title: 'Archive Account?',
                            text: 'This account will be moved to the archive. You can restore it or permanently delete it later.',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#f59e0b',
                            cancelButtonColor: '#6b7280',
                            confirmButtonText: 'Yes, archive',
                            cancelButtonText: 'Cancel'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $wire.dispatchAccountAction('archive');
                            }
                        });
                    },
                    confirmDelete() {
                        this.open = false;
                        Swal.fire({
                            title: 'Delete Account?',
                            html: `<p class='text-gray-600 mb-3'>This action cannot be undone. All data will be permanently deleted.</p><p class='text-sm text-red-600 font-medium'>Type <strong>DELETE</strong> to confirm:</p>`,
                            input: 'text',
                            inputPlaceholder: 'Type DELETE',
                            icon: 'error',
                            showCancelButton: true,
                            confirmButtonColor: '#dc2626',
                            cancelButtonColor: '#6b7280',
                            confirmButtonText: 'Delete permanently',
                            cancelButtonText: 'Cancel',
                            preConfirm: (value) => {
                                if (value !== 'DELETE') {
                                    Swal.showValidationMessage('Please type DELETE to confirm');
                                    return false;
                                }
                                return true;
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $wire.dispatchAccountAction('delete');
                            }
                        });
                    }
                }">
                    <button
                        @click="open = !open"
                        @keydown.escape.window="open = false"
                        class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white border border-red-300 rounded-lg text-sm font-medium text-red-700 hover:bg-red-50 hover:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-150 whitespace-nowrap">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        Account Actions
                    </button>

                    <!-- Dropdown Menu -->
                    <div
                        x-show="open"
                        @click.away="open = false"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="fixed sm:absolute right-4 sm:right-0 mt-2 w-56 bg-white rounded-lg shadow-xl border border-gray-200 divide-y divide-gray-100"
                        style="display: none; z-index: 99999;"
                        x-cloak>

                        @if($canEdit)
                        <div class="py-1">
                            <!-- Deactivate/Reactivate Account - Conditional based on status -->
                            @if($isActive)
                            <button
                                @click="confirmDeactivate()"
                                class="flex w-full items-center gap-3 px-4 py-2.5 text-sm text-amber-700 hover:bg-amber-50 transition-colors duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                </svg>
                                Deactivate Account
                            </button>
                            @else
                            <button
                                @click="confirmActivate()"
                                class="flex w-full items-center gap-3 px-4 py-2.5 text-sm text-green-700 hover:bg-green-50 transition-colors duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Reactivate Account
                            </button>
                            @endif
                        </div>
                        @endif

                        @if($canDelete)
                        <div class="py-1">
                            <!-- Archive Account -->
                            <button
                                @click="confirmArchive()"
                                class="flex w-full items-center gap-3 px-4 py-2.5 text-sm text-amber-700 hover:bg-amber-50 transition-colors duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                </svg>
                                Archive Account
                            </button>
                            <!-- Delete Account -->
                            <button
                                @click="confirmDelete()"
                                class="flex w-full items-center gap-3 px-4 py-2.5 text-sm text-red-700 hover:bg-red-50 transition-colors duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Delete Permanently
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>
