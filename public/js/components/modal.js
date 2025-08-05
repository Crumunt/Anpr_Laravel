function entityFormHandler(type, entityId, modalId) {
    return {
        type: type,
        entityId: entityId,
        modalId: modalId,
        formData: {},
        isSubmitting: false,
        errors: {}, // Object to store field-specific validation errors
        filePreview: [],
        fileInput: [],
        currentStep: 1,

        init() {
            this.initializeFormData();
        },

        initializeFormData() {
            this.currentStep = 1;
            // Reset error state when initializing form
            this.errors = {};

            switch (this.type) {
                case "applicant":
                    this.formData = {
                        full_name: "",
                        address: "",
                        department: "",
                        position: "",
                        phone: "",
                        email: "",
                        clsu_id: "",
                    };
                    break;
                case "vehicle":
                    this.formData = {
                        license_plate: "",
                        vehicle_type: "",
                        make: "",
                        model: "",
                        year: new Date().getFullYear(),
                        color: "",
                        owner: "",
                        registration_date: new Date()
                            .toISOString()
                            .split("T")[0],
                    };
                    break;
                case "rfid":
                    this.formData = {
                        tag_number: "",
                        tag_type: "",
                        issue_date: new Date().toISOString().split("T")[0],
                        expiry_date: new Date(
                            new Date().setFullYear(new Date().getFullYear() + 1)
                        )
                            .toISOString()
                            .split("T")[0],
                        status: "active",
                        assigned_to: "",
                        vehicle: "",
                        notes: "",
                    };
                    break;
                case "admin":
                    this.formData = {
                        first_name: "",
                        last_name: "",
                        email: "",
                        phone: "",
                        role: "",
                        status: "active",
                        password: "",
                        password_confirmation: "",
                        permissions: [],
                    };
                    break;
                case "document_upload":
                    this.formData = {
                        document_type: "",
                        custom_document_type: "",
                        description: "",
                        expiry_date: "",
                        tags: "",
                    };
                    break;
            }
        },

        handleFileChange(event) {
            console.log(this.filePreview);
            const files = event.target.files;
            if (!files || files.length === 0) return;

            this.errors.file = null;
            this.filePreview = [];
            this.fileInput = [];

            for (let i = 0; i < files.length; i++) {
                const file = files[i];

                // Check size limit
                if (file.size > 10 * 1024 * 1024) {
                    this.errors.file = "File size should not exceed 10MB";
                    event.target.value = "";
                    this.filePreview = [];
                    this.fileInput = [];
                    return;
                }

                const type = this.getFileType(file.name);
                const formattedSize = this.formatFileSize(file.size);

                this.filePreview.push({
                    name: file.name,
                    size: formattedSize,
                    type: type,
                });

                this.fileInput.push(file);
            }
        },

        getFileType(filename) {
            const extension = filename.split(".").pop().toLowerCase();
            const types = {
                "jpg|jpeg|png|gif": "image",
                "doc|docx": "word",
                "xls|xlsx": "excel",
                pdf: "pdf",
            };

            for (const [pattern, type] of Object.entries(types)) {
                if (new RegExp(pattern).test(extension)) return type;
            }
            return "other";
        },

        formatFileSize(bytes) {
            const sizes = ["Bytes", "KB", "MB", "GB"];
            if (bytes === 0) return "0 Byte";
            const i = Math.floor(Math.log(bytes) / Math.log(1024));
            return Math.round(bytes / Math.pow(1024, i)) + " " + sizes[i];
        },

        removeFile() {
            this.filePreview = [];
            this.fileInput = [];
            this.errors.file = null;
            document.getElementById("file-upload").value = "";
        },

        validateForm() {
            // Reset all errors before validation
            this.errors = {};
            let isValid = true;

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const phoneRegex = /^\+?\d{7,15}$/;

            switch (this.type) {
                case "applicant":
                    if (!this.formData.full_name) {
                        this.errors.full_name = "Full Name is required";
                        isValid = false;
                    }
                    if (!this.formData.address) {
                        this.errors.address = "Address is required";
                        isValid = false;
                    }
                    if (!this.formData.department) {
                        this.errors.department = "Department is required";
                        isValid = false;
                    }
                    if (!this.formData.position) {
                        this.errors.position = "Position is required";
                        isValid = false;
                    }
                    if (!this.formData.phone) {
                        this.errors.phone = "Phone number is required";
                        isValid = false;
                    } else if (!phoneRegex.test(this.formData.phone)) {
                        this.errors.phone = "Valid phone number required";
                        isValid = false;
                    }
                    if (!this.formData.email) {
                        this.errors.email = "Email is required";
                        isValid = false;
                    } else if (!emailRegex.test(this.formData.email)) {
                        this.errors.email = "Valid email required";
                        isValid = false;
                    }
                    if (!this.formData.clsu_id) {
                        this.errors.clsu_id = "CLSU ID is required";
                        isValid = false;
                    }
                    break;
                case "vehicle":
                    if (!this.formData.license_plate) {
                        this.errors.license_plate = "License Plate is required";
                        isValid = false;
                    }
                    if (!this.formData.vehicle_type) {
                        this.errors.vehicle_type = "Vehicle type is required";
                        isValid = false;
                    }
                    if (!this.formData.make) {
                        this.errors.make = "Make/Brand is required";
                        isValid = false;
                    }
                    if (!this.formData.model) {
                        this.errors.model = "Model is required";
                        isValid = false;
                    }
                    if (!this.formData.year) {
                        this.errors.year = "Year is required";
                        isValid = false;
                    } else if (
                        this.formData.year < 1900 ||
                        this.formData.year > new Date().getFullYear() + 1
                    ) {
                        this.errors.year = "Invalid vehicle year";
                        isValid = false;
                    }
                    if (!this.formData.color) {
                        this.errors.color = "Color is required";
                        isValid = false;
                    }
                    if (!this.formData.owner) {
                        this.errors.owner = "Owner is required";
                        isValid = false;
                    }
                    if (!this.formData.registration_date) {
                        this.errors.registration_date =
                            "Registration date is required";
                        isValid = false;
                    }
                    break;
                case "rfid":
                    if (!this.formData.tag_number) {
                        this.errors.tag_number = "Tag number is required";
                        isValid = false;
                    }
                    if (!this.formData.tag_type) {
                        this.errors.tag_type = "Tag type is required";
                        isValid = false;
                    }
                    if (!this.formData.issue_date) {
                        this.errors.issue_date = "Issue date is required";
                        isValid = false;
                    }
                    if (!this.formData.expiry_date) {
                        this.errors.expiry_date = "Expiry date is required";
                        isValid = false;
                    } else if (
                        new Date(this.formData.expiry_date) <=
                        new Date(this.formData.issue_date)
                    ) {
                        this.errors.expiry_date =
                            "Expiry date must be after issue date";
                        isValid = false;
                    }
                    if (!this.formData.assigned_to) {
                        this.errors.assigned_to = "Assigned To is required";
                        isValid = false;
                    }
                    break;
                case "admin":
                    if (!this.formData.first_name) {
                        this.errors.first_name = "First Name is required";
                        isValid = false;
                    }
                    if (!this.formData.last_name) {
                        this.errors.last_name = "Last Name is required";
                        isValid = false;
                    }
                    if (!this.formData.email) {
                        this.errors.email = "Email is required";
                        isValid = false;
                    } else if (!emailRegex.test(this.formData.email)) {
                        this.errors.email = "Valid email required";
                        isValid = false;
                    }
                    if (!this.formData.phone) {
                        this.errors.phone = "Phone number is required";
                        isValid = false;
                    } else if (!phoneRegex.test(this.formData.phone)) {
                        this.errors.phone = "Valid phone number required";
                        isValid = false;
                    }
                    if (!this.formData.role) {
                        this.errors.role = "Role selection is required";
                        isValid = false;
                    }
                    if (!this.formData.password) {
                        this.errors.password = "Password is required";
                        isValid = false;
                    } else if (this.formData.password.length < 8) {
                        this.errors.password =
                            "Password must be at least 8 characters";
                        isValid = false;
                    }
                    if (!this.formData.password_confirmation) {
                        this.errors.password_confirmation =
                            "Please confirm password";
                        isValid = false;
                    } else if (
                        this.formData.password !==
                        this.formData.password_confirmation
                    ) {
                        this.errors.password_confirmation =
                            "Passwords do not match";
                        isValid = false;
                    }
                    if (this.formData.permissions.length === 0) {
                        this.errors.permissions =
                            "At least one permission is required";
                        isValid = false;
                    }
                    break;
                case "document_upload":
                    if (!this.fileInput) {
                        this.errors.file = "File upload is required";
                        isValid = false;
                    }
                    if (!this.formData.document_type) {
                        this.errors.document_type = "Document type is required";
                        isValid = false;
                    }
                    if (
                        this.formData.document_type === "other" &&
                        !this.formData.custom_document_type
                    ) {
                        this.errors.custom_document_type =
                            "Custom document type is required";
                        isValid = false;
                    }
                    if (!this.formData.description) {
                        this.errors.description = "Description is required";
                        isValid = false;
                    }
                    break;
            }
            return isValid;
        },

        async submitForm() {
            // Validate the form before submission
            if (!this.validateForm()) {
                // Scroll to the first error
                const firstErrorField =
                    document.querySelector(".border-red-300");
                if (firstErrorField) {
                    firstErrorField.scrollIntoView({
                        behavior: "smooth",
                        block: "center",
                    });
                    firstErrorField.focus();
                }
                return;
            }

            const action =
                this.type === "document_upload" ? "upload" : "create";
            const entityName =
                this.type.charAt(0).toUpperCase() + this.type.slice(1);

            try {
                const confirmation = await SweetAlertHelper.confirmAction(
                    action,
                    1,
                    entityName
                );
                if (!confirmation.isConfirmed) return;

                const loader = SweetAlertHelper.showLoading();
                this.isSubmitting = true;

                // Simulate API call
                await new Promise((resolve) => setTimeout(resolve, 1500));

                SweetAlertHelper.showSuccessMessage(action, 1, entityName);
                this.initializeFormData();
                if (this.type === "document_upload") this.removeFile();
                closeModal(this.modalId);
            } catch (error) {
                Swal.fire({
                    title: "Error!",
                    text: error.message || "An error occurred",
                    icon: "error",
                    confirmButtonColor: "#43A047",
                });
            } finally {
                this.isSubmitting = false;
                Swal.close();
            }
        },

        prevStep() {
            if (this.currentStep > 1) {
                this.currentStep--;
            }
        },

        nextStep() {
            if (this.currentStep < 4) {
                this.currentStep++;
            }
        },
    };
}

