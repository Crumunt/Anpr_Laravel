@props([
    'title',
    'totalNumber',
    'description',
    'icon',
    'percent',
])

@php
    $upwardTrend = $percent > 0;
    $arrowPath = $upwardTrend ? 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6' : 'M13 17h8m0 0V9m0 8l-8-8-4 4-6-6';
    $cardColor = $upwardTrend ? 'green' : 'red';
@endphp

<div>
    <div class="group relative rounded-2xl bg-white border-{{ $cardColor }}-100 transition-all duration-300 hover:border-transparent hover:-translate-y-1 shadow-sm hover:shadow-lg overflow-hidden hover:bg-gradient-to-br from-white to-{{ $cardColor }}-100">
        <div class="absolute inset-0 bg-{{ $cardColor }}-100 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        <div class="p-5 sm:p-6">
            <div class="flex justify-between items-start">
                <div class="relative z-10">
                    <p class="text-sm font-medium text-gray-500 mb-2 transition-colors duration-300 group-hover:text-{{ $cardColor }}-700">
                        {{ $title }}
                    </p>
                    <h3 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-3 transition-colors duration-300 group-hover:text-{{ $cardColor }}-900">
                        {{ $totalNumber }}
                    </h3>
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center rounded-full bg-{{ $cardColor }}-100 px-2.5 sm:px-3 py-1 text-xs font-medium text-{{ $cardColor }}-700 transition-all duration-300 group-hover:scale-105">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1 -ml-1 motion-safe:animate-pulse" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="{{ $arrowPath }}" />
                            </svg>
                            {{ $percent }}%
                        </span>
                        <span class="text-xs text-gray-500 transition-colors duration-300 group-hover:text-gray-600">from last month</span>
                    </div>
                </div>
                <div class="p-2.5 sm:p-3 rounded-full bg-{{ $cardColor }}-100 backdrop-blur transition-all duration-300 group-hover:bg-{{ $cardColor }}-200 group-hover:scale-110">
                    <x-icons.card-icon :icon="$icon" :color="$cardColor" />
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-3 sm:mt-4 opacity-80 transition-colors duration-300 group-hover:text-gray-600">
                {{ $description }}
            </p>
        </div>
    </div>
</div>
