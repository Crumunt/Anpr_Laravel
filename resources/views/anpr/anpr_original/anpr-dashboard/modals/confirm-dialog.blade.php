<div id="confirm-dialog" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden" role="dialog" aria-modal="true" aria-labelledby="confirm-title" aria-describedby="confirm-message">
    <div id="confirm-dialog-content" class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 animate-fade-in">
        <div class="p-6">
            <h3 id="confirm-title" class="text-lg font-bold text-gray-900 mb-4">Confirm Action</h3>
            <p id="confirm-message" class="text-gray-600 mb-6">Are you sure you want to proceed with this action?</p>
            <div class="flex justify-end space-x-3">
                <button id="cancel-button" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Cancel
                </button>
                <button id="confirm-button" class="px-4 py-2 bg-green-600 border border-transparent rounded-md text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
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
