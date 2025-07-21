@foreach ($rows as $index => $row)
    <tr @click="toggleRow({{ $index }}, $event)"
        class="group relative cursor-pointer transition-all duration-300 hover:bg-green-50/80 hover:shadow-sm hover:-translate-y-0.5"
        :class="{ 'bg-green-50/60': isSelected({{ $index }}) }">

        @if ($showCheckboxes)
            <x-table.data-cell>
                <x-table.checkbox-cell :index="$index" :is-selected="'isSelected'" />
            </x-table.data-cell>
        @endif

        @foreach ($headers as $header)
            @php
                $key = $header['key'] ?? $header['label'];
                $value = $row[$key] ?? null;
            @endphp

            <x-table.data-cell>

                <x-table.cell-renderer :value="$value" />

            </x-table.data-cell>

        @endforeach
        @if ($showActions)
            <x-table.data-cell class="text-right">
                <x-row-action-menu :index="$index" />
            </x-table.data-cell>
        @endif
    </tr>
@endforeach