function locationSelector() {
    return {
        regions: [],
        provinces: [],
        cityMunicipalities: [],
        barangays: [],
        selected: {
            region: "",
            province: "",
            citymun: "",
            barangay: "",
        },

        async init() {
            try {
                console.log("location selector has been initialized");

                const res = await fetch("/Anpr_Laravel/public/api/regions");

                this.regions = await res.json();
            } catch (error) {
                console.log(`Something went wrong!. ${error}`);
            }
        },

        async onRegionChange() {
            this.selected.province = "";
            this.selected.citymun = "";
            this.selected.barangay = "";
            this.provinces = [];
            this.cityMunicipalities = [];
            this.barangays = [];
            if (!this.selected.region) return;
            const res = await fetch(
                `/Anpr_Laravel/public/api/provinces?region_name=${this.selected.region}`
            );
            this.provinces = await res.json();
        },

        async onProvinceChange() {
            this.selected.citymun = "";
            this.selected.barangay = "";
            this.cityMunicipalities = [];
            this.barangays = [];
            if (!this.selected.province) return;
            const res = await fetch(
                `/Anpr_Laravel/public/api/city-municipalities?province_name=${this.selected.province}`
            );
            this.cityMunicipalities = await res.json();
        },

        async onCityMunChange() {
            this.selected.barangay = "";
            this.barangays = [];
            if (!this.selected.citymun) return;
            const res = await fetch(
                `/Anpr_Laravel/public/api/barangays?citymun_name=${this.selected.citymun}`
            );
            this.barangays = await res.json();
            
            console.log(this.barangays)
        },
    };
}

