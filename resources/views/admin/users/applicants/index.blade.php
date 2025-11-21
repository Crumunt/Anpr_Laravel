@extends('layouts.app-layout')
@section('main-content')


    <div class="flex-1 md:ml-64 p-6 pt-24">
        <!-- Dashboard Cards -->
        <div
            class="w-full bg-white rounded-xl shadow-sm border border-gray-100 transition-all duration-300 hover:shadow-md">
            <div dir="ltr" class="w-full">
            </div>
            <div class="p-6">
                <div class="w-full space-y-6">
                    <!-- Header -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <h2 class="text-2xl font-bold text-gray-800">Applicant Management</h2>
                        <div class="flex space-x-2">
                            <x-dashboard.buttons :icon="false" class="bg-emerald-600 hover:bg-emerald-700"
                                data-open-modal="applicant_modal">
                                <span slot="icon" class="mr-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <path d="M8 12h8"></path>
                                        <path d="M12 8v8"></path>
                                    </svg>
                                </span>
                                <span>Add Applicant</span>
                            </x-dashboard.buttons>
                        </div>
                    </div>

<<<<<<< HEAD
                    <x-modal.modal id="applicant_modal" type="applicant" action="add" />
=======
                    <x-modal.modal id="applicant_modal" type="applicant" action="add" maxWidth="3xl" />
>>>>>>> b9a42a6ee6a4bcd1afa37d466361da6ab061895d
                    <!-- Moved search filter inside the space-y-6 container -->
                    <x-dashboard.search-filter :showType="'applicant'" />

                    <!-- Removed mt-6 since space-y-6 will handle spacing -->
                    <div class="w-full space-y-6" id="table_wrapper">
                        <x-table.data :type="'applicant'" context="user_applicant" :rows="$userDetails"
                            caption="Applicants list for Summer 2024 Program" />
                    </div>
                    <div id="pagination">
                        <x-pagination :pagination="$users" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-footer.footer></x-footer.footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $(document).on('click', 'nav[role=navigation] a', function (e) {
                e.preventDefault();
                const url = $(this).attr('href');
                const search = $('#searchInput-table').serialize();
                const filters = $('#statusFilter').serialize();
                const types = $('#applicantTypeFilterDropdown input[type="checkbox"]').serialize();
                const query = `&${filters}&${types}&${search}`

                fetchApplicants(url + '&' + query);
            });
            // END FUNCTION

            // single function for fetching filter data
            function buildFilterQuery() {
                const search = $('#searchInput-table').serialize();
                const status = $('#statusFilter').serialize();
                const types = $('#applicantTypeFilterDropdown input[type="checkbox"]').serialize();
                const sort = $('#sortByFilter').serialize();

                return [search, status, types, sort]
                    .filter(param => param.length > 0)
                    .join('&');
            }

            function applyFilters() {
                const url = "{{ route('admin.applicant') }}";
                const query = buildFilterQuery();
                const fullUrl = query ? `${url}?${query}` : url;
                fetchApplicants(fullUrl);
            }

            // simpler handlers
            $(document).on('change', '#statusFilter', applyFilters);
            $(document).on('change', '#sortByFilter', applyFilters);
            $(document).on('change', '#applicantTypeFilterDropdown input[type="checkbox"]', applyFilters);

            $(document).on('click', '#resetBtn', function (e) {
                e.preventDefault();
                $('#activeFilters').empty();
                $('#filterCount').remove();
                $('#statusFilter option').prop('selected', function () {
                    return this.defaultSelected;
                });
                // Reset other filters too
                fetchApplicants("{{ route('admin.applicant') }}");
            });

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
                        const search = $('#searchInput-table').serialize();
                        const filters = $('#statusFilter').serialize();
                        const types = $('#applicantTypeFilterDropdown input[type="checkbox"]').serialize();

                        return `${search}&${filters}&${types}`;
                    } catch (error) {
                        console.error('Error collecting search data:', error);
                        return '';
                    }
                },

                executeSearch(query) {
                    try {
                        const url = "{{ route('admin.applicant') }}";
                        this.clearActiveFilters();
                        fetchApplicants(`${url}?${query}`, false);
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
                },

                destroy() {
                    this.clearDebounceTimer();
                    $('#searchInput-table').off('keyup');
                }
            };

            // Usage
            SearchHandler.init();

            $(document).on('click', '#clearSearchBtn', function () {
                const url = "{{ route('admin.applicant') }}";
                const showLoad = false;

                fetchApplicants(url, showLoad);
            })

            function fetchApplicants(url, showLoad = true) {
                $.ajax({
                    url: url,
                    type: 'GET',
                    beforeSend: function () {
                        console.log(url)
                        if (showLoad) {
                            Swal.fire({
                                title: 'Loading...',
                                text: 'Hang tight — getting your data....',
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
                        // console.log(response)
                        $('#user_data').html(response.rows);
                        $('#pagination').html(response.pagination);
                    },
                    error: function (xhr) {
                        if (showLoad) Swal.close();
                        // Try to get toast from server response
                        if (xhr.responseJSON && xhr.responseJSON.toast) {
                            console.log(xhr.responseJSON)
                            $('body').append(xhr.responseJSON.toast);
                        } else {
                            alert('Failed to load data. Please try again.');
                        }

                        // Auto-remove toast after 5 seconds
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