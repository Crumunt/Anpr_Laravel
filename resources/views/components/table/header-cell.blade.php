@props([
    'label' => '',
    'text_alignment' => 'left',
])

<th class="h-12 px-4 text-{{ $text_alignment }} align-middle font-medium text-gray-500 hover:text-green-600 transition-colors">
    {{ $label }}
</th>