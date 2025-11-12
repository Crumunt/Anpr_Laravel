@props([
    'userType' => 'applicant',           // admin, applicant, user
    'users' => collect(),           // Collection of users
    'pagination' => null,           // Pagination object
    'stats' => [],                  // Statistics array
    'routeName' => 'admin.applicant',              // Route name for AJAX calls
    'showAdminCard' => false,       // Whether to show admin card
    'pageTitle' => 'User Management', // Page title
    'tableCaption' => 'All Users',  // Table caption
])
@extends('layouts.app-layout')
@section('main-content')

    <div class="flex-1 md:ml-64 p-6 pt-24">
        <!-- Dashboard Cards -->
        <div
            class="grid grid-cols-1 sm:grid-cols-2 {{ $showAdminCard ? 'lg:grid-cols-4' : 'lg:grid-cols-3' }} gap-4 mb-6">
            <x-dashboard.card title="Total {{$userType}}" totalNumber="{{ $dashboardData['total']['count'] ?? 0 }}"
                percent="{{ $dashboardData['total']['percentage'] ?? 0 }}" description="Total system users" icon="users" />
            <x-dashboard.card title="Active {{$userType}}" totalNumber="{{ $dashboardData['active']['count'] ?? 0 }}"
                percent="{{ $dashboardData['active']['percentage'] ?? 0 }}" description="Currently active users" icon="check" />
            <x-dashboard.card title="Inactive {{$userType}}" totalNumber="{{ $dashboardData['inactive']['count'] ?? 0 }}"
                percent="{{ $dashboardData['inactive']['percentage'] ?? 0 }}" description="Currently inactive users" icon="ban" />
            @if($showAdminCard)
                <x-dashboard.card 
                    title="Super {{ ucfirst($userType) }}s" 
                    color="blue" 
                    totalNumber="{{ $stats['super'] ?? 0 }}"
                    percent="{{ $stats['super_change'] ?? '0' }}" 
                    description="Super administrators" 
                    icon="shield" />
            @endif
        </div>

        <!-- Users Management Container -->
        <div
            class="w-full bg-white rounded-xl shadow-sm border border-gray-100 transition-all duration-300 hover:shadow-md">

            <div class="p-6">
                <div class="w-full space-y-6">
                    <!-- Header -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <h2 class="text-2xl font-bold text-gray-800">
                            {{ $pageTitle }}
                        </h2>
                        <div class="flex space-x-2">
                            <x-dashboard.buttons :icon="false" class="bg-emerald-600 hover:bg-emerald-700"
                                data-open-modal="{{$userType}}-modal">
                                <span slot="icon" class="mr-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <path d="M8 12h8"></path>
                                        <path d="M12 8v8"></path>
                                    </svg>
                                </span>
                                <span>Add {{$userType}}</span>
                            </x-dashboard.buttons>
                        </div>
                    </div>

                    @if ($userType === 'applicant')
                        <x-modal.modal id="applicant-modal" type="applicant" action="add" maxWidth="3xl" />
                    @else
                        <x-modal.modal id="admin-modal" type="admin" action="add" />
                    @endif

                    <!-- Search Filter -->
                    <x-dashboard.search-filter userType="{{$userType}}" />

                    <!-- Table container -->
                    <div class="w-full space-y-6" id="table_wrapper">
                        <!-- All Users Tab Content -->
                        <x-table.data :type="$userType" context="user_{{ $userType }}" :rows="$userDetails"
                            caption="{{ $tableCaption }}" />
                    </div>

                    <!-- Pagination -->
                    <div id="pagination">
                        <x-pagination :pagination="$pagination" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-footer.footer></x-footer.footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            // Set search filter type
            if (document.querySelector('searchFilter')) {
                document.querySelector('searchFilter').setAttribute('userType', 'users');
            }

            // Pagination handler
            $(document).on('click', 'nav[role=navigation] a', function (e) {
                e.preventDefault();
                const url = $(this).attr('href');
                const query = buildFilterQuery();
                fetchUsers(url + (query ? '&' + query : ''));
            });

            // Build filter query
            function buildFilterQuery() {
                const search = $('#searchInput-table').serialize();
                const filters = $('#statusFilter').serialize();
                const roleFilter = $('#roleFilter').serialize();

                return [search, filters, roleFilter]
                    .filter(param => param.length > 0)
                    .join('&');
            }

            // Apply filters
            function applyFilters() {
                const url = "{{route($routeName)}}";
                console.log(url)
                const query = buildFilterQuery();
                const fullUrl = query ? `${url}?${query}` : url;
                fetchUsers(fullUrl);
            }

            // Filter change handlers
            $(document).on('change', '#statusFilter', applyFilters);
            $(document).on('change', '#roleFilter', applyFilters);

            // Reset filters
            $(document).on('click', '#resetBtn', function (e) {
                e.preventDefault();
                $('#activeFilters').empty();
                $('#filterCount').remove();
                $('#statusFilter option').prop('selected', function () {
                    return this.defaultSelected;
                });
                $('#roleFilter option').prop('selected', function () {
                    return this.defaultSelected;
                });
                fetchUsers("{{route($routeName)}}");
            });

            // Search handler with debouncing
            const SearchHandler = {
                DEBOUNCE_DELAY: 250,
                debounceTimer: null,

                init() {
                    $('#searchInput-table').on('keyup', this.handleSearch.bind(this));
                },

                handleSearch() {
                    this.clearDebounceTimer();
                    const searchData = this.collectSearchData();

                    this.debounceTimer = setTimeout(() => {
                        this.executeSearch(searchData);
                    }, this.DEBOUNCE_DELAY);
                },

                collectSearchData() {
                    try {
                        return buildFilterQuery();
                    } catch (error) {
                        console.error('Error collecting search data:', error);
                        return '';
                    }
                },

                executeSearch(query) {
                    try {
                        const url = "{{route($routeName)}}";
                        this.clearActiveFilters();
                        fetchUsers(`${url}?${query}`, false);
                    } catch (error) {
                        console.error('Error executing search:', error);
                    }
                },

                clearActiveFilters() {
                    $('#activeFilters').empty();
                    $('#filterCount').remove();
                },

                clearDebounceTimer() {
                    if (this.debounceTimer) {
                        clearTimeout(this.debounceTimer);
                        this.debounceTimer = null;
                    }
                }
            };

            SearchHandler.init();

            // Clear search
            $(document).on('click', '#clearSearchBtn', function () {
                fetchUsers("{{route($routeName)}}", false);
            });

            // Fetch users data
            function fetchUsers(url, showLoad = true) {
                $.ajax({
                    url: url,
                    type: 'GET',
                    beforeSend: function () {
                        if (showLoad) {
                            Swal.fire({
                                title: 'Loading...',
                                text: 'Fetching user data...',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                        }
                    },
                    success: function (response) {
                        if (showLoad) Swal.close();
                        $('#user_data').html(response.rows);
                        $('#pagination').html(response.pagination);
                    },
                    error: function (xhr) {
                        if (showLoad) Swal.close();

                        if (xhr.responseJSON && xhr.responseJSON.toast) {
                            $('body').append(xhr.responseJSON.toast);
                        } else {
                            alert('Failed to load user data. Please try again.');
                        }

                        setTimeout(() => {
                            $('.toast-container').last().fadeOut(300, function () {
                                $(this).remove();
                            });
                        }, 2000);
                    }
                });
            }
        });
    </script>
@endsection