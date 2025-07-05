<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-12">
                <h1 class="text-3xl font-bold text-green-800 mb-2">CLSU RFID Gate Pass Application</h1>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Apply for your Central Luzon State University RFID gate pass to enjoy convenient campus access for your vehicle.
                </p>
            </div>

            <div class="max-w-5xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                    <div class="rounded-xl border shadow p-6 flex flex-col items-center text-center bg-white">
                        <div class="h-16 w-16 rounded-full bg-green-100 flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-8 w-8 text-green-600">
                                <rect width="20" height="14" x="2" y="5" rx="2"></rect>
                                <line x1="2" x2="22" y1="10" y2="10"></line>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium mb-2">Easy Registration</h3>
                        <p class="text-gray-500 text-sm">
                            Complete a simple application form with your personal and vehicle information.
                        </p>
                    </div>

                    <div class="rounded-xl border shadow p-6 flex flex-col items-center text-center bg-white">
                        <div class="h-16 w-16 rounded-full bg-green-100 flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-8 w-8 text-green-600">
                                <path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"></path>
                                <circle cx="7" cy="17" r="2"></circle>
                                <path d="M9 17h6"></path>
                                <circle cx="17" cy="17" r="2"></circle>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium mb-2">Vehicle Documentation</h3>
                        <p class="text-gray-500 text-sm">
                            Upload your OR/CR documents to verify your vehicle ownership and registration.
                        </p>
                    </div>

                    <div class="rounded-xl border shadow p-6 flex flex-col items-center text-center bg-white">
                        <div class="h-16 w-16 rounded-full bg-green-100 flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-8 w-8 text-green-600">
                                <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"></path>
                                <path d="m9 12 2 2 4-4"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium mb-2">Secure Access</h3>
                        <p class="text-gray-500 text-sm">
                            Receive your RFID tag for convenient and secure campus entry and exit.
                        </p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <h2 class="text-xl font-semibold text-green-800 mb-4">Application Process</h2>
                    <ol class="list-decimal list-inside space-y-4 text-gray-700">
                        <li class="pl-2">
                            <span class="font-medium">Review and accept</span> the RFID Gate Pass User Agreement
                        </li>
                        <li class="pl-2">
                            <span class="font-medium">Complete the application form</span> with your personal details and vehicle information
                        </li>
                        <li class="pl-2">
                            <span class="font-medium">Upload required documents</span> including your OR/CR for verification
                        </li>
                        <li class="pl-2">
                            <span class="font-medium">Submit your application</span> and receive a reference number
                        </li>
                        <li class="pl-2">
                            <span class="font-medium">Wait for approval</span> and notification when your RFID tag is ready for pickup
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
<!-- Terms Modal -->
<div id="termsModal" class="fixed inset-0 z-50 bg-black bg-opacity-70 flex items-center justify-center p-4 sm:p-6">
    <div class="bg-white rounded-lg p-4 sm:p-6 w-full max-w-[95%] sm:max-w-6xl mx-auto h-[90vh] flex flex-col">   <!-- Header -->
        <div class="flex items-start justify-between mb-3">
            <div class="flex flex-col sm:flex-row sm:items-center space-y-1 sm:space-y-0 sm:space-x-3">
                <div class="bg-green-100 p-2 rounded-full w-fit mb-1 sm:mb-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 sm:h-8 sm:w-8 text-green-600" 
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-800 leading-tight">CLSU RFID Gate Pass User Agreement</h2>
                    <p class="text-gray-600 mt-1 text-sm sm:text-base">Read and accept terms to proceed</p>
                </div>
            </div>
            <button id="closeModal" class="text-gray-500 hover:text-gray-700 text-2xl sm:text-3xl ml-2">
                &times;
            </button>
        </div>

        <!-- Terms Content -->
        <div class="flex-1 overflow-y-auto border rounded-lg bg-gray-50 p-4 mt-3 text-sm sm:text-base">
            
            <div class="h-96 overflow-y-auto rounded-lg border p-4">
                <div class="space-y-4 text-sm text-gray-700">
                  <p class="font-medium">Terms and Conditions</p>
                  <p>Should my application for CLSU vehicle sticker be approved, I fully understand and agree to undertake the following terms and conditions:</p>
              
                  <ol class=" pl-5 space-y-2">
                    <li>
                      <span class="font-medium">1.</span> I fully understand the content of this application and express my consent for CLSU to collect, consolidate and use my personal data as part of the evaluation and archiving process of the University Business Affairs Office. I also recognize the authority of CLSU to process my personal information pursuant to Data Privacy Act and other applicable laws.
                    </li>
                    <li>
                      <span class="font-medium">2.</span> I shall voluntarily show my Driver’s License as may be requested by the guard upon entering the CLSU Gates, and if the vehicle is driven by a company or personal driver, I shall likewise require the driver to show the same.
                    </li>
                    <li>
                      <span class="font-medium">3.</span> I shall abide by all the rules and regulations of CLSU pertaining to the operation of the vehicle i.e. speed limits and parking privileges while inside the University premises.
                    </li>
                    <li>
                      <span class="font-medium">4.</span> The sticker is non-transferable and must be permanently posted on the upper left side of the windshield (cars and other related type of vehicles), and on the upper left side of the visor/front cover (motorcycle and tricycle and other related type of vehicles) of the vehicle to which it is assigned.
                    </li>
                    <li>
                      <span class="font-medium">5.</span> Falsification, counterfeiting, tampering, including lamination and detachment, of the vehicle sticker, will result in confiscation and cancellation.
                    </li>
                    <li>
                      <span class="font-medium">6.</span> The CLSU vehicle sticker shall be valid for three (3) calendar years. A sticker shall be issued annually for the consecutive years upon issuance.
                    </li>
                    <li>
                      <span class="font-medium">7.</span> Upon sale or transfer of my vehicle to a third party, I shall strip off the sticker and inform CLSU of the same by sending email to: ubap@clsu.edu.ph.
                    </li>
                  </ol>
              
                  <p>Please understand the roles of the University security guards and cooperate with them at all times. We appreciate your understanding and cooperation in ensuring a safe and secure environment for the whole CLSU community.</p>
                  <p>For further information and inquiries, please contact the University Business Affairs Program Office through facebook page: @ubapofficial or email us through: ubap@clsu.edu.ph.</p>

                </div>
              </div>
        </div>

        <div class="mt-4 flex items-center space-x-2">
            <input type="checkbox" id="agreeCheckbox" class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
            <label for="agreeCheckbox" class="text-sm text-gray-700">I have read and agree to the terms and conditions</label>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row justify-end space-y-1 sm:space-y-0 sm:space-x-3 mt-3">
            <button id="declineBtn" class="px-3 sm:px-6 py-1.5 sm:py-2 border rounded-lg hover:bg-gray-100 font-medium text-xs sm:text-base w-full">
                Decline
            </button>
            <button id="acceptBtn" class="px-3 sm:px-6 py-1.5 sm:py-2 bg-green-300 text-white rounded-lg font-medium text-xs sm:text-base w-full cursor-not-allowed" disabled>
                Accept & Continue
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const agreeCheckbox = document.getElementById('agreeCheckbox');
        const acceptBtn = document.getElementById('acceptBtn');

        agreeCheckbox.addEventListener('change', (e) => {
            if (e.target.checked) {
                acceptBtn.disabled = false;
                acceptBtn.classList.remove('bg-green-300', 'cursor-not-allowed');
                acceptBtn.classList.add('bg-green-600', 'hover:bg-green-700');
            } else {
                acceptBtn.disabled = true;
                acceptBtn.classList.add('bg-green-300', 'cursor-not-allowed');
                acceptBtn.classList.remove('bg-green-600', 'hover:bg-green-700');
            }
        });

        // Modal Control
        const modal = document.getElementById('termsModal');

        document.getElementById('closeModal').addEventListener('click', () => {
            modal.style.display = 'none';
        });

        document.getElementById('declineBtn').addEventListener('click', () => {
            modal.style.display = 'none';
        });

        document.getElementById('acceptBtn').addEventListener('click', () => {
    window.location.href = "{{ route('gate-pass.personal-info') }}";  // Using Laravel's named routes
});


        // Show modal on page load
        window.addEventListener('load', () => {
            modal.style.display = 'flex';
        });
    });
</script>
   
</body>
</html>