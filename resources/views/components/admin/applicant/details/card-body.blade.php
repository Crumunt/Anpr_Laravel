@props([
    'cardTitle',
    'canEdit'
])

<div {{ $attributes->merge(['class' => 'bg-white rounded-lg shadow-sm border border-gray-200 transition-all duration-300 hover:shadow-md']) }}>
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800">
            <template x-if="typeof isEditing !== 'undefined' && isEditing">
                <span class="text-green-600">Editing: </span>
            </template>
            {{ $cardTitle }}
        </h2>

        <div class="card-actions">
            {{ $tableButton ?? '' }}
            @if($canEdit)
                <button
                    x-show="!isEditing"
                    @click="startEdit()"
                    type="button"
                    class="inline-flex items-center px-3 py-1.5 border border-green-600 text-sm font-medium rounded-md text-green-600 bg-white hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                >
                    <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </button>

                <div x-show="isEditing" class="space-x-2" style="display: none;">
                    <button
                        @click="saveChanges()"
                        type="button"
                        class="inline-flex items-center px-3 py-1.5 border border-green-600 text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none"
                    >
                        <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Save
                    </button>
                    <button
                        @click="cancelEdit()"
                        type="button"
                        class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none"
                    >
                        Cancel
                    </button>
                </div>
            @endif
        </div>
    </div>

    {{ $slot }}
</div>
