<x-layout>
    <form id="vehicleInfoForm" onsubmit="return validateForm(event)">
        <div data-state="active" data-orientation="horizontal" role="tabpanel" aria-labelledby="radix-:r7:-trigger-vehicle" id="radix-:r7:-content-vehicle" tabindex="0" class="mt-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 space-y-6">
            <x-forms.tab-navigation activeTab="vehicle" />
            <x-forms.form-section title="Vehicle Information">
                <x-forms.form-field 
                    label="Vehicle Type"
                    id="vehicleType" 
                    name="vehicleType" 
                    type="select"
                    required
                >
                    <option value="" disabled selected>Select vehicle type</option>
                    <option value="car">Car</option>
                    <option value="motorcycle">Motorcycle</option>
                    <option value="truck">Truck</option>
                    <option value="van">Van</option>
                    <option value="suv">SUV</option>
                </x-forms.form-field>
                
                <x-forms.form-field 
                    label="Make"
                    id="make" 
                    name="make" 
                    placeholder="E.g., Toyota, Honda, Ford, Nissan"
                    required
                />
                
                <x-forms.form-field 
                    label="Model"
                    id="model" 
                    name="model" 
                    placeholder="E.g., Civic, Corolla, Mustang, Fortuner"
                    required
                />
                
                <x-forms.form-field 
                    label="Color"
                    id="color" 
                    name="color" 
                    placeholder="E.g., Red, Blue, Black, White"
                    required
                />
                
                <x-forms.form-field 
                    label="Plate Number"
                    id="plateNumber" 
                    name="plateNumber" 
                    placeholder="E.g., ABC-1234"
                    required
                />
            </x-forms.form-section>
            <x-forms.form-buttons 
                :prevRoute="route('rfid.personal-info')"
                prevText="Back: Personal Information"
                :nextRoute="route('rfid.documents')"
                nextText="Next: Documents"
            />
        </div>
    </form>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Find all next buttons or links
            const nextButtons = document.querySelectorAll('a[href="' + "{{ route('rfid.documents') }}" + '"], button[type="submit"], .next-button');
            
            nextButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    validateForm(e);
                });
            });
            
            // Remove error styling when user types in field or changes select
            const form = document.getElementById('vehicleInfoForm');
            form.querySelectorAll('input, select').forEach(input => {
                input.addEventListener('input', function() {
                    this.classList.remove('border-red-500');
                });
                if (input.tagName === 'SELECT') {
                    input.addEventListener('change', function() {
                        this.classList.remove('border-red-500');
                    });
                }
            });
        });
        
        function validateForm(e) {
            e.preventDefault();
            const form = document.getElementById('vehicleInfoForm');
            
            // Get all required inputs and selects
            const requiredInputs = form.querySelectorAll('[required]');
            let hasEmptyFields = false;
            let firstEmptyField = null;
            
            // Check if all required fields are filled
            requiredInputs.forEach(input => {
                if (!input.value || input.value.trim() === '') {
                    hasEmptyFields = true;
                    if (!firstEmptyField) firstEmptyField = input;
                    input.classList.add('border-red-500');
                } else {
                    input.classList.remove('border-red-500');
                }
            });
            
            // Validate plate number format (simple validation)
            const plateNumberInput = document.getElementById('plateNumber');
            const plateNumberValue = plateNumberInput.value.trim();
            // Allow formats like: ABC-1234, ABC 1234, ABC1234, etc.
            const plateNumberRegex = /^[A-Za-z0-9]{3,4}[\s-]?[0-9]{3,4}$/;
            const isPlateNumberValid = plateNumberRegex.test(plateNumberValue);
            
            if (hasEmptyFields) {
                // Show error alert for empty fields
                Swal.fire({
                    icon: 'error',
                    title: 'Required Fields Empty',
                    text: 'Please fill in all required fields to proceed.',
                    confirmButtonColor: '#3085d6'
                });
                
                // Focus on the first empty field
                if (firstEmptyField) firstEmptyField.focus();
                return false;
            } else if (plateNumberValue && !isPlateNumberValid) {
                // Show error for invalid plate number format
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Plate Number',
                    text: 'Please enter a valid plate number format (e.g., ABC-1234).',
                    confirmButtonColor: '#3085d6'
                });
                plateNumberInput.classList.add('border-red-500');
                plateNumberInput.focus();
                return false;
            } else {
                // All validations passed, proceed to next page
                window.location.href = "{{ route('rfid.documents') }}";
                return true;
            }
        }
    </script>
</x-layout>