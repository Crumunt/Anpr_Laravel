@props(['items' => []])

<nav class="flex mb-6" aria-label="Breadcrumb">
  <ol class="inline-flex items-center space-x-1 md:space-x-3">
    @foreach($items as $index => $item)
      <li class="{{ $index === 0 ? 'inline-flex items-center' : '' }}">
        @if($index > 0)
          <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        @endif
        
        @if(isset($item['url']) && !($loop->last))
          <a href="{{ $item['url'] }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-green-600">
            @if($index === 0)
              <i class="fas fa-home mr-2"></i>
            @endif
            {{ $item['label'] }}
          </a>
        @else
          <span class="text-sm font-medium text-gray-500">{{ $item['label'] }}</span>
        @endif
        
        @if($index > 0)
          </div>
        @endif
      </li>
    @endforeach
  </ol>
</nav>