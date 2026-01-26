@props([
    'title' => '',
    'count' => 0,
    'icon' => '',
    'iconGradient' => 'linear-gradient(135deg, #e6f7ef, #d1f0e2);',
    'iconColor' => 'clsu-text',
    'percent' => null,
    'percentColor' => 'text-green-600',
    'percentIcon' => 'fa-arrow-up',
    'percentText' => 'from yesterday',
    'progress' => 0,
    'progressBarColor' => 'clsu-bg',
    'progressBg' => 'bg-gray-100',
])

<div class="glass-card p-6 relative overflow-hidden">
    <div class="flex justify-between items-start">
        <div>
            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">{{ $title }}</p>
            <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($count) }}</h3>
        </div>
        <div class="p-3 rounded-lg flex items-center justify-center" style="background: {{ $iconGradient }};">
            <i class="fas {{ $icon }} text-xl {{ $iconColor }}" aria-hidden="true"></i>
        </div>
    </div>
    <div class="flex items-center mt-5">
        @if($percent !== null)
            <span class="{{ $percentColor }} text-sm font-medium flex items-center">
                <i class="fas {{ $percentIcon }} mr-1" aria-hidden="true"></i> {{ $percent }}%
            </span>
        @endif
        <span class="text-gray-500 text-sm ml-2">{{ $percentText }}</span>
    </div>
    <div class="w-full {{ $progressBg }} rounded-full h-1.5 mt-4 overflow-hidden" role="progressbar" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
        <div class="{{ $progressBarColor }} h-1.5 rounded-full progress-bar" style="width: {{ $progress }}%"></div>
    </div>
</div>