window.SweetAlertHelper = {
    confirmDelete(count, entityName = null) {
        const message = entityName
            ? `You are about to delete ${entityName}. This action cannot be undone.`
            : `You are about to delete ${count} item(s). This action cannot be undone.`;
        return Swal.fire({
            title: "Are you sure?",
            text: message,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!",
        });
    },

    confirmApproval(count, entityName = null) {
        const message = entityName
            ? `You are about to approve ${entityName}.`
            : `You are about to approve ${count} item(s).`;
        return Swal.fire({
            title: "Confirm Approval",
            text: message,
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#43A047",
            cancelButtonColor: "#6B7280",
            confirmButtonText: "Yes, approve it!",
        });
    },

    confirmCreate(entityName) {
        return Swal.fire({
            title: "Confirm Creation",
            text: `You are about to create a new ${entityName}.`,
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#43A047",
            cancelButtonColor: "#6B7280",
            confirmButtonText: "Yes, create it!",
        });
    },

    confirmUpload(entityName) {
        return Swal.fire({
            title: "Confirm Upload",
            text: `You are about to upload ${entityName}.`,
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#43A047",
            cancelButtonColor: "#6B7280",
            confirmButtonText: "Yes, upload it!",
        });
    },

    showLoading() {
        return Swal.fire({
            title: "Processing...",
            text: "Please wait while we process your request.",
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => Swal.showLoading(),
        });
    },

    showSuccessMessage(action, count = 1, entityName = null) {
        let title, text;
        switch (action) {
            case "delete":
                title = "Deleted!";
                text = entityName
                    ? `${entityName} has been deleted.`
                    : `${count} item(s) deleted.`;
                break;
            case "approve":
                title = "Approved!";
                text = entityName
                    ? `${entityName} has been approved.`
                    : `${count} item(s) approved.`;
                break;
            case "create":
                title = "Created!";
                text = entityName
                    ? `${entityName} has been created.`
                    : `${count} item(s) created.`;
                break;
            case "upload":
                title = "Uploaded!";
                text = entityName
                    ? `${entityName} has been uploaded.`
                    : `${count} item(s) uploaded.`;
                break;
            default:
                title = "Success!";
                text = "Operation completed successfully.";
        }
        return Swal.fire({
            title,
            text,
            icon: "success",
            confirmButtonColor: "#43A047",
        });
    },

    confirmAction(action, count = 1, entityName = null) {
        switch (action) {
            case "delete":
                return this.confirmDelete(count, entityName);
            case "approve":
                return this.confirmApproval(count, entityName);
            case "create":
                return this.confirmCreate(entityName);
            case "upload":
                return this.confirmUpload(entityName);
            default:
                return Promise.resolve({
                    isConfirmed: true,
                });
        }
    },
};

