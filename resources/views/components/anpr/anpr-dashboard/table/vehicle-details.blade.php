@php
    // Get vehicle data from view or use defaults
    $vehicle = $vehicle ?? [];
@endphp

<div class="glass-card overflow-hidden shadow-lg">
    <div class="bg-[#008000] text-white px-6 py-5 flex items-center justify-between">
        <div class="flex items-center">
            <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center clsu-text mr-3">
                <i class="fas fa-car-side text-lg" aria-hidden="true"></i>
            </div>
            <div>
                <h2 class="font-bold text-lg">Vehicle Details</h2>
                <p class="text-xs text-green-100">Last updated: 1 min ago</p>
            </div>
        </div>
        <span class="bg-green-600 text-white text-xs font-bold px-3 py-1.5 rounded-lg">AUTHORIZED</span>
    </div>
    
    <div class="p-6">
        <div class="flex justify-center mb-6">
            <div class="relative">
                <!-- Enhanced License Plate Design -->
                <div class="relative bg-gradient-to-r from-yellow-400 to-yellow-500 border-4 border-black py-4 px-10 rounded-lg shadow-2xl transform hover:scale-105 transition-all duration-300">
                    <!-- License plate background with metallic effect -->
                    <div class="absolute inset-0 bg-gradient-to-b from-yellow-300 to-yellow-600 rounded-lg opacity-80"></div>
                    
                    <!-- License plate text with enhanced styling -->
                    <div class="relative z-10">
                        <span class="text-3xl font-black tracking-widest text-black drop-shadow-lg">
                            {{ $vehicle['license_plate'] ?? ($vehicle['plate'] ?? 'ABC-123') }}
                        </span>
                    </div>
                    
                    <!-- Reflective border effect -->
                    <div class="absolute inset-0 border-2 border-yellow-200 rounded-lg opacity-50"></div>
                </div>
                
                <!-- Status badge with enhanced styling -->
                <div class="absolute -top-2 -right-2">
                    <span class="bg-gradient-to-r from-green-500 to-emerald-600 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg border-2 border-white">
                        <i class="fas fa-check-circle mr-1" aria-hidden="true"></i>
                        VALID
                    </span>
                </div>
            </div>
        </div>
        
        <div class="flex items-center mb-6 bg-gradient-to-r from-indigo-50 to-purple-50 p-5 rounded-xl border border-indigo-200 shadow-lg">
            <div class="w-14 h-14 rounded-full bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center text-white shadow-lg">
                <i class="fas fa-id-card text-xl" aria-hidden="true"></i>
            </div>
            <div class="ml-5">
                <p class="text-sm font-medium text-indigo-600 mb-1">RFID Tag</p>
                <div class="flex items-center">
                    <p class="text-lg font-bold text-gray-800">{{ $vehicle['rfid_tag'] ?? 'RF-9856-A2' }}</p>
                    <span class="bg-gradient-to-r from-green-500 to-emerald-600 text-white text-xs font-bold px-3 py-1 rounded-full ml-3 shadow-sm">
                        <i class="fas fa-check-circle mr-1" aria-hidden="true"></i>
                        ACTIVE
                    </span>
                </div>
                <p class="text-xs text-gray-500 mt-1">Last scanned: 2 minutes ago</p>
            </div>
        </div>
        
        <div class="border border-gray-200 rounded-lg overflow-hidden mb-6">
            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                <h3 class="font-medium text-gray-700">Vehicle Information</h3>
            </div>
            <div class="p-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-500">Make & Model</span>
                        <div class="flex items-center mt-1">
                            <i class="fas fa-car text-gray-400 mr-2" aria-hidden="true"></i>
                            <span class="text-sm font-medium">{{ $vehicle['make_model'] ?? ($vehicle['model'] ?? 'Toyota Camry') }}</span>
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-500">Type</span>
                        <div class="flex items-center mt-1">
                            <i class="fas fa-tag text-gray-400 mr-2" aria-hidden="true"></i>
                            <span class="text-sm font-medium">{{ $vehicle['type'] ?? 'Sedan' }}</span>
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-500">Color</span>
                        <div class="flex items-center mt-1">
                            <div class="w-4 h-4 rounded-full bg-gray-300 mr-2" aria-hidden="true"></div>
                            <span class="text-sm font-medium">{{ $vehicle['color'] ?? 'Silver' }}</span>
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-500">Year</span>
                        <div class="flex items-center mt-1">
                            <i class="fas fa-calendar text-gray-400 mr-2" aria-hidden="true"></i>
                            <span class="text-sm font-medium">{{ $vehicle['year'] ?? '2023' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="border border-gray-200 rounded-lg overflow-hidden mb-6">
            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                <h3 class="font-medium text-gray-700">Owner Information</h3>
            </div>
            <div class="p-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                        <i class="fas fa-user" aria-hidden="true"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-base font-medium">{{ $vehicle['owner_name'] ?? 'Michael Johnson' }}</p>
                        <p class="text-sm text-gray-500">{{ $vehicle['owner_role'] ?? 'Faculty - College of Engineering' }}</p>
                        <p class="text-xs text-gray-500">ID: {{ $vehicle['owner_id'] ?? 'FAC-2023-0042' }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="space-y-3">
            <button class="w-full py-3 bg-[#007C3D] hover:bg-[#007C3D] text-white font-medium rounded-lg transition-colors flex items-center justify-center shadow-sm" onclick="confirmVehicle()">
                <i class="fas fa-check mr-2" aria-hidden="true"></i> Confirm Record
            </button>
            
            <div class="grid grid-cols-2 gap-3">
                <button class="py-2.5 border border-amber-200 bg-amber-50 hover:bg-amber-100 text-amber-700 font-medium rounded-lg transition-colors flex items-center justify-center" onclick="flagVehicle()">
                    <i class="fas fa-flag mr-2" aria-hidden="true"></i> Flag
                </button>
                <button class="py-2.5 border border-red-200 bg-red-50 hover:bg-red-100 text-red-700 font-medium rounded-lg transition-colors flex items-center justify-center" onclick="denyVehicle()">
                    <i class="fas fa-ban mr-2" aria-hidden="true"></i> Deny
                </button>
            </div>
        </div>
    </div>
</div>
