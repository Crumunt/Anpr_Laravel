@props(['id' => 'notificationToast'])
<div id="{{ $id }}" class="fixed bottom-4 right-4 z-50 bg-white rounded-xl shadow-xl max-w-sm w-full hidden animate-slide-in">
    <div class="p-4">
        <div class="flex items-start">
            <div class="flex-shrink-0 bg-green-100 rounded-full p-2" id="{{ $id }}-icon-container">
                <i class="fas fa-check-circle text-green-600 text-lg" id="{{ $id }}-icon" aria-hidden="true"></i>
            </div>
            <div class="ml-3 w-0 flex-1">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-medium text-gray-900" id="{{ $id }}-title"></h3>
                    <button onclick="closeToast()" class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500" aria-label="Close notification">
                        <i class="fas fa-times" aria-hidden="true"></i>
                    </button>
                </div>
                <p class="mt-1 text-sm text-gray-600" id="{{ $id }}-message"></p>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes slideIn {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}
.animate-slide-in {
    animation: slideIn 0.3s ease-out;
}
</style> 