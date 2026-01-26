<div class="col-span-1 md:col-span-2 glass-card overflow-hidden shadow-lg flex flex-col h-full">
    <div class="flex justify-between items-center px-4 md:px-6 py-4 border-b bg-white">
        <div class="flex items-center">
            <div class="w-9 h-9 rounded-full bg-green-100 flex items-center justify-center clsu-text mr-3">
                <i class="fas fa-video" aria-hidden="true"></i>
            </div>
            <h2 class="font-semibold text-gray-800 text-lg">Live Camera Feed</h2>
        </div>
        <div class="flex items-center space-x-3">
            <select id="camera-select" class="text-sm border-gray-300 rounded-lg shadow-sm focus:border-green-400 focus:ring focus:ring-green-200 focus:ring-opacity-50 py-2 pl-4 pr-8 bg-gray-50" aria-label="Select camera">
                <option>Camera #1 - Main Entrance</option>
                <option>Camera #2 - Exit Gate</option>
                <option>Camera #3 - Parking Area</option>
                <option>Camera #4 - Back Gate</option>
            </select>
            <span id="connection-status" class="hidden sm:flex items-center bg-green-100 text-green-800 px-3 py-1.5 rounded-lg text-xs font-medium">
                <span class="w-2 h-2 bg-green-500 rounded-full mr-1.5 animate-pulse"></span>
                LIVE
            </span>
        </div>
    </div>
    
    <div class="flex-grow camera-container relative bg-gray-900">
        <div id="camera-feed-container" class="h-full">
            <div id="camera-loading" class="h-full flex items-center justify-center">
                <div class="spinner"></div>
                <p class="text-white mt-20">Connecting to camera...</p>
            </div>
            
            <div id="camera-error" class="h-full flex flex-col items-center justify-center bg-gray-800 text-white p-8 text-center" style="display: none;">
                <i class="fas fa-exclamation-triangle text-amber-400 text-4xl mb-4" aria-hidden="true"></i>
                <h3 class="text-xl font-bold mb-2">Camera Connection Error</h3>
                <p class="text-gray-300 mb-4">Unable to connect to the selected camera. Please check your connection or try another camera.</p>
                <button onclick="retryCamera()" class="bg-amber-500 hover:bg-amber-600 text-gray-900 px-4 py-2 rounded-lg transition-colors font-medium">
                    <i class="fas fa-sync-alt mr-2" aria-hidden="true"></i> Retry Connection
                </button>
            </div>
            
            <div id="camera-active" class="h-full" style="display: none;">
                <video id="live-video" autoplay playsinline class="w-full h-full object-cover"></video>
                
                <div class="plate-highlight" id="plate-highlight" style="top: 70%; left: 45%; width: 100px; height: 30px; display: none;"></div>
                
                <div class="absolute top-4 left-4 bg-black bg-opacity-70 text-white text-xs px-3 py-2 rounded-lg flex items-center">
                    <i class="fas fa-video mr-2" aria-hidden="true"></i>
                    <span id="camera-active-name">Camera #1 - Main Entrance</span>
                </div>
                
                <div class="absolute top-4 right-4 bg-green-600 text-white text-xs px-3 py-2 rounded-lg flex items-center">
                    <span class="w-2 h-2 bg-white rounded-full mr-1.5 animate-pulse"></span>
                    <span>SCANNING</span>
                </div>
                
                <div class="absolute bottom-4 right-4 clsu-accent-bg text-gray-900 text-sm font-bold px-3 py-2 rounded-lg flex items-center">
                    <i class="fas fa-car mr-2" aria-hidden="true"></i>
                    ABC-123
                </div>
                
                <div class="absolute bottom-4 left-4 bg-black bg-opacity-70 text-white text-xs px-3 py-2 rounded-lg">
                    <span id="timestamp">2025-03-28 14:32:45</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="bg-gray-900 py-3 px-4 border-t border-gray-800">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <div class="flex space-x-2">
                <button onclick="switchCamera(-1)" class="p-2 bg-gray-800 hover:bg-gray-700 rounded-lg text-white transition-colors tooltip" aria-label="Previous camera">
                    <i class="fas fa-chevron-left" aria-hidden="true"></i>
                    <span class="tooltip-text">Previous camera</span>
                </button>
                <button onclick="switchCamera(1)" class="p-2 bg-gray-800 hover:bg-gray-700 rounded-lg text-white transition-colors tooltip" aria-label="Next camera">
                    <i class="fas fa-chevron-right" aria-hidden="true"></i>
                    <span class="tooltip-text">Next camera</span>
                </button>
                <button id="toggle-feed-btn" class="p-2 bg-red-600 hover:bg-red-700 rounded-lg text-white transition-colors flex items-center px-4" aria-label="Start/stop recording">
                    <i class="fas fa-circle mr-2" aria-hidden="true"></i> REC
                </button>
            </div>
            
            <div class="flex items-center">
                <span class="text-xs text-gray-400 mr-3" id="resolution-info">1280 × 720</span>
                <span class="text-xs text-gray-400 mr-3" id="fps-info">30 FPS</span>
            </div>
            
            <div class="flex space-x-2">
                <button onclick="takeSnapshot()" class="p-2 bg-gray-800 hover:bg-gray-700 rounded-lg text-white transition-colors tooltip" aria-label="Take snapshot">
                    <i class="fas fa-camera" aria-hidden="true"></i>
                    <span class="tooltip-text">Take snapshot</span>
                </button>
                <button onclick="toggleFullscreen()" class="p-2 bg-gray-800 hover:bg-gray-700 rounded-lg text-white transition-colors tooltip" aria-label="Full screen">
                    <i class="fas fa-expand" aria-hidden="true"></i>
                    <span class="tooltip-text">Full screen</span>
                </button>
                <button onclick="openCameraSettings()" class="p-2 bg-gray-800 hover:bg-gray-700 rounded-lg text-white transition-colors tooltip" aria-label="Camera settings">
                    <i class="fas fa-cog" aria-hidden="true"></i>
                    <span class="tooltip-text">Camera settings</span>
                </button>
            </div>
        </div>
    </div>
</div>
