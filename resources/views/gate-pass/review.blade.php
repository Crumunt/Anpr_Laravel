<x-layout>
    <form method="POST" id="reviewForm">
        @csrf
        <div class="mt-2 space-y-6">
            <x-forms.tab-navigation activeTab="review" />
            <x-forms.application-review>
                <!-- Personal Information -->
                <x-forms.review-section title="Personal Information">
                    <x-forms.review-item label="Name" />
                    <x-forms.review-item label="Home Address" />
                    <x-forms.review-item label="College/Unit/Department" />
                    <x-forms.review-item label="Designation/Position" />
                    <x-forms.review-item label="Contact Number" />
                    <x-forms.review-item label="Email Address" />
                </x-forms.review-section>
                
                <!-- Vehicle Information -->
                <x-forms.review-section title="Vehicle Information">
                    <x-forms.review-item label="Vehicle Type">Car</x-forms.review-item>
                    <x-forms.review-item label="Make" />
                    <x-forms.review-item label="Model" />
                    <x-forms.review-item label="Color" />
                    <x-forms.review-item label="Plate Number" />
                </x-forms.review-section>
                
                <!-- Documents Section -->
                <x-forms.document-list :documents="['477340999_2005457919959994_1949826297934370334_n.jpg']" />
                
                <!-- Purpose of Application -->
                <x-forms.textarea-field
                    id="purpose"
                    name="purpose"
                    label="Purpose of Application"
                    placeholder="Briefly describe why you need an RFID gate pass"
                    required="true"
                />
                
                <!-- Additional Comments -->
                <x-forms.textarea-field
                    id="comments"
                    name="comments"
                    label="Additional Comments (Optional)"
                    placeholder="Any additional information you'd like to provide"
                />
            </x-forms.application-review>
            
            <!-- Replace the buttons component with our own implementation -->
            <div class="flex justify-between mt-6">
                <a href="{{ route('rfid.documents') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Back: Documents
                </a>
                <button type="button" id="customSubmitButton" class="px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Submit Application
                </button>
            </div>
        </div>
    </form>

    
    <script>
        // Function to validate and submit the form
        function validateAndSubmit() {
            const purposeField = document.getElementById('purpose');
            if (!purposeField) {
                console.error('Purpose field not found!');
                return;
            }
            
            const purposeValue = purposeField.value.trim();
            
            if (!purposeValue) {
                // Purpose field is empty
                Swal.fire({
                    icon: 'error',
                    title: 'Purpose Required',
                    text: 'Please provide the purpose of your application before submitting.',
                    confirmButtonColor: '#3085d6'
                });
                
                // Highlight the purpose field
                purposeField.classList.add('border-red-500');
                purposeField.focus();
            } else if (purposeValue.length < 10) {
                // Purpose is too short
                Swal.fire({
                    icon: 'warning',
                    title: 'Purpose Too Brief',
                    text: 'Please provide a more detailed purpose for your application (at least 10 characters).',
                    confirmButtonColor: '#3085d6'
                });
                
                purposeField.classList.add('border-red-500');
                purposeField.focus();
            } else {
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
                        // Show success message before submitting
                        Swal.fire({
                            icon: 'success',
                            title: 'Application Submitted!',
                            text: 'Your RFID gate pass application has been submitted successfully.',
                            confirmButtonColor: '#43A047'
                        }).then(() => {
                            // Actually submit the form
                            document.getElementById('reviewForm').submit();
                        });
                    }
                });
            }
        }

        // Immediate execution code
        (function() {
            console.log('Script loaded and running');
            
            // Attempt 1: Regular DOM ready event
            document.addEventListener('DOMContentLoaded', function() {
                console.log('DOM content loaded');
                attachEventHandlers();
            });
            
            // Attempt 2: Run now in case DOMContentLoaded has already fired
            attachEventHandlers();
            
            // Attempt 3: Last resort - wait a bit and try again
            setTimeout(attachEventHandlers, 500);
            
            function attachEventHandlers() {
                // Try to get our custom button
                const submitBtn = document.getElementById('customSubmitButton');
                if (submitBtn) {
                    console.log('Submit button found, attaching event');
                    submitBtn.addEventListener('click', function(e) {
                        console.log('Submit button clicked');
                        e.preventDefault();
                        validateAndSubmit();
                    });
                }
                
                // Also try to catch the form submission
                const form = document.getElementById('reviewForm');
                if (form) {
                    console.log('Form found, attaching event');
                    form.addEventListener('submit', function(e) {
                        console.log('Form submission intercepted');
                        e.preventDefault();
                        validateAndSubmit();
                    });
                }
                
                // Also try to find any submit buttons in the document
                const allSubmitButtons = document.querySelectorAll('button[type="submit"], input[type="submit"]');
                allSubmitButtons.forEach(btn => {
                    console.log('Found a submit button, attaching event');
                    btn.addEventListener('click', function(e) {
                        console.log('Submit button clicked');
                        e.preventDefault();
                        validateAndSubmit();
                    });
                });
                
                // Remove error styling when user types in textarea
                const purpose = document.getElementById('purpose');
                if (purpose) {
                    purpose.addEventListener('input', function() {
                        this.classList.remove('border-red-500');
                    });
                }
            }
            
            // Display uploaded files in review
            function displayUploadedFilesInReview() {
                const files = JSON.parse(localStorage.getItem('rfid_uploaded_files') || '[]');
                const docList = document.getElementById('review-other-documents');
                if (docList) {
                    docList.innerHTML = '';
                    files.forEach(file => {
                        docList.innerHTML += `<li>${file.name} (${(file.size/1024).toFixed(1)} KB)</li>`;
                    });
                }
            }
            document.addEventListener('DOMContentLoaded', displayUploadedFilesInReview);
        })();

        // Test alert to verify the script is loading
        window.addEventListener('load', function() {
            console.log('Window loaded completely');
            
            // Uncomment the line below to test if SweetAlert is working
            // Swal.fire('Script Loaded', 'The validation script has loaded successfully.', 'info');
        });
    </script>
</x-layout>