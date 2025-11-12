@props(['id', 'option' => ['testing'], 'skipKeys' => []])


<button id="{{ $id }}TypeFilterBtn" type="button"
    class="w-full flex justify-between items-center rounded-lg border px-3 py-2 text-sm bg-gray-50 border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/50 hover:bg-green-50 transition-all duration-200">
    <span class="selected-types-label">Select Types</span>
    <i class="fas fa-chevron-down text-gray-400 transition-transform duration-200"></i>
</button>
<div id="{{ $id }}TypeFilterDropdown"
    class="type-dropdown absolute left-0 mt-2 w-full p-3 bg-white border border-gray-100 rounded-lg shadow-lg z-10 hidden transition-all duration-200 opacity-0 transform scale-95">
    <div class="mb-2 pb-2 border-b border-gray-100">
        <label class="flex items-center gap-2 text-sm text-green-600 font-medium">
            <input type="checkbox" class="select-all-types form-checkbox text-green-500 rounded">
            Select All
        </label>
    </div>

    @php
        $checkLabel = count($option) !== count($option, COUNT_RECURSIVE) ? ($option[$id] ?? []) : $option;
    @endphp
    @foreach($checkLabel as $value => $label)
        @if (!in_array($value, $skipKeys))
            <label class="flex items-center gap-2 mb-2 hover:bg-green-50 p-1 rounded transition-colors">
                <input type="checkbox" name="{{ $id }}_types[]" value="{{ $value }}"
                    class="typeCheckbox {{ $id }}TypeCheckbox rounded text-green-500" {{ in_array($value, request($id . '_types', [])) ? 'checked' : '' }}>
                {{ $label }}
            </label>
        @endif
    @endforeach
</div>