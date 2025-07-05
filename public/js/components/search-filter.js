document.addEventListener("DOMContentLoaded", function () {
    // Initialize Flatpickr for date range picker with improved UI
    flatpickr("#dateRangePicker", {
        mode: "range",
        dateFormat: "Y-m-d",
        showMonths: 1,
        animate: true,
        disableMobile: true,
        onChange: function (selectedDates, dateStr, instance) {
            updateFilters();
        },
    });

    // DOM Elements
    const searchInput = document.getElementById("searchInput");
    const clearSearchBtn = document.getElementById("clearSearchBtn");
    const resetBtn = document.getElementById("resetBtn");
    const filterBtn = document.getElementById("filterBtn");
    const filterDropdown = document.getElementById("filterDropdown");
    const filterCount = document.getElementById("filterCount");
    const activeFilters = document.getElementById("activeFilters");
    const statusFilter = document.getElementById("statusFilter");
    const adminStatusFilter = document.getElementById("adminStatusFilter");
    // const roleFilter = document.getElementById('roleFilter'); // We're replacing this with dropdown
    const sortByFilter = document.getElementById("sortByFilter");
    const dateRangePicker = document.getElementById("dateRangePicker");
    const clearAllFiltersBtn = document.getElementById("clearAllFiltersBtn");
    const clearFiltersContainer = document.getElementById(
        "clearFiltersContainer"
    );

    // Type filter buttons
    const applicantTypeFilterBtn = document.getElementById(
        "applicantTypeFilterBtn"
    );
    const vehicleTypeFilterBtn = document.getElementById(
        "vehicleTypeFilterBtn"
    );
    const gatePassTypeFilterBtn = document.getElementById("gatePassTypeFilterBtn");
    const adminTypeFilterBtn = document.getElementById("adminTypeFilterBtn");
    const roleTypeFilterBtn = document.getElementById("roleTypeFilterBtn"); // Add role filter button

    // Type filter dropdowns
    const applicantTypeFilterDropdown = document.getElementById(
        "applicantTypeFilterDropdown"
    );
    const vehicleTypeFilterDropdown = document.getElementById(
        "vehicleTypeFilterDropdown"
    );
    const gatePassTypeFilterDropdown = document.getElementById(
        "gatePassTypeFilterDropdown"
    );
    const adminTypeFilterDropdown = document.getElementById(
        "adminTypeFilterDropdown"
    );
    const roleTypeFilterDropdown = document.getElementById("roleTypeFilterDropdown"); // Add role filter dropdown

    // All type filter buttons and dropdowns
    const typeFilterBtns = [
        applicantTypeFilterBtn,
        vehicleTypeFilterBtn,
        gatePassTypeFilterBtn,
        adminTypeFilterBtn,
        roleTypeFilterBtn,
    ];
    const typeFilterDropdowns = [
        applicantTypeFilterDropdown,
        vehicleTypeFilterDropdown,
        gatePassTypeFilterDropdown,
        adminTypeFilterDropdown,
        roleTypeFilterDropdown,
    ];

    let activeFiltersList = [];

    // Initialize active filters on page load
    initializeActiveFilters();

    // Toggle dropdown with smooth animation
    function toggleDropdown(dropdown, button, show = null) {
        if (!dropdown || !button) return;

        const isShowing =
            show !== null ? show : dropdown.classList.contains("hidden");
        const chevron = button.querySelector(".fa-chevron-down");

        if (isShowing) {
            // Show dropdown
            dropdown.classList.remove("hidden");
            setTimeout(() => {
                dropdown.classList.remove("opacity-0", "scale-95");
                dropdown.classList.add("opacity-100", "scale-100");
                if (chevron) chevron.classList.add("rotate-180");
            }, 10);
        } else {
            // Hide dropdown
            dropdown.classList.remove("opacity-100", "scale-100");
            dropdown.classList.add("opacity-0", "scale-95");
            if (chevron) chevron.classList.remove("rotate-180");

            setTimeout(() => {
                dropdown.classList.add("hidden");
            }, 200);
        }
    }

    // Toggle filter dropdown
    if (filterBtn) {
        filterBtn.addEventListener("click", function (e) {
            e.stopPropagation();
            toggleDropdown(filterDropdown, filterBtn);
        });
    }

    // Close dropdown when clicking outside
    document.addEventListener("click", function (e) {
        if (
            filterDropdown &&
            !filterDropdown.contains(e.target) &&
            !filterBtn.contains(e.target)
        ) {
            toggleDropdown(filterDropdown, filterBtn, false);
        }
    });

    // Set up type filter button toggles
    typeFilterBtns.forEach((btn, index) => {
        if (btn) {
            btn.addEventListener("click", function (e) {
                e.stopPropagation();
                const dropdown = typeFilterDropdowns[index];

                // Close all other type dropdowns first
                typeFilterDropdowns.forEach((d, i) => {
                    if (d && d !== dropdown) {
                        toggleDropdown(d, typeFilterBtns[i], false);
                    }
                });

                // Toggle current dropdown
                if (dropdown) {
                    toggleDropdown(dropdown, btn);
                }
            });
        }
    });

    // Close type dropdowns when clicking outside
    document.addEventListener("click", function (e) {
        typeFilterDropdowns.forEach((dropdown, index) => {
            if (
                dropdown &&
                !dropdown.contains(e.target) &&
                !typeFilterBtns.some((btn) => btn && btn.contains(e.target))
            ) {
                toggleDropdown(dropdown, typeFilterBtns[index], false);
            }
        });
    });

    // Add "Select All" functionality
    document.querySelectorAll(".select-all-types").forEach((checkbox) => {
        checkbox.addEventListener("change", function () {
            const container = this.closest(".type-dropdown");
            const checkboxes = container.querySelectorAll(".typeCheckbox");
            checkboxes.forEach((cb) => (cb.checked = this.checked));
            updateFilters();
            updateTypeSelectionLabel();
        });
    });

    // Update type selection label based on selected checkboxes
    function updateTypeSelectionLabel() {
        document.querySelectorAll('[id$="TypeFilterBtn"]').forEach((btn) => {
            if (!btn) return;

            const type = btn.id.replace("TypeFilterBtn", "");
            const checkboxes = document.querySelectorAll(
                `.${type}TypeCheckbox:checked`
            );
            const label = btn.querySelector(".selected-types-label");

            if (checkboxes.length === 0) {
                if (type === "admin") {
                    label.textContent = "Select Admin Types";
                } else if (type === "role") {
                    label.textContent = "Select Roles";
                } else {
                    label.textContent = "Select Types";
                }
            } else {
                label.textContent = `${checkboxes.length} Selected`;
            }
        });
    }

    // Update filters when type checkboxes change
    document.querySelectorAll(".typeCheckbox").forEach((checkbox) => {
        checkbox.addEventListener("change", function () {
            updateFilters();
            updateTypeSelectionLabel();

            // Update select all checkbox
            const container = this.closest(".type-dropdown");
            const selectAll = container.querySelector(".select-all-types");
            const checkboxes = container.querySelectorAll(".typeCheckbox");
            const allChecked = Array.from(checkboxes).every((cb) => cb.checked);
            const noneChecked = Array.from(checkboxes).every(
                (cb) => !cb.checked
            );

            selectAll.checked = allChecked;
            selectAll.indeterminate = !allChecked && !noneChecked;
        });
    });

    // Update filters when other filter inputs change
    [statusFilter, adminStatusFilter, sortByFilter, dateRangePicker].forEach(
        (filter) => {
            if (filter) {
                filter.addEventListener("change", updateFilters);
            }
        }
    );

    // Reset all filters
    if (resetBtn) {
        resetBtn.addEventListener("click", function () {
            resetAllFilters();
        });
    }

    // Clear all filters
    if (clearAllFiltersBtn) {
        clearAllFiltersBtn.addEventListener("click", function () {
            resetAllFilters();
        });
    }

    function resetAllFilters() {
        activeFiltersList = [];
        if (statusFilter) statusFilter.value = "all";
        if (adminStatusFilter) adminStatusFilter.value = "all";

        // Reset all type checkboxes
        document
            .querySelectorAll(".typeCheckbox")
            .forEach((checkbox) => (checkbox.checked = false));
        document.querySelectorAll(".select-all-types").forEach((checkbox) => {
            checkbox.checked = false;
            checkbox.indeterminate = false;
        });

        if (sortByFilter) sortByFilter.value = "newest";
        if (dateRangePicker) {
            dateRangePicker.value = "";
            dateRangePicker._flatpickr.clear();
        }
        if (searchInput) searchInput.value = "";
        if (clearSearchBtn) clearSearchBtn.classList.add("hidden");

        updateTypeSelectionLabel();
        updateActiveFilters();

        // Submit form to reset all filters
        const form = resetBtn ? resetBtn.closest("form") : null;
        if (form) {
            form.submit();
        }
    }

    // Search functionality with improved clear button
    if (searchInput) {
        searchInput.addEventListener("input", function () {
            clearSearchBtn.classList.toggle("hidden", !this.value);
        });

        // Initialize on page load
        clearSearchBtn.classList.toggle("hidden", !searchInput.value);
    }

    if (clearSearchBtn) {
        clearSearchBtn.addEventListener("click", function () {
            searchInput.value = "";
            searchInput.focus();
            this.classList.add("hidden");
        });
    }

    // Initialize active filters based on URL parameters
    function initializeActiveFilters() {
        const urlParams = new URLSearchParams(window.location.search);
        activeFiltersList = [];

        // Status filter
        if (
            statusFilter &&
            urlParams.has("status") &&
            urlParams.get("status") !== "all"
        ) {
            activeFiltersList.push({
                type: "status",
                value: urlParams.get("status"),
                label: statusFilter.options[statusFilter.selectedIndex].text,
            });
        }

        // Admin status filter
        if (
            adminStatusFilter &&
            urlParams.has("admin_status") &&
            urlParams.get("admin_status") !== "all"
        ) {
            activeFiltersList.push({
                type: "admin_status",
                value: urlParams.get("admin_status"),
                label: `Status: ${
                    adminStatusFilter.options[adminStatusFilter.selectedIndex]
                        .text
                }`,
            });
        }

        // Role type filters (using the new role_types[] parameter)
        if (urlParams.has("role_types[]")) {
            const types = urlParams.getAll("role_types[]");
            types.forEach((type) => {
                const checkbox = document.querySelector(
                    `.roleTypeCheckbox[value="${type}"]`
                );
                if (checkbox) {
                    checkbox.checked = true;
                    const label = checkbox.closest("label").textContent.trim();
                    activeFiltersList.push({
                        type: "role_type",
                        value: type,
                        label: `Role: ${label}`,
                    });
                }
            });
        } else if (urlParams.has("role") && urlParams.get("role") !== "all") {
            // For backward compatibility with the old role parameter
            // This can be removed once you've fully migrated to role_types[]
            const roleValue = urlParams.get("role");
            const roleCheckbox = document.querySelector(
                `.roleTypeCheckbox[value="${roleValue}"]`
            );
            if (roleCheckbox) {
                roleCheckbox.checked = true;
                const label = roleCheckbox.closest("label").textContent.trim();
                activeFiltersList.push({
                    type: "role_type",
                    value: roleValue,
                    label: `Role: ${label}`,
                });
            }
        }

        // Applicant type filters
        if (urlParams.has("applicant_types[]")) {
            const types = urlParams.getAll("applicant_types[]");
            types.forEach((type) => {
                const checkbox = document.querySelector(
                    `.applicantTypeCheckbox[value="${type}"]`
                );
                if (checkbox) {
                    checkbox.checked = true;
                    const label = checkbox.closest("label").textContent.trim();
                    activeFiltersList.push({
                        type: "applicant_type",
                        value: type,
                        label: label,
                    });
                }
            });
        }

        // Vehicle type filters
        if (urlParams.has("vehicle_types[]")) {
            const types = urlParams.getAll("vehicle_types[]");
            types.forEach((type) => {
                const checkbox = document.querySelector(
                    `.vehicleTypeCheckbox[value="${type}"]`
                );
                if (checkbox) {
                    checkbox.checked = true;
                    const label = checkbox.closest("label").textContent.trim();
                    activeFiltersList.push({
                        type: "vehicle_type",
                        value: type,
                        label: label,
                    });
                }
            });
        }

        // gatePass type filters
        if (urlParams.has("gatePass_types[]")) {
            const types = urlParams.getAll("gatePass_types[]");
            types.forEach((type) => {
                const checkbox = document.querySelector(
                    `.gatePassTypeCheckbox[value="${type}"]`
                );
                if (checkbox) {
                    checkbox.checked = true;
                    const label = checkbox.closest("label").textContent.trim();
                    activeFiltersList.push({
                        type: "gatePass_type",
                        value: type,
                        label: label,
                    });
                }
            });
        }

        // Admin type filters
        if (urlParams.has("admin_types[]")) {
            const types = urlParams.getAll("admin_types[]");
            types.forEach((type) => {
                const checkbox = document.querySelector(
                    `.adminTypeCheckbox[value="${type}"]`
                );
                if (checkbox) {
                    checkbox.checked = true;
                    const label = checkbox.closest("label").textContent.trim();
                    activeFiltersList.push({
                        type: "admin_type",
                        value: type,
                        label: label,
                    });
                }
            });
        }

        // Date range filter
        if (urlParams.has("date_range") && urlParams.get("date_range")) {
            activeFiltersList.push({
                type: "dateRange",
                value: urlParams.get("date_range"),
                label: `Date: ${urlParams.get("date_range")}`,
            });
        }

        // Sort filter
        if (
            sortByFilter &&
            urlParams.has("sort_by") &&
            urlParams.get("sort_by") !== "newest"
        ) {
            const sortValue = urlParams.get("sort_by");
            const sortOption = sortByFilter.querySelector(
                `option[value="${sortValue}"]`
            );
            activeFiltersList.push({
                type: "sortBy",
                value: sortValue,
                label: `Sorted by: ${
                    sortOption ? sortOption.textContent : sortValue
                }`,
            });
        }

        updateTypeSelectionLabel();
        updateActiveFilters();
    }

    // Create a filter badge with improved styling and interaction
    function createFilterBadge(filter) {
        const badge = document.createElement("div");
        badge.className =
            "px-3 py-1.5 bg-green-50 border border-green-100 rounded-full flex items-center gap-2 text-xs text-green-700 transition-all duration-200 hover:bg-green-100 group animate-fadeIn";
        badge.innerHTML = `
                ${filter.label}
                <button type="button" class="ml-1 opacity-70 group-hover:opacity-100 transition-opacity" data-filter-type="${filter.type}" data-filter-value="${filter.value}">
                    <i class="fas fa-times h-3 w-3 cursor-pointer text-green-600 hover:text-green-700"></i>
                </button>
            `;

        badge.querySelector("button").addEventListener("click", function () {
            const filterType = this.dataset.filterType;
            const filterValue = this.dataset.filterValue;

            // Handle different filter types
            if (
                filterType === "applicant_type" ||
                filterType === "vehicle_type" ||
                filterType === "gatePass_type" ||
                filterType === "admin_type" ||
                filterType === "role_type"
            ) {
                // Add role_type
                let selector;
                if (filterType === "applicant_type")
                    selector = `.applicantTypeCheckbox[value="${filterValue}"]`;
                else if (filterType === "vehicle_type")
                    selector = `.vehicleTypeCheckbox[value="${filterValue}"]`;
                else if (filterType === "gatePass_type")
                    selector = `.gatePassTypeCheckbox[value="${filterValue}"]`;
                else if (filterType === "role_type")
                    selector = `.roleTypeCheckbox[value="${filterValue}"]`;
                else selector = `.adminTypeCheckbox[value="${filterValue}"]`;

                const checkbox = document.querySelector(selector);
                if (checkbox) {
                    checkbox.checked = false;
                    // Also update the "Select All" checkbox
                    const container = checkbox.closest(".type-dropdown");
                    const selectAll =
                        container.querySelector(".select-all-types");
                    const allCheckboxes =
                        container.querySelectorAll(".typeCheckbox");
                    const noneChecked = Array.from(allCheckboxes).every(
                        (cb) => !cb.checked
                    );

                    selectAll.checked = false;
                    selectAll.indeterminate = !noneChecked;
                }
            } else {
                if (filterType === "status" && statusFilter)
                    statusFilter.value = "all";
                if (filterType === "admin_status" && adminStatusFilter)
                    adminStatusFilter.value = "all";
                if (filterType === "sortBy" && sortByFilter)
                    sortByFilter.value = "newest";
                if (filterType === "dateRange" && dateRangePicker) {
                    dateRangePicker.value = "";
                    dateRangePicker._flatpickr.clear();
                }
            }

            activeFiltersList = activeFiltersList.filter(
                (f) => !(f.type === filterType && f.value === filterValue)
            );
            updateTypeSelectionLabel();
            updateActiveFilters();

            // Submit form to apply filter change
            const form = activeFilters.closest("form");
            if (form) {
                form.submit();
            }
        });

        return badge;
    }

    // Update active filters display with smoother animations
    function updateActiveFilters() {
        if (filterCount) {
            filterCount.textContent = activeFiltersList.length;
            filterCount.classList.toggle(
                "hidden",
                activeFiltersList.length === 0
            );
        }

        if (activeFilters) {
            // Clear existing filters with a fade-out effect
            const existingBadges = activeFilters.querySelectorAll("div");
            if (existingBadges.length > 0) {
                existingBadges.forEach((badge) => {
                    badge.style.opacity = "0";
                    badge.style.transform = "translateY(-5px)";
                });

                setTimeout(() => {
                    activeFilters.innerHTML = "";
                    renderFilterBadges();
                }, 200);
            } else {
                renderFilterBadges();
            }
        }

        // Show/hide clear filters button
        if (clearFiltersContainer) {
            clearFiltersContainer.style.display =
                activeFiltersList.length > 0 ? "block" : "none";
        }
    }

    function renderFilterBadges() {
        if (activeFiltersList.length > 0) {
            activeFiltersList.forEach((filter, index) => {
                const badge = createFilterBadge(filter);
                badge.style.animationDelay = `${index * 50}ms`;
                activeFilters.appendChild(badge);
            });
        }
    }

    // Update filters with optimized performance
    function updateFilters() {
        activeFiltersList = [];

        // Status filter
        if (statusFilter && statusFilter.value !== "all") {
            activeFiltersList.push({
                type: "status",
                value: statusFilter.value,
                label: statusFilter.options[statusFilter.selectedIndex].text,
            });
        }

        // Admin status filter
        if (adminStatusFilter && adminStatusFilter.value !== "all") {
            activeFiltersList.push({
                type: "admin_status",
                value: adminStatusFilter.value,
                label: `Status: ${
                    adminStatusFilter.options[adminStatusFilter.selectedIndex]
                        .text
                }`,
            });
        }

        // Applicant type checkboxes
        document
            .querySelectorAll(".applicantTypeCheckbox:checked")
            .forEach((checkbox) => {
                activeFiltersList.push({
                    type: "applicant_type",
                    value: checkbox.value,
                    label: checkbox.closest("label").textContent.trim(),
                });
            });

        // Vehicle type checkboxes
        document
            .querySelectorAll(".vehicleTypeCheckbox:checked")
            .forEach((checkbox) => {
                activeFiltersList.push({
                    type: "vehicle_type",
                    value: checkbox.value,
                    label: checkbox.closest("label").textContent.trim(),
                });
            });

        // gatePass type checkboxes
        document
            .querySelectorAll(".gatePassTypeCheckbox:checked")
            .forEach((checkbox) => {
                activeFiltersList.push({
                    type: "gatePass_type",
                    value: checkbox.value,
                    label: checkbox.closest("label").textContent.trim(),
                });
            });

        // Admin type checkboxes
        document
            .querySelectorAll(".adminTypeCheckbox:checked")
            .forEach((checkbox) => {
                activeFiltersList.push({
                    type: "admin_type",
                    value: checkbox.value,
                    label: checkbox.closest("label").textContent.trim(),
                });
            });

        // Role type checkboxes
        document
            .querySelectorAll(".roleTypeCheckbox:checked")
            .forEach((checkbox) => {
                activeFiltersList.push({
                    type: "role_type",
                    value: checkbox.value,
                    label: `Role: ${checkbox
                        .closest("label")
                        .textContent.trim()}`,
                });
            });

        // Date range filter
        if (dateRangePicker && dateRangePicker.value) {
            activeFiltersList.push({
                type: "dateRange",
                value: dateRangePicker.value,
                label: `Date: ${dateRangePicker.value}`,
            });
        }

        // Sort filter
        if (sortByFilter && sortByFilter.value !== "newest") {
            activeFiltersList.push({
                type: "sortBy",
                value: sortByFilter.value,
                label: `Sorted by: ${
                    sortByFilter.options[sortByFilter.selectedIndex].text
                }`,
            });
        }

        updateActiveFilters();
    }

    // In your JavaScript section, ensure this part is working correctly
    document.addEventListener("tab-changed", function (e) {
        // Get the Alpine.js component instance
        const xData = document.querySelector("[x-data]").__x.$data;

        if (e.detail === "Applicant") {
            xData.activeFilterType = "applicant";
        } else if (e.detail === "Registered Vehicles") {
            xData.activeFilterType = "vehicle";
        } else if (e.detail === "Gate Pass Management") {
            xData.activeFilterType = "gate-pass";
        } else if (
            e.detail === "All Admins" ||
            e.detail === "Active" ||
            e.detail === "Inactive"
        ) {
            xData.activeFilterType = "admins";
        }

        // Reinitialize type selection labels after tab change
        updateTypeSelectionLabel();
    });

    // Add keyboard navigation support
    typeFilterDropdowns.forEach((dropdown) => {
        if (dropdown) {
            dropdown.querySelectorAll("label").forEach((label, i, labels) => {
                label.setAttribute("tabindex", "0");
                label.addEventListener("keydown", function (e) {
                    if (e.key === "Enter" || e.key === " ") {
                        const checkbox = this.querySelector(
                            'input[type="checkbox"]'
                        );
                        checkbox.checked = !checkbox.checked;
                        checkbox.dispatchEvent(new Event("change"));
                        e.preventDefault();
                    } else if (e.key === "ArrowDown" && i < labels.length - 1) {
                        labels[i + 1].focus();
                        e.preventDefault();
                    } else if (e.key === "ArrowUp" && i > 0) {
                        labels[i - 1].focus();
                        e.preventDefault();
                    }
                });
            });
        }
    });
});
