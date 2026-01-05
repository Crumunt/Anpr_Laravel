<div id="notificationToast" class="fixed bottom-4 right-4 z-50 bg-white rounded-xl shadow-2xl max-w-sm w-full hidden" role="alert" aria-live="assertive" aria-atomic="true">
    <!-- Header with gradient background -->
    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-4 py-3 rounded-t-xl">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-car-side text-sm text-white" aria-hidden="true"></i>
                <span class="ml-2 text-white text-sm font-medium">ANPR System</span>
            </div>
            <button onclick="closeToast()" class="text-white hover:text-gray-200 transition-colors" aria-label="Close notification">
                <i class="fas fa-times text-sm" aria-hidden="true"></i>
            </button>
        </div>
    </div>
    
    <!-- Content -->
    <div id="toast-content" class="p-4 transition-all duration-300 translate-y-2 opacity-0">
        <div class="flex items-start">
            <div class="flex-shrink-0 bg-green-100 rounded-full p-2" id="toast-icon-container">
                <i class="fas fa-check-circle text-green-600 text-lg" id="toast-icon" aria-hidden="true"></i>
            </div>
            <div class="ml-3 w-0 flex-1">
                <div class="flex items-center justify-between">
                    <h3 id="toast-title" class="text-sm font-medium text-gray-900">Alert resolved</h3>
                </div>
                <p id="toast-message" class="mt-1 text-sm text-gray-500">Alert #ALT-2025-0042 has been successfully resolved.</p>
                <div class="flex items-center mt-2 space-x-2">
                    <span id="toast-time" class="text-xs text-gray-400">Just now</span>
                    <span id="toast-confidence" class="text-xs font-bold text-green-600">98%</span>
                </div>
                <div class="w-full h-1 bg-gray-200 rounded-full mt-2 overflow-hidden">
                    <div id="toast-progress" class="h-1 bg-green-500 progress-bar" style="width: 100%; transition: width 1.5s ease-in-out;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
