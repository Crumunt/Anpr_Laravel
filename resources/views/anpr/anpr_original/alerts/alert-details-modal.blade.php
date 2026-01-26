@props(['alert' => null])

<div id="alert-details-modal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 hidden transition-all duration-300 ease-out" role="dialog" aria-labelledby="alert-details-title">
    <div class="bg-white rounded-2xl shadow-2xl max-w-5xl w-full mx-4 sm:mx-6 max-h-[95vh] overflow-hidden transform transition-all duration-300 scale-95 opacity-0" id="modal-content">
        <!-- Enhanced Header with Live Status -->
        <div class="relative bg-gradient-to-r from-red-50 via-orange-50 to-red-50 border-b border-gray-200 px-6 py-5">
            <div class="absolute inset-0 bg-gradient-to-r from-red-100/20 to-orange-100/20"></div>
            <div class="relative flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-100 to-red-200 flex items-center justify-center shadow-lg animate-pulse">
                            <i class="fas fa-exclamation-triangle text-red-600 text-lg" aria-hidden="true"></i>
                        </div>
                        <div class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full flex items-center justify-center animate-bounce">
                            <span class="text-xs text-white font-bold">!</span>
                        </div>
                        <!-- Live indicator -->
                        <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-500 rounded-full border-2 border-white animate-pulse"></div>
                    </div>
                    <div>
                        <h3 id="alert-details-title" class="text-xl font-bold text-gray-900 flex items-center">
                            Alert Details
                            @if($alert)
                                <span class="ml-2 text-sm font-normal text-gray-500">#{{ $alert['alert_id'] }}</span>
                                <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 animate-pulse">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1"></span>
                                    LIVE
                                </span>
                            @endif
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">Comprehensive alert information and response tracking</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <!-- Real-time clock -->
                    <div class="hidden sm:flex items-center space-x-2 bg-white/80 backdrop-blur-sm rounded-lg px-3 py-2 border border-gray-200">
                        <i class="fas fa-clock text-gray-400 text-sm"></i>
                        <span class="text-sm font-medium text-gray-700" id="live-clock"></span>
                    </div>
                    <button onclick="closeAlertDetails()" class="group p-2 rounded-xl hover:bg-gray-100 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2" aria-label="Close modal">
                        <i class="fas fa-times text-gray-400 group-hover:text-gray-600 transition-colors" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Enhanced Content -->
        <div class="overflow-y-auto p-6 max-h-[calc(95vh-140px)] scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
            @if($alert)
                <div class="animate-fade-in space-y-6">
                    <!-- Enhanced Alert Status Banner -->
                    <div class="bg-gradient-to-r from-red-50 via-orange-50 to-red-50 border border-red-200 rounded-xl p-4 relative overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-r from-red-100/20 to-orange-100/20"></div>
                        <div class="relative flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="flex items-center space-x-2">
                                    <div class="w-3 h-3 bg-red-500 rounded-full animate-pulse"></div>
                                    <span class="text-sm font-medium text-red-800">Active Alert</span>
                                </div>
                                <span class="text-gray-400">|</span>
                                <span class="text-sm text-gray-600">Detected {{ $alert['time'] }}</span>
                                <span class="text-gray-400">|</span>
                                <span class="text-sm text-gray-600">Duration: <span id="alert-duration" class="font-semibold">2m 15s</span></span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200 animate-pulse">
                                    <span class="w-2 h-2 bg-red-500 rounded-full mr-1.5"></span>
                                    {{ ucfirst($alert['priority']) }} Priority
                                </span>
                                <!-- Response time indicator -->
                                <div class="flex items-center space-x-1 bg-amber-100 rounded-full px-2 py-1">
                                    <i class="fas fa-stopwatch text-amber-600 text-xs"></i>
                                    <span class="text-xs font-medium text-amber-800">2m remaining</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Content Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Left Column - Vehicle Information -->
                        <div class="lg:col-span-2 space-y-6">
                            <!-- Enhanced Vehicle Information Card -->
                            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-lg transition-shadow duration-300">
                                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                                    <h4 class="text-lg font-semibold text-gray-900 flex items-center justify-between">
                                        <div class="flex items-center">
                                            <i class="fas fa-car text-blue-500 mr-2"></i>
                                            Vehicle Information
                                        </div>
                                        <button class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center space-x-1 transition-colors">
                                            <i class="fas fa-expand-alt"></i>
                                            <span>View Full</span>
                                        </button>
                                    </h4>
                                </div>
                                <div class="p-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Enhanced Vehicle Image -->
                                        <div class="relative group">
                                            <div class="aspect-video bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl overflow-hidden shadow-lg group-hover:shadow-xl transition-shadow duration-300">
                                                <img src="{{ $alert['image_url'] ?? 'https://placehold.co/600x400/1f2937/cccccc?text=Vehicle+Snapshot' }}" 
                                                     alt="Vehicle snapshot" 
                                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                                                <!-- Image overlay with actions -->
                                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-all duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
                                                    <div class="flex space-x-2">
                                                        <button class="bg-white/90 backdrop-blur-sm rounded-lg p-2 hover:bg-white transition-colors shadow-lg">
                                                            <i class="fas fa-search text-gray-700"></i>
                                                        </button>
                                                        <button class="bg-white/90 backdrop-blur-sm rounded-lg p-2 hover:bg-white transition-colors shadow-lg">
                                                            <i class="fas fa-download text-gray-700"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="absolute top-4 left-4">
                                                <div class="bg-black/80 backdrop-blur-sm text-white px-3 py-1 rounded-lg text-sm font-mono shadow-lg">
                                                    {{ $alert['license_plate'] ?? 'ABC-123' }}
                                                </div>
                                            </div>
                                            <!-- Confidence badge -->
                                            <div class="absolute top-4 right-4">
                                                <div class="bg-green-500/90 backdrop-blur-sm text-white px-2 py-1 rounded-lg text-xs font-medium shadow-lg">
                                                    92% Match
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Enhanced Vehicle Details -->
                                        <div class="space-y-4">
                                            <div class="grid grid-cols-2 gap-4">
                                                <div class="bg-gray-50 rounded-lg p-3 hover:bg-gray-100 transition-colors cursor-pointer group">
                                                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Make & Model</p>
                                                    <p class="text-sm font-semibold text-gray-900 mt-1 group-hover:text-blue-600 transition-colors">{{ $alert['model'] ?? 'Mitsubishi Montero' }}</p>
                                                </div>
                                                <div class="bg-gray-50 rounded-lg p-3 hover:bg-gray-100 transition-colors cursor-pointer group">
                                                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Color</p>
                                                    <div class="flex items-center mt-1">
                                                        <div class="w-4 h-4 rounded-full bg-gray-900 mr-2 border-2 border-gray-300 group-hover:scale-110 transition-transform"></div>
                                                        <p class="text-sm font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">{{ $alert['color'] ?? 'Black' }}</p>
                                                    </div>
                                                </div>
                                                <div class="bg-gray-50 rounded-lg p-3 hover:bg-gray-100 transition-colors cursor-pointer group">
                                                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle Type</p>
                                                    <p class="text-sm font-semibold text-gray-900 mt-1 group-hover:text-blue-600 transition-colors">{{ $alert['type'] ?? 'SUV' }}</p>
                                                </div>
                                                <div class="bg-gray-50 rounded-lg p-3 hover:bg-gray-100 transition-colors cursor-pointer group">
                                                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">RFID Status</p>
                                                    <div class="flex items-center mt-1">
                                                        <div class="w-2 h-2 bg-red-500 rounded-full mr-2 animate-pulse"></div>
                                                        <p class="text-sm font-semibold text-red-600 group-hover:text-red-700 transition-colors">Not Detected</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Enhanced Recognition Confidence -->
                                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4 border border-blue-200 hover:shadow-md transition-shadow">
                                                <div class="flex items-center justify-between mb-2">
                                                    <p class="text-sm font-medium text-blue-900">Recognition Confidence</p>
                                                    <span class="text-sm font-bold text-blue-700">92%</span>
                                                </div>
                                                <div class="w-full bg-blue-200 rounded-full h-2 relative overflow-hidden">
                                                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-2 rounded-full transition-all duration-1000 relative" style="width: 92%">
                                                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent animate-pulse"></div>
                                                    </div>
                                                </div>
                                                <div class="flex justify-between text-xs text-blue-600 mt-1">
                                                    <span>Low</span>
                                                    <span>High</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Enhanced Alert Information Card -->
                            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-lg transition-shadow duration-300">
                                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                                    <h4 class="text-lg font-semibold text-gray-900 flex items-center">
                                        <i class="fas fa-info-circle text-indigo-500 mr-2"></i>
                                        Alert Information
                                    </h4>
                                </div>
                                <div class="p-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors cursor-pointer group">
                                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Alert Type</p>
                                            <p class="text-sm font-semibold text-gray-900 mt-1 group-hover:text-indigo-600 transition-colors">{{ $alert['type_label'] }}</p>
                                        </div>
                                        <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors cursor-pointer group">
                                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Detected At</p>
                                            <p class="text-sm font-semibold text-gray-900 mt-1 group-hover:text-indigo-600 transition-colors">{{ $alert['time'] }}</p>
                                        </div>
                                        <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors cursor-pointer group">
                                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Location</p>
                                            <p class="text-sm font-semibold text-gray-900 mt-1 group-hover:text-indigo-600 transition-colors">{{ $alert['location'] }}</p>
                                        </div>
                                        <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors cursor-pointer group">
                                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Status</p>
                                            <div class="flex items-center mt-1">
                                                <div class="w-2 h-2 bg-red-500 rounded-full mr-2 animate-pulse"></div>
                                                <p class="text-sm font-semibold text-red-600 group-hover:text-red-700 transition-colors">Active - Unresolved</p>
                                            </div>
                                        </div>
                                        <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors cursor-pointer group">
                                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned To</p>
                                            <p class="text-sm font-semibold text-gray-900 mt-1 group-hover:text-indigo-600 transition-colors">Security Team</p>
                                        </div>
                                        <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors cursor-pointer group">
                                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Response Time</p>
                                            <p class="text-sm font-semibold text-gray-900 mt-1 group-hover:text-indigo-600 transition-colors">2 minutes (Expected)</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Enhanced Alert Description Card -->
                            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-lg transition-shadow duration-300">
                                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                                    <h4 class="text-lg font-semibold text-gray-900 flex items-center">
                                        <i class="fas fa-file-alt text-green-500 mr-2"></i>
                                        Alert Description
                                    </h4>
                                </div>
                                <div class="p-6">
                                    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border border-yellow-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                        <p class="text-sm text-gray-700 leading-relaxed">{{ $alert['details'] }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Actions & Timeline -->
                        <div class="space-y-6">
                            <!-- Enhanced Quick Actions Card -->
                            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-lg transition-shadow duration-300">
                                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                                    <h4 class="text-lg font-semibold text-gray-900 flex items-center">
                                        <i class="fas fa-bolt text-yellow-500 mr-2"></i>
                                        Quick Actions
                                    </h4>
                                </div>
                                <div class="p-6 space-y-3">
                                    <button onclick="confirmEscalateAlert('{{ $alert['alert_id'] ?? 'ALT-2025-0042' }}')" class="w-full bg-red-600 hover:bg-red-700 text-white rounded-lg px-4 py-3 text-sm font-medium transition-all duration-200 flex items-center justify-center space-x-2 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 hover:scale-105">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <span>Escalate Alert</span>
                                    </button>
                                    <button onclick="confirmAssignAlert('{{ $alert['alert_id'] ?? 'ALT-2025-0042' }}')" class="w-full bg-amber-500 hover:bg-amber-600 text-white rounded-lg px-4 py-3 text-sm font-medium transition-all duration-200 flex items-center justify-center space-x-2 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 hover:scale-105">
                                        <i class="fas fa-user-plus"></i>
                                        <span>Assign to Team</span>
                                    </button>
                                    <button onclick="confirmResolveAlert('{{ $alert['alert_id'] ?? 'ALT-2025-0042' }}')" class="w-full bg-green-600 hover:bg-green-700 text-white rounded-lg px-4 py-3 text-sm font-medium transition-all duration-200 flex items-center justify-center space-x-2 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 hover:scale-105">
                                        <i class="fas fa-check"></i>
                                        <span>Mark as Resolved</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Enhanced Response Timeline Card -->
                            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-lg transition-shadow duration-300">
                                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                                    <h4 class="text-lg font-semibold text-gray-900 flex items-center">
                                        <i class="fas fa-clock text-purple-500 mr-2"></i>
                                        Response Timeline
                                    </h4>
                                </div>
                                <div class="p-6">
                                    <div class="space-y-4">
                                        <div class="flex items-start space-x-3 group hover:bg-gray-50 rounded-lg p-2 transition-colors">
                                            <div class="flex-shrink-0">
                                                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center group-hover:scale-110 transition-transform">
                                                    <i class="fas fa-check text-green-600 text-sm"></i>
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900">Security personnel dispatched</p>
                                                <p class="text-xs text-gray-500">2 minutes ago</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start space-x-3 group hover:bg-gray-50 rounded-lg p-2 transition-colors">
                                            <div class="flex-shrink-0">
                                                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center group-hover:scale-110 transition-transform">
                                                    <i class="fas fa-check text-green-600 text-sm"></i>
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900">Automatic notification sent</p>
                                                <p class="text-xs text-gray-500">2 minutes ago</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start space-x-3 group hover:bg-gray-50 rounded-lg p-2 transition-colors">
                                            <div class="flex-shrink-0">
                                                <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center group-hover:scale-110 transition-transform">
                                                    <i class="fas fa-clock text-amber-600 text-sm"></i>
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900">Investigation in progress</p>
                                                <p class="text-xs text-gray-500">In progress</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start space-x-3 opacity-50 group hover:bg-gray-50 rounded-lg p-2 transition-colors">
                                            <div class="flex-shrink-0">
                                                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center group-hover:scale-110 transition-transform">
                                                    <i class="fas fa-ban text-gray-400 text-sm"></i>
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900">Alert resolution</p>
                                                <p class="text-xs text-gray-500">Pending</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Comments Section -->
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                            <h4 class="text-lg font-semibold text-gray-900 flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="fas fa-comments text-blue-500 mr-2"></i>
                                    Notes & Comments
                                </div>
                                <span class="text-sm text-gray-500">2 comments</span>
                            </h4>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4 mb-6">
                                <div class="flex items-start space-x-3 group hover:bg-gray-50 rounded-lg p-2 transition-colors">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-100 to-indigo-200 flex items-center justify-center group-hover:scale-110 transition-transform">
                                            <span class="text-sm font-bold text-indigo-700">JD</span>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors">
                                            <div class="flex items-center justify-between mb-2">
                                                <p class="text-sm font-medium text-gray-900">John Doe</p>
                                                <span class="text-xs text-gray-500">2 minutes ago</span>
                                            </div>
                                            <p class="text-sm text-gray-700">
                                                Security team has been notified. Officer Rodriguez is heading to the main entrance to investigate.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3 group hover:bg-gray-50 rounded-lg p-2 transition-colors">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center group-hover:scale-110 transition-transform">
                                            <span class="text-sm font-bold text-green-700">SW</span>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors">
                                            <div class="flex items-center justify-between mb-2">
                                                <p class="text-sm font-medium text-gray-900">Sarah Williams</p>
                                                <span class="text-xs text-gray-500">Just now</span>
                                            </div>
                                            <p class="text-sm text-gray-700">
                                                Checking visitor logs to see if this might be an unregistered visitor. Will update shortly.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Enhanced Add Comment -->
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center">
                                        <span class="text-sm font-bold text-green-700">You</span>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <textarea rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 text-sm resize-none transition-all duration-200 hover:shadow-md focus:shadow-lg" placeholder="Add a comment..."></textarea>
                                    <div class="mt-2 flex justify-between items-center">
                                        <div class="flex items-center space-x-2 text-xs text-gray-500">
                                            <i class="fas fa-info-circle"></i>
                                            <span>Press Enter to send, Shift+Enter for new line</span>
                                        </div>
                                        <button class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 hover:scale-105">
                                            Add Comment
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="animate-pulse space-y-6">
                    <div class="h-6 bg-gray-200 rounded w-3/4"></div>
                    <div class="h-4 bg-gray-200 rounded w-1/2"></div>
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="lg:col-span-2 space-y-4">
                            <div class="h-32 bg-gray-200 rounded"></div>
                            <div class="h-24 bg-gray-200 rounded"></div>
                        </div>
                        <div class="space-y-4">
                            <div class="h-32 bg-gray-200 rounded"></div>
                            <div class="h-48 bg-gray-200 rounded"></div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Enhanced Footer -->
        <div class="border-t border-gray-200 px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 flex flex-wrap justify-between gap-3">
            <div class="flex space-x-2">
                <button class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 hover:scale-105" onclick="closeAlertDetails()">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes fadeIn {
    from { 
        opacity: 0; 
        transform: translateY(20px) scale(0.95); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0) scale(1); 
    }
}

.animate-fade-in {
    animation: fadeIn 0.4s ease-out;
}

/* Custom scrollbar */
.scrollbar-thin::-webkit-scrollbar {
    width: 6px;
}

.scrollbar-thin::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

.scrollbar-thin::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.scrollbar-thin::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Modal animation */
#alert-details-modal:not(.hidden) #modal-content {
    animation: modalSlideIn 0.3s ease-out forwards;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: scale(0.95) translateY(20px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

/* Enhanced hover effects */
.group:hover .group-hover\:scale-110 {
    transform: scale(1.1);
}

.group:hover .group-hover\:scale-105 {
    transform: scale(1.05);
}

/* Live clock animation */
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>

<!-- scripts moved to public/js/anpr/alerts.js --> 