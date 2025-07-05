<x-layout>
    <div class="text-center py-8">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 text-green-600 mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Application Successfully Submitted!</h2>
        <p class="text-gray-600 mb-8 max-w-md mx-auto">
            Thank you for applying for a CLSU RFID Gate Pass. Your application has been received and is being processed.
        </p>
        
        <div class="bg-gray-50 rounded-lg p-6 max-w-md mx-auto text-left">
            <h3 class="font-medium text-gray-800 mb-2">What happens next?</h3>
            <ol class="list-decimal list-inside text-gray-600 space-y-2">
                <li>Your application will be reviewed by the CLSU Security Office</li>
                <li>You will receive an email notification once your application is approved</li>
                <li>Visit the Security Office with your ID to pick up your RFID gate pass</li>
            </ol>
        </div>
        
        <a href="{{ route('home') }}" class="mt-8 inline-block text-green-600 hover:text-green-800 font-medium">
            Return to Home
        </a>
    </div>
</x-layout>