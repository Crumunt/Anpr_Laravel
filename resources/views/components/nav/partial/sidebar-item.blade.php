<!-- /resources/views/components/nav-item.blade.php -->
@props([
    'title' => 'Default',
    'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
    'routeName' => '',
    'url' => '#',
    'action' => false,
    'badge' => false,
    'badgeCount' => 0,
    'fontAwesomeIcon' => false,
    'fontAwesomeClass' => 'fa-home'
])
@php
    $isActive = request()->url() === $url || (Route::has($routeName) && Route::currentRouteName() == $routeName);
    $elementType = $action ? 'button' : 'a';
    $href = $url;
    
    if (!empty($routeName) && Route::has($routeName)) {
        try {
            $href = route($routeName);
        } catch (\Exception $e) {
            // Fallback to URL
        }
    }
    
    $attributes = $action 
        ? ['type' => 'button', 'onclick' => $action] 
        : ['href' => $href];

    $classes = $isActive 
        ? 'sidebar-item active' 
        : 'sidebar-item';
@endphp
<{{ $elementType }}
    @foreach($attributes as $key => $value)
        {{$key}}="{{$value}}"
    @endforeach
    class="flex items-center px-4 py-3 text-white rounded-lg {{$classes}}"
    @if($isActive) aria-current="page" @endif
>
    <div class="bg-white bg-opacity-10 w-8 h-8 rounded-md flex items-center justify-center">
        @if($fontAwesomeIcon)
            <i class="fas {{ $fontAwesomeClass }}" aria-hidden="true"></i>
        @else
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{$icon}}"></path>
            </svg>
        @endif
    </div>
    <span class="ml-3 font-medium">{{$title}}</span>
    
    @if($badge && $badgeCount > 0)
    <span class="ml-auto bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full" aria-label="{{ $badgeCount }} new items">
        {{ $badgeCount }}
    </span>
    @endif
</{{ $elementType }}>