@forelse ($rows as $index => $row)
    <tr @click="toggleRow({{ $index }}, $event)"
        x-data="toggleDeselectAll()"
        class="group relative cursor-pointer transition-all duration-300 hover:bg-green-50/80 hover:shadow-sm hover:-translate-y-0.5"
        :class="{ 'bg-green-50/60': isSelected({{ $index }}) }">

        @if ($showCheckboxes)
            <x-table.data-cell>
                <x-table.checkbox-cell :data-id="$row['id']" :index="$index" :is-selected="'isSelected'" />
            </x-table.data-cell>
        @endif

        @foreach ($headers as $header)
            @php
                $key = $header['key'] ?? $header['label'];
                $value = $row[$key] ?? null;
            @endphp

            <x-table.data-cell :class="$key == 'user_id' ? 'font-medium' : ''">

                <x-table.cell-renderer :value="$value" />

            </x-table.data-cell>

        @endforeach
        @if ($showActions)
            <x-table.data-cell class="text-right">
                <x-row-action-menu :index="$index" />
            </x-table.data-cell>
        @endif
    </tr>
@empty
    <tr>
        <td colspan="{{ count($headers) + 2 }}"
            class="text-center text-lg font-semibold text-gray-500 bg-gray-50 px-6 py-6 rounded-lg shadow-sm border border-dashed border-gray-300 animate-pulse">
            <div class="flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9.75 9.75h.008v.008H9.75V9.75zM14.25 9.75h.008v.008h-.008V9.75zM9 13.5h6M4.5 6.75A2.25 2.25 0 016.75 4.5h10.5a2.25 2.25 0 012.25 2.25v10.5a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 17.25V6.75z" />
                </svg>
                <span>No data found</span>
            </div>
        </td>
    </tr>
@endforelse