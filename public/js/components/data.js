function applicationTable(totalRows) {
    return {
        showActionDropdown: false,
        showBulkActionDropdown: false,
        actionDropdownPosition: { top: 0, left: 0 },
        selectedRow: null,
        selectedRows: [],
        selectAll: false,

        init() {
            // Close dropdowns when clicking outside
            document.addEventListener("click", (e) => {
                if (
                    !e.target.closest(".bulk-action-menu") &&
                    !e.target.closest(".bulk-action-button") &&
                    this.showBulkActionDropdown
                ) {
                    this.showBulkActionDropdown = false;
                }

                if (
                    !e.target.closest(".row-action-menu") &&
                    !e.target.closest(".row-action-button") &&
                    this.showActionDropdown
                ) {
                    this.showActionDropdown = false;
                }
            });
        },

        toggleActionMenu(event, rowId) {
            event.stopPropagation();
            this.selectedRow = rowId;
            this.showActionDropdown = !this.showActionDropdown;
            this.showBulkActionDropdown = false;

            // For desktop only - mobile uses CSS positioning
            if (window.innerWidth >= 640) {
                const rect = event.currentTarget.getBoundingClientRect();
                this.actionDropdownPosition = {
                    top: rect.bottom + window.scrollY + 5,
                    left: rect.right - 220, // Right-align with button
                };

                // Prevent dropdown from going off-screen
                const minLeft = 10;
                const maxLeft = window.innerWidth - 230;
                this.actionDropdownPosition.left = Math.min(
                    Math.max(this.actionDropdownPosition.left, minLeft),
                    maxLeft
                );
            }
        },

        toggleBulkActionMenu(event) {
            // Explicitly stop propagation
            event.stopPropagation();

            // Toggle dropdown visibility - FIXED VARIABLE NAME
            this.showBulkActionDropdown = !this.showBulkActionDropdown;
            this.showActionDropdown = false;
        },

        toggleRow(rowId, event) {
            // Only handle row clicking if not clicking on special elements
            if (event) {
                const target = event.target;
                // Prevent toggling when clicking buttons, checkboxes or dropdown elements
                if (
                    target.closest("button") ||
                    target.closest("input[type=checkbox]") ||
                    target.closest(".dropdown-content")
                ) {
                    return;
                }
            }

            const index = this.selectedRows.indexOf(rowId);
            if (index === -1) {
                this.selectedRows.push(rowId);
            } else {
                this.selectedRows.splice(index, 1);
            }

            // Update selectAll state based on if all rows are selected
            this.selectAll =
                this.selectedRows.length === this.totalRows &&
                this.selectedRows.length > 0;
        },

        toggleCheckbox(rowId, event) {
            // Explicitly stop propagation to prevent row toggle
            event.stopPropagation();

            const index = this.selectedRows.indexOf(rowId);
            if (index === -1) {
                this.selectedRows.push(rowId);
            } else {
                this.selectedRows.splice(index, 1);
            }

            // Update selectAll state
            this.selectAll =
                this.selectedRows.length === this.totalRows &&
                this.selectedRows.length > 0;
        },

        toggleSelectAll(event) {
            // Explicitly stop propagation
            if (event) event.stopPropagation();

            this.selectAll = !this.selectAll;
            if (this.selectAll) {
                // THE PROBLEM WAS WHEN ALPHINE WAS MOVED INTO A SEPARATE JS FILE, THE 'this.totalRows' returns undefined
                // MEANING IT DOESN'T READ THE LENGHT OF THE TOTAL ROWS IN THE TABLE
                this.selectedRows = Array.from(
                    { length: totalRows },
                    (_, i) => i
                );
            } else {
                this.selectedRows = [];
            }
        },

        toggleDeselectAll() {
            if (this.selectedRows.length !== 0) {
                this.selectAll = false;
                this.selectedRows = [];
            }
        },

        isSelected(rowId) {
            return this.selectedRows.includes(rowId);
        },

        executeBulkAction(action, event) {
            // Stop propagation to prevent unwanted behaviors
            event.stopPropagation();

            // Show different confirmation dialog based on action
            if (action === "delete") {
                Swal.fire({
                    title: "Are you sure?",
                    text: `You are about to delete ${this.selectedRows.length} item(s). This action cannot be undone.`,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, delete them!",
                    cancelButtonText: "Cancel",
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Here you would implement the actual delete functionality
                        this.processBulkAction(action);
                    }
                });
            } else if (action === "approve") {
                Swal.fire({
                    title: "Confirm Approval",
                    text: `You are about to approve ${this.selectedRows.length} item(s).`,
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#43A047",
                    cancelButtonColor: "#6B7280",
                    confirmButtonText: "Yes, approve them!",
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.processBulkAction(action);
                    }
                });
            } else if (action === "export") {
                Swal.fire({
                    title: "Export Data",
                    text: `You are about to export ${this.selectedRows.length} item(s).`,
                    icon: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#6B7280",
                    confirmButtonText: "Export",
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.processBulkAction(action);
                    }
                });
            } else if (action === "deactivate") {
                Swal.fire({
                    title: "Confirm Deactivation",
                    text: `You are about to deactivate ${this.selectedRows.length} item(s).`,
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#f97316",
                    cancelButtonColor: "#6B7280",
                    confirmButtonText: "Yes, deactivate them!",
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.processBulkAction(action);
                    }
                });
            } else {
                this.processBulkAction(action);
            }
        },

        processBulkAction(action) {
            console.log(`Executing ${action} on rows:`, this.selectedRows);

            // Simulate processing with a loading state
            Swal.fire({
                title: "Processing...",
                text: "Please wait while we process your request.",
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            });

            // Simulate API call with a timeout
            setTimeout(() => {
                // Show success message
                if (action === "delete") {
                    Swal.fire({
                        title: "Deleted!",
                        text: `${this.selectedRows.length} item(s) have been deleted successfully.`,
                        icon: "success",
                        confirmButtonColor: "#43A047",
                    });
                } else if (action === "approve") {
                    Swal.fire({
                        title: "Approved!",
                        text: `${this.selectedRows.length} item(s) have been approved successfully.`,
                        icon: "success",
                        confirmButtonColor: "#43A047",
                    });
                } else if (action === "export") {
                    Swal.fire({
                        title: "Exported!",
                        text: `${this.selectedRows.length} item(s) have been exported successfully.`,
                        icon: "success",
                        confirmButtonColor: "#43A047",
                    });
                } else if (action === "deactivate") {
                    Swal.fire({
                        title: "Deactivated!",
                        text: `${this.selectedRows.length} item(s) have been deactivated successfully.`,
                        icon: "success",
                        confirmButtonColor: "#43A047",
                    });
                }

                // Clear selections after successful action
                this.selectedRows = [];
                this.selectAll = false;
                this.showBulkActionDropdown = false;
            }, 1500);
        },

        handleRowAction(action, rowId, event) {
            event.stopPropagation(); // Prevent row selection
            this.showActionDropdown = false; // Close dropdown

            const entity = this.rows ? this.rows[rowId] : null;
            const entityName =
                entity && entity.name ? entity.name : `Item #${rowId + 1}`;

            if (action === "delete") {
                Swal.fire({
                    title: "Are you sure?",
                    text: `You are about to delete ${entityName}. This action cannot be undone.`,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, delete it!",
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.processRowAction(action, rowId, entityName);
                    }
                });
            } else if (action === "approve") {
                Swal.fire({
                    title: "Confirm Approval",
                    text: `You are about to approve ${entityName}.`,
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#43A047",
                    cancelButtonColor: "#6B7280",
                    confirmButtonText: "Yes, approve it!",
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.processRowAction(action, rowId, entityName);
                    }
                });
            } else if (action === "reset_password") {
                Swal.fire({
                    title: "Reset Password",
                    text: `You are about to reset the password for ${entityName}.`,
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#6B7280",
                    confirmButtonText: "Yes, reset it!",
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.processRowAction(action, rowId, entityName);
                    }
                });
            } else if (action === "deactivate") {
                Swal.fire({
                    title: "Confirm Deactivation",
                    text: `You are about to deactivate ${entityName}.`,
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#f97316",
                    cancelButtonColor: "#6B7280",
                    confirmButtonText: "Yes, deactivate it!",
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.processRowAction(action, rowId, entityName);
                    }
                });
            } else if (action === "edit") {
                // Directly proceed to edit without confirmation
                this.processRowAction(action, rowId, entityName);
            } else if (action === "view") {
                // Directly proceed to view without confirmation
                this.processRowAction(action, rowId, entityName);
            }
        },

        processRowAction(action, rowId, entityName) {
            console.log(`Executing ${action} on row:`, rowId);

            // For edit and view, navigate directly
            if (action === "edit" || action === "view") {
                window.location.href =
                    `{{ route('admin.applicant.show-details', ['type' => $type, 'id' => '__ID__']) }}`.replace(
                        "__ID__",
                        rowId + 1
                    );
                return;
            }

            // For other actions that need processing, show loading
            Swal.fire({
                title: "Processing...",
                text: "Please wait while we process your request.",
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            });

            // Simulate API call with a timeout
            setTimeout(() => {
                if (action === "delete") {
                    Swal.fire({
                        title: "Deleted!",
                        text: `${entityName} has been deleted successfully.`,
                        icon: "success",
                        confirmButtonColor: "#43A047",
                    });
                } else if (action === "approve") {
                    Swal.fire({
                        title: "Approved!",
                        text: `${entityName} has been approved successfully.`,
                        icon: "success",
                        confirmButtonColor: "#43A047",
                    });
                } else if (action === "reset_password") {
                    Swal.fire({
                        title: "Password Reset!",
                        text: `Password for ${entityName} has been reset successfully.`,
                        icon: "success",
                        confirmButtonColor: "#43A047",
                    });
                } else if (action === "deactivate") {
                    Swal.fire({
                        title: "Deactivated!",
                        text: `${entityName} has been deactivated successfully.`,
                        icon: "success",
                        confirmButtonColor: "#43A047",
                    });
                }
            }, 1000);
        },
    };
}
