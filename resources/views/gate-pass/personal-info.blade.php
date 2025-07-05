<x-layout>
    <div id="application-form" class="application-form">

        <form id="rfidApplicationForm" method="POST" action="{{ route('rfid.submit') }}" enctype="multipart/form-data">
            @csrf

            <div id="form-tabs" role="tablist" aria-orientation="horizontal"
                class="h-9 items-center justify-center rounded-lg bg-muted p-1 text-muted-foreground grid grid-cols-4 mb-8 transition-all duration-300"
                tabindex="0" style="outline: none;">
                <x-forms.tab-button
                    :active="true"
                    icon="user"
                    label="Personal Info"
                    data-step="1"
                    id="tab-personal" />
                <x-forms.tab-button :active="false" icon="car"
                    label="Vehicle Info"
                    data-step="2"
                    id="tab-vehicle" />
                <x-forms.tab-button
                    :active="false"
                    icon="file-check"
                    label="Documents"
                    data-step="3"
                    id="tab-documents" />
                <x-forms.tab-button
                    :active="false"
                    icon="arrow-right"
                    label="Review"
                    data-step="4"
                    id="tab-review" />
            </div>

            <div class="form-step" id="step-1">
                <x-forms.form-section title="Personal Information">
                    <x-forms.form-field
                        label="Full Name"
                        id="firstName"
                        name="firstName"
                        placeholder="Alden A. Richard"
                        :required="true" />
                    <x-forms.form-field
                        label="Home Address"
                        id="lastName"
                        name="lastName"
                        placeholder="Street, Barangay, City, Province"
                        :required="true" />
                    <x-forms.form-field
                        label="College/Unit/Department"
                        id="department"
                        name="department"
                        placeholder="E.g., College of Business, HR Department, Research Unit"
                        :required="true" />
                    <x-forms.form-field
                        label="Designation/Position"
                        id="position"
                        name="position"
                        placeholder="E.g., Student, Faculty, Chuchu"
                        :required="true" />
                    <x-forms.form-field
                        label="Phone Number"
                        id="phone"
                        name="phone"
                        type="tel"
                        placeholder="Enter your phone number"
                        :required="true" />
                    <x-forms.form-field
                        label="Email Address"
                        id="email"
                        name="email"
                        type="email"
                        placeholder="Enter your email address"
                        :required="true" />
                    <x-forms.form-field
                        label="CLSU ID Number"
                        id="id_number"
                        name="id_number"
                        placeholder="Enter your CLSU ID number"
                        :required="true" />
                    <x-forms.form-field
                        label="Type"
                        id="type"
                        name="type"
                        type="select"
                        :required="true">
                        <option value="">Select Type</option>
                        <option value="student">Student</option>
                        <option value="faculty">Faculty</option>
                        <option value="staff">Staff</option>
                        <option value="visitor">Visitor</option>
                    </x-forms.form-field>
                </x-forms.form-section>
                `
                <div class="flex justify-between mt-6">
                    <div></div>
                    <button type="button" class="next-btn
            inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium
            focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring
            disabled:pointer-events-none disabled:opacity-50 text-white shadow
            h-9 px-4 py-2 bg-green-700 hover:bg-green-800 transition-all duration-300
            transform hover:translate-x-1"
                        data-next="2">
                        Next: Vehicle Information
                    </button>
                </div>
            </div>
            <div class="form-step hidden" id="step-2">
                <x-forms.form-section title="Vehicle Information">
                    <x-forms.form-field
                        label="Vehicle Type"
                        id="vehicleType"
                        name="vehicleType"
                        type="select" :required="true">
                        <option value="" disabled selected>Select vehicle type</option>
                        <option value="car">Car</option>
                        <option value="motorcycle">Motorcycle</option>
                        <option value="truck">Truck</option>
                        <option value="van">Van</option>
                        <option value="suv">SUV</option>
                    </x-forms.form-field>
                    <x-forms.form-field label="Make"
                        id="make"
                        name="make"
                        placeholder="E.g., Toyota, Honda, Ford, Nissan" :required="true" />
                    <x-forms.form-field
                        label="Model"
                        id="model" name="model" placeholder="E.g., Civic, Corolla, Mustang, Fortuner"
                        :required="true" /> <x-forms.form-field
                        label="Color"
                        id="color" name="color"
                        placeholder="E.g., Red, Blue, Black, White"
                        :required="true" /> <x-forms.form-field
                        label="Year"
                        id="year" name="year" type="number" placeholder="E.g., 2022"
                        :required="true" />
                    <x-forms.form-field
                        label="Plate Number"
                        id="plateNumber"
                        name="plateNumber" placeholder="E.g., ABC-1234"
                        :required="true" />
                </x-forms.form-section>
                <div class="flex justify-between mt-6"> <button type="button" class="prev-btn
                        inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium     focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring
    disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm
                        hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 transition-all duration-300
                        transform hover:-translate-x-1"
                        data-prev="1">
                        Back: Personal Information
                    </button>
                    <button type="button" class="next-btn
                inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium
                        focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring
                       disabled:pointer-events-none disabled:opacity-50 text-white shadow
                   h-9 px-4 py-2 bg-green-700 hover:bg-green-800 transition-all duration-300
                        transform hover:translate-x-1"
                        data-next="3">
                        Next: Documents
                    </button>
                </div>
            </div>

            <div class="form-step hidden" id="step-3">
                @include('gate-pass.documents')
                <div class="flex justify-between mt-6">
                    <button type="button" class="prev-btn
                        inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium
                        focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring
                        disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 transition-all duration-300 transform hover:-translate-x-1"
                        data-prev="2">
                        Back: Vehicle Information
                    </button>
                    <button type="button" class="next-btn
                        inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium
                        focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring
                        disabled:pointer-events-none disabled:opacity-50 text-white shadow h-9 px-4 py-2 bg-green-700 hover:bg-green-800 transition-all duration-300 transform hover:translate-x-1"
                        data-next="4">
                        Next: Review
                    </button>
                </div>
            </div>

            <div class="form-step hidden" id="step-4">
                <x-forms.application-review title="Review Your Application">
                    <!-- Personal Information -->
                    <x-forms.review-section title="Personal Information">
                        <x-forms.review-item label="Name">
                            <span id="review-name"></span>
                        </x-forms.review-item> <x-forms.review-item label="Email">
                            <span id="review-email"></span> </x-forms.review-item>
                        <x-forms.review-item label="Contact Number">
                            <span id="review-phone"></span> </x-forms.review-item>
                        <x-forms.review-item label="CLSU ID">
                            <span id="review-id-number"></span>
                        </x-forms.review-item>
                        <x-forms.review-item label="Type">
                            <span id="review-type"></span>
                        </x-forms.review-item>
                        <x-forms.review-item label="Department">
                            <span id="review-department"></span> </x-forms.review-item>
                        <x-forms.review-item label="Position">
                            <span id="review-position"></span>
                        </x-forms.review-item>
                        <x-forms.review-item label="Home Address">
                            <span id="review-address"></span> </x-forms.review-item>
                    </x-forms.review-section>
                    <!-- Vehicle Information -->
                    <x-forms.review-section title="Vehicle Information"> <x-forms.review-item label="Vehicle Type">
                            <span id="review-vehicle-type"></span>
                        </x-forms.review-item>
                        <x-forms.review-item label="Plate Number">
                            <span id="review-plate-number"></span>
                        </x-forms.review-item> <x-forms.review-item label="Make">
                            <span id="review-make"></span>
                        </x-forms.review-item> <x-forms.review-item label="Model">
                            <span id="review-model"></span>
                        </x-forms.review-item> <x-forms.review-item label="Year">
                            <span id="review-year"></span> </x-forms.review-item>
                        <x-forms.review-item label="Color"><span id="review-color"></span> </x-forms.review-item>
                    </x-forms.review-section>
                    <div id="review-documents-container">
                    </div> <!-- Purpose of Application -->
                    <x-forms.textarea-field
                        id="purpose" name="purpose"
                        label="Purpose of Application"
                        placeholder="Briefly describe why you need an RFID gate pass"
                        :required="true" />
                    <!-- Additional Comments -->
                    <x-forms.textarea-field
                        id="comments"
                        name="comments"
                        label="Additional Comments (Optional)"
                        placeholder="Any additional information you'd like to provide" />
                </x-forms.application-review>

                <div class="flex justify-between mt-6">
                    <button type="button" class="prev-btn
                inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium
                        focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring
                       disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm     hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 transition-all duration-300
                  transform hover:-translate-x-1" data-prev="3"> Back: Documents </button>
                    <button type="submit" id="submitApplication" class="     inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium     focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring
    disabled:pointer-events-none disabled:opacity-50 text-white shadow
    h-9 px-4 py-2 bg-green-700 hover:bg-green-800 transition-all duration-300     transform hover:translate-x-1">
                        Submit Application </button>
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const steps = document.querySelectorAll('.form-step');
            const tabButtons = document.querySelectorAll('[data-step]');
            let uploadedFiles = [];

            const allowedTypes = ["application/pdf", "image/jpeg", "image/png", "image/jpg"];

            // Handle next button clicks
            document.querySelectorAll('.next-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const nextStep = this.dataset.next;

                    // Validate current step before proceeding
                    const currentStep = this.closest('.form-step').id.split('-')[1];
                    if (validateStep(currentStep)) {
                        // Hide all steps
                        steps.forEach(step => step.classList.add('hidden'));
                        // Show the target step
                        document.getElementById(`step-${nextStep}`).classList.remove('hidden');
                        // Update tab status
                        updateTabs(nextStep);
                        // If this is the last step (review), populate review fields
                        if (nextStep === '4') {
                            populateReviewFields();
                        }
                    }
                });
            });

            // Handle prev button clicks
            document.querySelectorAll('.prev-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const prevStep = this.dataset.prev;
                    // Hide all steps
                    steps.forEach(step => step.classList.add('hidden'));

                    // Show the target step
                    document.getElementById(`step-${prevStep}`).classList.remove('hidden');
                    // Update tab status
                    updateTabs(prevStep);
                });
            });

            // Function to update tab status
            function updateTabs(activeStep) {
                tabButtons.forEach(tab => {
                    const step = tab.getAttribute('data-step');

                    // Remove active class from all tabs
                    tab.setAttribute('aria-selected', 'false');
                    tab.classList.remove('bg-green-100', 'text-green-900');
                    tab.classList.add('text-muted-foreground');

                    // Add active class to the current tab
                    if (step === activeStep) {
                        tab.setAttribute('aria-selected', 'true');
                        tab.classList.add('bg-green-100', 'text-green-900');
                        tab.classList.remove('text-muted-foreground');
                    }
                });
            }

            // Validate each step
            function validateStep(step) {
                const currentStep = document.getElementById(`step-${step}`);
                const requiredFields = currentStep.querySelectorAll('[required]');
                let isValid = true;
                let firstInvalidField = null;

                requiredFields.forEach(field => {
                    if (!field.value) {
                        isValid = false;
                        if (!firstInvalidField) firstInvalidField = field;
                        field.classList.add('border-red-500');
                        // Add shake animation for invalid fields
                        field.classList.add('animate-shake');
                        setTimeout(() => {
                            field.classList.remove('animate-shake');
                        }, 500);
                    } else {
                        field.classList.remove('border-red-500');
                    }
                });

                // Additional validations based on step
                if (step === '1' && isValid) {
                    // Email validation
                    const emailInput = document.getElementById('email');
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(emailInput.value.trim())) {
                        isValid = false;
                        emailInput.classList.add('border-red-500');
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid Email',
                            text: 'Please enter a valid email address.',
                            confirmButtonColor: '#3085d6'
                        });
                        return false;
                    }

                    // Phone validation
                    const phoneInput = document.getElementById('phone');
                    const phoneRegex = /^[0-9+\-\s]{7,15}$/;
                    if (!phoneRegex.test(phoneInput.value.trim())) {
                        isValid = false;
                        phoneInput.classList.add('border-red-500');
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid Phone Number',
                            text: 'Please enter a valid phone number.',
                            confirmButtonColor: '#3085d6'
                        });
                        return false;
                    }
                } else if (step === '2' && isValid) {
                    // Plate number validation
                    const plateNumberInput = document.getElementById('plateNumber');
                    const plateNumberValue = plateNumberInput.value.trim();
                    const plateNumberRegex = /^[A-Za-z0-9]{3,4}[\s-]?[0-9]{3,4}$/;
                    if (!plateNumberRegex.test(plateNumberValue)) {
                        isValid = false;
                        plateNumberInput.classList.add('border-red-500');
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid Plate Number',
                            text: 'Please enter a valid plate number format (e.g., ABC-1234).',
                            confirmButtonColor: '#3085d6'
                        });
                        return false;
                    }
                } else if (step === '3' && isValid) {
                    let documentsValid = true;
                    let errorMessage = '';
                    const applicantType = document.getElementById('applicantType').value;
                    // OR/CR validation (always required)
                    if (uploadedFiles.length === 0) {
                        documentsValid = false;
                        errorMessage = 'Please upload at least one OR/CR document to proceed.';
                        const dropZone = document.getElementById('dropZone');
                        dropZone.classList.add('border-red-500');
                        dropZone.classList.remove('border-gray-300');
                    }
                    // Student or Other
                    if (applicantType === 'student') {
                        const idPhotoInput = document.getElementById('id_photo');
                        if (idPhotoInput.files.length === 0) {
                            documentsValid = false;
                            errorMessage = errorMessage || 'Please upload your CLSU ID photo.';
                            idPhotoInput.classList.add('border-red-500');
                        }
                        const driversLicenseInput = document.getElementById('drivers_license');
                        if (driversLicenseInput.files.length === 0) {
                            documentsValid = false;
                            errorMessage = errorMessage || 'Please upload your Driver\'s License.';
                            driversLicenseInput.classList.add('border-red-500');
                        }
                        const allFiles = [
                            ...Array.from(idPhotoInput.files),
                            ...Array.from(driversLicenseInput.files),
                            ...uploadedFiles
                        ];
                        for (let file of allFiles) {
                            if (!allowedTypes.includes(file.type)) {
                                isValid = false;
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Invalid File Format',
                                    text: `File "${file.name}" is not a valid format. Allowed: PDF, JPG, JPEG, PNG.`,
                                    confirmButtonColor: '#3085d6'
                                });
                                return false;
                            }
                            if (file.size > 5 * 1024 * 1024) {
                                isValid = false;
                                Swal.fire({
                                    icon: 'error',
                                    title: 'File Too Large',
                                    text: `File "${file.name}" exceeds the 5MB limit.`,
                                    confirmButtonColor: '#3085d6'
                                });
                                return false;
                            }
                        }
                    } else {
                        const govIdInput = document.getElementById('gov_id');
                        const vehicleRegInput = document.getElementById('vehicle_reg');
                        if (govIdInput.files.length === 0) {
                            documentsValid = false;
                            errorMessage = errorMessage || 'Please upload your Government ID.';
                            govIdInput.classList.add('border-red-500');
                        }
                        if (vehicleRegInput.files.length === 0) {
                            documentsValid = false;
                            errorMessage = errorMessage || 'Please upload your Vehicle Registration.';
                            vehicleRegInput.classList.add('border-red-500');
                        }
                        const allFiles = [
                            ...Array.from(govIdInput.files),
                            ...Array.from(vehicleRegInput.files),
                            ...uploadedFiles
                        ];
                        for (let file of allFiles) {
                            if (!allowedTypes.includes(file.type)) {
                                isValid = false;
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Invalid File Format',
                                    text: `File "${file.name}" is not a valid format. Allowed: PDF, JPG, JPEG, PNG.`,
                                    confirmButtonColor: '#3085d6'
                                });
                                return false;
                            }
                            if (file.size > 5 * 1024 * 1024) {
                                isValid = false;
                                Swal.fire({
                                    icon: 'error',
                                    title: 'File Too Large',
                                    text: `File "${file.name}" exceeds the 5MB limit.`,
                                    confirmButtonColor: '#3085d6'
                                });
                                return false;
                            }
                        }
                    }
                    if (!documentsValid) {
                        isValid = false;
                        Swal.fire({
                            icon: 'error',
                            title: 'Document Uploads Incomplete',
                            text: errorMessage || 'Please upload all required documents before proceeding.',
                            confirmButtonColor: '#3085d6'
                        });
                        return false;
                    }
                }

                if (!isValid) {
                    if (firstInvalidField) firstInvalidField.focus();
                    Swal.fire({
                        icon: 'error',
                        title: 'Required Fields Empty',
                        text: 'Please fill in all required fields before proceeding.',
                        confirmButtonColor: '#3085d6'
                    });
                }
                return isValid;
            }

            // Populate review fields with form data
            function populateReviewFields() {
                document.getElementById('review-name').textContent = document.getElementById('firstName').value;
                document.getElementById('review-email').textContent = document.getElementById('email').value;
                document.getElementById('review-phone').textContent = document.getElementById('phone').value;
                document.getElementById('review-id-number').textContent = document.getElementById('id_number').value;
                const applicantTypeSelect = document.getElementById('type');
                document.getElementById('review-type').textContent = applicantTypeSelect.options[applicantTypeSelect.selectedIndex].text;
                document.getElementById('review-address').textContent = document.getElementById('lastName').value;
                document.getElementById('review-department').textContent = document.getElementById('department').value;
                document.getElementById('review-position').textContent = document.getElementById('position').value;

                document.getElementById('review-vehicle-type').textContent = document.getElementById('vehicleType').options[document.getElementById('vehicleType').selectedIndex].text;
                document.getElementById('review-plate-number').textContent = document.getElementById('plateNumber').value;
                document.getElementById('review-make').textContent = document.getElementById('make').value;
                document.getElementById('review-model').textContent = document.getElementById('model').value;
                document.getElementById('review-year').textContent = document.getElementById('year').value;
                document.getElementById('review-color').textContent = document.getElementById('color').value;

                // Update documents section
                const applicantType = applicantTypeSelect.value;
                let docCount = window.uploadedFiles.length;
                const documentsList = [];
                // OR/CR files
                window.uploadedFiles.forEach(file => {
                    documentsList.push('OR/CR Document (' + file.name + ')');
                });
                // Student or Other
                if (applicantType === 'student') {
                    const idPhotoInput = document.getElementById('id_photo');
                    if (idPhotoInput.files.length > 0) {
                        documentsList.push('CLSU ID Photo (' + idPhotoInput.files[0].name + ')');
                        docCount++;
                    }
                    const driversLicenseInput = document.getElementById('drivers_license');
                    if (driversLicenseInput.files.length > 0) {
                        documentsList.push('Driver\'s License (' + driversLicenseInput.files[0].name + ')');
                        docCount++;
                    }
                } else {
                    const govIdInput = document.getElementById('gov_id');
                    if (govIdInput.files.length > 0) {
                        documentsList.push('Government ID (' + govIdInput.files[0].name + ')');
                        docCount++;
                    }
                    const vehicleRegInput = document.getElementById('vehicle_reg');
                    if (vehicleRegInput.files.length > 0) {
                        documentsList.push('Vehicle Registration (' + vehicleRegInput.files[0].name + ')');
                        docCount++;
                    }
                }
                // Create documents component content
                const docsContainer = document.getElementById('review-documents-container');
                docsContainer.innerHTML = `
        <div>
            <h4 class="font-medium text-green-700 mb-2">Documents</h4>
            <p class="text-sm">${docCount} document(s) uploaded</p>
           <ul class="list-disc list-inside text-sm ml-2">
                ${documentsList.map(doc => `<li>${doc}</li>`).join('')}
            </ul>
        </div>
        `;
            }

            // File upload handling for OR/CR
            const fileInput = document.getElementById("fileInput");
            const dropZone = document.getElementById("dropZone");
            const fileList = document.getElementById("fileList");
            const fileCount = document.getElementById("fileCount");

            // Handle click on dropZone
            dropZone.addEventListener("click", () => fileInput.click());

            // Handle file selection
            fileInput.addEventListener("change", function(event) {
                handleFiles(event.target.files);
            });

            // Drag and drop support
            dropZone.addEventListener("dragover", (event) => {
                event.preventDefault();
                dropZone.classList.add("bg-gray-100");
            });

            dropZone.addEventListener("dragleave", () => {
                dropZone.classList.remove("bg-gray-100");
            });

            dropZone.addEventListener("drop", (event) => {
                event.preventDefault();
                dropZone.classList.remove("bg-gray-100");
                handleFiles(event.dataTransfer.files);
            });

            function handleFiles(files) {
                for (let file of files) {
                    if (!allowedTypes.includes(file.type)) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid File Format',
                            text: `File "${file.name}" is not a valid format. Allowed: PDF, JPG, JPEG, PNG.`,
                            confirmButtonColor: '#3085d6'
                        });
                        continue;
                    }
                    if (file.size > 5 * 1024 * 1024) {
                        Swal.fire({
                            icon: 'error',
                            title: 'File Too Large',
                            text: `File "${file.name}" exceeds the 5MB limit.`,
                            confirmButtonColor: '#3085d6'
                        });
                        continue;
                    }
                    if (uploadedFiles.length >= 2) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Maximum Files Reached',
                            text: 'Only 2 files can be uploaded.',
                            confirmButtonColor: '#3085d6'
                        });
                        return;
                    }
                    uploadedFiles.push(file);
                    displayFiles();
                }
            }

            function displayFiles() {
                fileList.innerHTML = "";
                uploadedFiles.forEach((file, index) => {
                    const listItem = document.createElement("li");
                    listItem.classList.add("flex", "justify-between", "items-center", "bg-gray-100", "p-2", "rounded-md");
                    listItem.innerHTML = `
                <span>${file.name} (${(file.size / 1024).toFixed(1)} KB)</span>
                <button type="button" class="text-red-600 text-xs remove-file" data-index="${index}">Remove</button>
            `;
                    fileList.appendChild(listItem);
                });

                fileCount.textContent = `${uploadedFiles.length}/2 files uploaded`;

                // Add event listeners to newly created remove buttons
                document.querySelectorAll('.remove-file').forEach(button => {
                    button.addEventListener('click', function() {
                        removeFile(parseInt(this.getAttribute('data-index')));
                    });
                });

                // Update dropzone visual state
                if (uploadedFiles.length > 0) {
                    dropZone.classList.add('border-green-500');
                    dropZone.classList.remove('border-gray-300', 'border-red-500');
                } else {
                    dropZone.classList.remove('border-green-500');
                    dropZone.classList.add('border-gray-300');
                }
            }

            function removeFile(index) {
                uploadedFiles.splice(index, 1);
                displayFiles();
            }

            // Form submission
            document.getElementById('submitApplication').addEventListener('click', function(e) {
                e.preventDefault();

                // Validate purpose field
                const purposeField = document.getElementById('purpose');
                const purposeValue = purposeField.value.trim();
                if (!purposeValue) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Purpose Required',
                        text: 'Please provide the purpose of your application before submitting.',
                        confirmButtonColor: '#3085d6'
                    });
                    purposeField.classList.add('border-red-500');
                    purposeField.focus();
                    return;
                } else if (purposeValue.length < 10) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Purpose Too Brief',
                        text: 'Please provide a more detailed purpose for your application (at least 10 characters).',
                        confirmButtonColor: '#3085d6'
                    });
                    purposeField.classList.add('border-red-500');
                    purposeField.focus();
                    return;
                }

                // Show confirmation dialog before submitting
                Swal.fire({
                    icon: 'question',
                    title: 'Submit Application?',
                    text: 'Please review all your information before submitting. Once submitted, you cannot make changes to this application.',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, submit application',
                    cancelButtonText: 'No, I need to review',
                    confirmButtonColor: '#43A047',
                    cancelButtonColor: '#d33'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Sync uploadedFiles to the file input
                        const dataTransfer = new DataTransfer();
                        uploadedFiles.forEach(file => dataTransfer.items.add(file));
                        fileInput.files = dataTransfer.files;
                        // Show success message before submitting
                        Swal.fire({
                            icon: 'success',
                            title: 'Application Submitted!',
                            text: 'Your RFID gate pass application has been submitted successfully.',
                            confirmButtonColor: '#43A047'
                        }).then(() => {
                            // Submit the form
                            const form = document.getElementById('rfidApplicationForm');
                            form.submit();
                        });
                    }
                });
            });

            // Add keyframes for shake animation if not already defined in your CSS
            if (!document.querySelector('style#shake-animation')) {
                const styleElement = document.createElement('style');
                styleElement.id = 'shake-animation';
                styleElement.textContent = `
            @keyframes shake {
                0% { transform: translateX(0); }
                25% { transform: translateX(8px); }
                50% { transform: translateX(-8px); }
                75% { transform: translateX(8px); }
                100% { transform: translateX(0); }
            }
            
            .animate-shake {
                animation: shake 0.82s cubic-bezier(.36,.07,.19,.97) both;
            }
        `;
                document.head.appendChild(styleElement);
            }

            // Remove error styling when user interacts with fields
            document.querySelectorAll('input, select, textarea').forEach(field => {
                field.addEventListener('input', function() {
                    this.classList.remove('border-red-500');
                });
                if (field.tagName === 'SELECT') {
                    field.addEventListener('change', function() {
                        this.classList.remove('border-red-500');
                    });
                }
            });

            // Add event listeners to remove validation styling when files are selected
            document.getElementById('id_photo').addEventListener('change', function() {
                this.classList.remove('border-red-500');
            });

            document.getElementById('drivers_license').addEventListener('change', function() {
                this.classList.remove('border-red-500');
            });

            // Applicant type logic
            const applicantTypeSelect = document.getElementById('applicantType');
            const studentSection = document.getElementById('student-documents-section');
            const otherSection = document.getElementById('other-documents-section');
            applicantTypeSelect.addEventListener('change', function() {
                if (this.value === 'student') {
                    studentSection.style.display = '';
                    otherSection.style.display = 'none';
                    // Set required attributes
                    document.getElementById('id_photo').setAttribute('required', 'required');
                    document.getElementById('drivers_license').setAttribute('required', 'required');
                    document.getElementById('gov_id').removeAttribute('required');
                    document.getElementById('vehicle_reg').removeAttribute('required');
                } else {
                    studentSection.style.display = 'none';
                    otherSection.style.display = '';
                    // Set required attributes
                    document.getElementById('id_photo').removeAttribute('required');
                    document.getElementById('drivers_license').removeAttribute('required');
                    document.getElementById('gov_id').setAttribute('required', 'required');
                    document.getElementById('vehicle_reg').setAttribute('required', 'required');
                }
            });
            // Set initial state
            if (applicantTypeSelect.value === 'student') {
                studentSection.style.display = '';
                otherSection.style.display = 'none';
                document.getElementById('id_photo').setAttribute('required', 'required');
                document.getElementById('drivers_license').setAttribute('required', 'required');
                document.getElementById('gov_id').removeAttribute('required');
                document.getElementById('vehicle_reg').removeAttribute('required');
            } else {
                studentSection.style.display = 'none';
                otherSection.style.display = '';
                document.getElementById('id_photo').removeAttribute('required');
                document.getElementById('drivers_license').removeAttribute('required');
                document.getElementById('gov_id').setAttribute('required', 'required');
                document.getElementById('vehicle_reg').setAttribute('required', 'required');
            }

            // Remove error styling when user interacts with new fields
            if (document.getElementById('gov_id')) {
                document.getElementById('gov_id').addEventListener('change', function() {
                    this.classList.remove('border-red-500');
                });
            }
            if (document.getElementById('vehicle_reg')) {
                document.getElementById('vehicle_reg').addEventListener('change', function() {
                    this.classList.remove('border-red-500');
                });
            }
        });
    </script>
</x-layout>