document.addEventListener("DOMContentLoaded", function () {
    // Modal animation functions
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) return;

        modal.classList.remove("hidden");
        setTimeout(() => {
            modal.querySelector(".modal-backdrop").classList.add("opacity-100");
            modal
                .querySelector(".modal-content")
                .classList.remove("scale-95", "opacity-0");
            modal
                .querySelector(".modal-content")
                .classList.add("scale-100", "opacity-100");

            // Set focus to first focusable element
            const focusable = modal.querySelectorAll(
                'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
            );
            if (focusable.length) focusable[0].focus();
        }, 10);

        // Prevent body scrolling when modal is open
        document.body.style.overflow = "hidden";
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) return;

        modal.querySelector(".modal-backdrop").classList.remove("opacity-100");
        modal
            .querySelector(".modal-content")
            .classList.remove("scale-100", "opacity-100");
        modal
            .querySelector(".modal-content")
            .classList.add("scale-95", "opacity-0");

        setTimeout(() => {
            modal.classList.add("hidden");
            // Restore body scrolling
            document.body.style.overflow = "";
        }, 300);
    }

    // Make the functions globally available
    window.openModal = openModal;
    window.closeModal = closeModal;

    // Open modals
    document.querySelectorAll("[data-open-modal]").forEach((button) => {
        button.addEventListener("click", function () {
            const modalId = this.getAttribute("data-open-modal");
            openModal(modalId);
        });
    });

    // Close modals
    document.querySelectorAll(".close-modal-btn").forEach((button) => {
        button.addEventListener("click", function () {
            const modalId = this.getAttribute("data-modal");
            closeModal(modalId);
        });
    });

    // Close modals when clicking cancel buttons
    document.querySelectorAll("[data-cancel-modal]").forEach((button) => {
        button.addEventListener("click", function () {
            const modalId = this.getAttribute("data-cancel-modal");
            closeModal(modalId);
        });
    });

    // Close modal with Escape key
    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") {
            document.querySelectorAll(".modal-backdrop").forEach((backdrop) => {
                if (backdrop.classList.contains("opacity-100")) {
                    const modal = backdrop.closest("[id]");
                    if (modal) closeModal(modal.id);
                }
            });
        }
    });

    // Close modal when clicking backdrop
    document.querySelectorAll("[id].fixed").forEach((modal) => {
        modal.addEventListener("click", function (event) {
            if (
                event.target === this ||
                event.target.classList.contains("modal-backdrop")
            ) {
                closeModal(this.id);
            }
        });
    });

    // Trap focus inside modal
    document.querySelectorAll("[id].fixed").forEach((modal) => {
        modal.addEventListener("keydown", function (e) {
            if (e.key !== "Tab") return;

            const focusable = modal.querySelectorAll(
                'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
            );
            const firstFocusable = focusable[0];
            const lastFocusable = focusable[focusable.length - 1];

            if (e.shiftKey && document.activeElement === firstFocusable) {
                e.preventDefault();
                lastFocusable.focus();
            } else if (
                !e.shiftKey &&
                document.activeElement === lastFocusable
            ) {
                e.preventDefault();
                firstFocusable.focus();
            }
        });
    });
});
