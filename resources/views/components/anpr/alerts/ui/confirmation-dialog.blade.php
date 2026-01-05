@props([
    'id' => 'confirm-dialog',
    'title' => 'Confirm Action',
    'message' => 'Are you sure you want to proceed with this action?',
])
<div id="confirm-dialog" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 animate-fade-in">
        <div class="p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4" id="confirm-dialog-title">{{ $title }}</h3>
            <p class="text-gray-600 mb-6" id="confirm-dialog-message">{{ $message }}</p>
            <div class="flex justify-end space-x-3">
                <button id="confirm-dialog-cancel-button" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Cancel
                </button>
                <button id="confirm-dialog-confirm-button" class="px-4 py-2" style="background-color: #379f4f; border: none; color: white; border-radius: 0.375rem; transition: background 0.2s;" onmouseover="this.style.backgroundColor='#2c7d41'" onmouseout="this.style.backgroundColor='#379f4f'">
                    Confirm
                </button>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}
.animate-fade-in {
    animation: fadeIn 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
</style> 