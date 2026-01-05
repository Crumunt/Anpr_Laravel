<div id="camera-settings-modal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 hidden backdrop-blur-sm transition-opacity duration-300">
    <div class="bg-white rounded-xl shadow-2xl max-w-lg w-full mx-4 transform transition-all duration-300 scale-95 opacity-0" id="camera-settings-content">
        <div class="relative">
            <!-- Header with gradient background -->
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-6 py-4 rounded-t-xl">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-white bg-opacity-20 flex items-center justify-center mr-3">
                            <i class="fas fa-cog text-white" aria-hidden="true"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold">Camera Settings</h3>
                            <p class="text-xs text-blue-100">Configure camera and recognition settings</p>
                        </div>
                    </div>
                    <button onclick="closeCameraSettings()" class="text-white hover:text-blue-100 transition-colors" aria-label="Close modal">
                        <i class="fas fa-times" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
            
            <!-- Content -->
            <div class="p-6">
                <!-- Video Quality Section -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-3" for="camera-quality">
                        <i class="fas fa-video mr-2 text-blue-500" aria-hidden="true"></i>
                        Video Quality
                    </label>
                    <select id="camera-quality" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-colors">
                        <option value="low">Low Quality (480p) - Faster processing</option>
                        <option value="medium" selected>Medium Quality (720p) - Balanced</option>
                        <option value="high">High Quality (1080p) - Best detail</option>
                        <option value="ultra">Ultra HD (4K) - Maximum detail</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Higher quality requires more bandwidth and processing power</p>
                </div>
                
                <!-- FPS Settings -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-3" for="fps-limit">
                        <i class="fas fa-tachometer-alt mr-2 text-green-500" aria-hidden="true"></i>
                        Frame Rate (FPS)
                    </label>
                    <select id="fps-limit" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 transition-colors">
                        <option value="15">15 FPS - Low bandwidth</option>
                        <option value="24">24 FPS - Standard</option>
                        <option value="30" selected>30 FPS - Smooth</option>
                        <option value="60">60 FPS - High performance</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Higher FPS provides smoother video but uses more resources</p>
                </div>
                
                <!-- Recognition Settings -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                        <i class="fas fa-brain mr-2 text-purple-500" aria-hidden="true"></i>
                        Recognition Settings
                    </label>
                    <div class="space-y-3 bg-gray-50 p-4 rounded-lg">
                        <label class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input type="checkbox" class="rounded text-blue-600 focus:ring-blue-500 h-4 w-4" checked>
                                <span class="ml-3 text-sm font-medium text-gray-700">License Plate Detection</span>
                            </div>
                            <span class="text-xs text-green-600 font-medium">Active</span>
                        </label>
                        <label class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input type="checkbox" class="rounded text-blue-600 focus:ring-blue-500 h-4 w-4" checked>
                                <span class="ml-3 text-sm font-medium text-gray-700">Vehicle Type Recognition</span>
                            </div>
                            <span class="text-xs text-green-600 font-medium">Active</span>
                        </label>
                        <label class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input type="checkbox" class="rounded text-blue-600 focus:ring-blue-500 h-4 w-4" checked>
                                <span class="ml-3 text-sm font-medium text-gray-700">Color Recognition</span>
                            </div>
                            <span class="text-xs text-green-600 font-medium">Active</span>
                        </label>
                        <label class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input type="checkbox" class="rounded text-blue-600 focus:ring-blue-500 h-4 w-4">
                                <span class="ml-3 text-sm font-medium text-gray-700">Face Recognition</span>
                            </div>
                            <span class="text-xs text-gray-500 font-medium">Disabled</span>
                        </label>
                        <label class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input type="checkbox" class="rounded text-blue-600 focus:ring-blue-500 h-4 w-4">
                                <span class="ml-3 text-sm font-medium text-gray-700">Behavioral Analysis</span>
                            </div>
                            <span class="text-xs text-gray-500 font-medium">Disabled</span>
                        </label>
                    </div>
                </div>
                
                <!-- Advanced Settings -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                        <i class="fas fa-sliders-h mr-2 text-orange-500" aria-hidden="true"></i>
                        Advanced Settings
                    </label>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Detection Sensitivity</label>
                            <select class="w-full text-sm border-gray-300 rounded-md focus:border-orange-500 focus:ring focus:ring-orange-200">
                                <option>Low</option>
                                <option selected>Medium</option>
                                <option>High</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Alert Threshold</label>
                            <select class="w-full text-sm border-gray-300 rounded-md focus:border-orange-500 focus:ring focus:ring-orange-200">
                                <option>70%</option>
                                <option selected>85%</option>
                                <option>95%</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Action buttons -->
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button onclick="closeCameraSettings()" class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200 font-medium">
                        <i class="fas fa-times mr-2" aria-hidden="true"></i>
                        Cancel
                    </button>
                    <button onclick="saveCameraSettings()" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 border border-transparent rounded-lg text-white hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 font-medium shadow-lg">
                        <i class="fas fa-save mr-2" aria-hidden="true"></i>
                        Save Settings
                    </button>
                </div>
            </div>
        </div>
    </div>
</div> 