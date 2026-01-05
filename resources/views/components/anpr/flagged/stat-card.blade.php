@props(['title', 'count', 'change', 'isIncrease' => true, 'icon', 'color'])
<div class="glass-card p-6 relative overflow-hidden transform hover:scale-105 transition-transform duration-200 shadow-md hover:shadow-lg">
    <div class="flex justify-between items-start">
        <div>
            <p class="text-sm font-medium text-gray-600 uppercase tracking-wider">{{ $title }}</p>
            <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $count }}</h3>
        </div>
        <div class="p-3 rounded-lg flex items-center justify-center bg-gradient-to-br from-{{ $color }}-100 to-{{ $color }}-200" aria-hidden="true">
            <i class="fas {{ $icon }} text-xl text-{{ $color }}-600" aria-hidden="true"></i>
        </div>
    </div>
    <div class="flex items-center mt-5">
        <span class="text-sm font-medium flex items-center {{ $isIncrease ? 'text-green-600' : 'text-red-600' }}">
            <i class="fas {{ $isIncrease ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1" aria-hidden="true"></i> {{ $change }}
        </span>
        <span class="text-gray-600 text-sm ml-2">from last week</span>
    </div>
</div>