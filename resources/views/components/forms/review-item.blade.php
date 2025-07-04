@props(['label', 'value' => ''])

<div>
    <span class="font-medium">{{ $label }}:</span> 
    {{ !empty($value) ? $value : $slot }}
</div>