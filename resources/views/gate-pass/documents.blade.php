{{-- Document Upload Partial --}}
<div class="rounded-xl border bg-card text-card-foreground shadow p-6 transition-all duration-300 hover:shadow-md">
    <h3 class="text-lg font-medium mb-4">Document Upload</h3>
    <p class="text-sm text-gray-500 mb-4">
        Please select your applicant type and upload the required documents. Requirements change based on your selection.
    </p>
    <div class="mb-4">
        <label for="applicantType" class="block text-sm font-medium text-gray-700 mb-1">Applicant Type <span class="text-red-500">*</span></label>
        <select id="applicantType" name="applicantType" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50" required>
            <option value="student">Student</option>
            <option value="faculty">Faculty</option>
            <option value="staff">Staff</option>
            <option value="visitor">Visitor</option>
        </select>
    </div>
    <div class="mb-4">
        <h3 class="text-lg font-medium text-gray-900">OR/CR Document Upload</h3>
        <p class="text-sm text-gray-500">
            Upload your Official Receipt (OR) and Certificate of Registration (CR) documents </p>
    </div>
    <div class="mb-4">
        <input type="file" id="fileInput" class="hidden" name="documents[]" accept=".pdf,.jpg,.jpeg,.png" multiple>
        <div id="dropZone" class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center cursor-pointer hover:bg-gray-50 transition-colors">
            <div class="flex flex-col items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-upload h-10 w-10 text-green-600">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="17 8 12 3 7 8"></polyline>
                    <line x1="12" x2="12" y1="3" y2="15"></line>
                </svg>
                <p class="text-sm font-medium">Click or Drag & Drop to upload OR/CR documents</p>
                <p class="text-xs text-gray-500">PDF, JPG, JPEG, PNG up to 5MB each</p>
                <p class="text-xs text-gray-500" id="fileCount">0/2 files uploaded</p>
            </div>
        </div>
        <ul id="fileList" class="mt-4 text-sm text-gray-700 space-y-2"></ul>
    </div>
    <div id="student-documents-section">
        <div class="space-y-2 mt-6">
            <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="id_photo">
                CLSU ID Photo <span class="text-red-500">*</span> </label>
            <input type="file"
                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100"
                id="id_photo" name="id_photo" required>
            <p class="text-xs text-gray-500">Clear image of your CLSU ID card</p>
        </div>
        <div class="space-y-2 mt-4">
            <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="drivers_license">
                Driver's License <span class="text-red-500">*</span>
            </label> <input type="file"
                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100" id="drivers_license" name="drivers_license" required>
            <p class="text-xs text-gray-500">Valid driver's license</p>
        </div>
    </div>
    <div id="other-documents-section" style="display:none;">
        <div class="space-y-2 mt-4">
            <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="gov_id">
                Government ID <span class="text-red-500">*</span>
            </label>
            <input type="file"
                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100"
                id="gov_id" name="gov_id">
            <p class="text-xs text-gray-500">Upload a valid government-issued ID (PDF, JPG, JPEG, PNG, max 5MB)</p>
        </div>
        <div class="space-y-2 mt-4">
            <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="vehicle_reg">
                Vehicle Registration <span class="text-red-500">*</span>
            </label>
            <input type="file"
                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100"
                id="vehicle_reg" name="vehicle_reg">
            <p class="text-xs text-gray-500">Upload your vehicle registration document (PDF, JPG, JPEG, PNG, max 5MB)</p>
        </div>
    </div>
</div>
<script>
// Document upload logic for the partial
// This script assumes only one instance per page. If you need multiple, use unique IDs or data attributes.
window.uploadedFiles = window.uploadedFiles || [];
let uploadedFiles = window.uploadedFiles;
const allowedTypes = ["application/pdf", "image/jpeg", "image/png", "image/jpg"];

document.addEventListener('DOMContentLoaded', function() {
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
            // Prevent duplicates by name and size
            if (uploadedFiles.some(f => f.name === file.name && f.size === file.size)) {
                continue;
            }
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
        }
        displayFiles();
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
        document.querySelectorAll('.remove-file').forEach(button => {
            button.addEventListener('click', function() {
                removeFile(parseInt(this.getAttribute('data-index')));
            });
        });
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

    // Applicant type logic
    const applicantTypeSelect = document.getElementById('applicantType');
    const studentSection = document.getElementById('student-documents-section');
    const otherSection = document.getElementById('other-documents-section');
    applicantTypeSelect.addEventListener('change', function() {
        if (this.value === 'student') {
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
});
</script>