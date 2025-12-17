<x-admin.applicant.details.card-body
    x-data="{
        isEditing: false,

        startEdit() {
            this.isEditing = true;
        },

        async cancelEdit() {
            const result = await Swal.fire({
                title: 'Discard Changes?',
                text: 'Any unsaved changes will be lost.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Discard',
                cancelButtonText: 'Continue Editing'
            });

            if (result.isConfirmed) {
                this.isEditing = false;
            }
        },

        async saveChanges() {
            const { isConfirmed } = await Swal.fire({
                title: 'Save Changes?',
                text: 'Are you sure you want to save these changes?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#43A047',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Save',
                cancelButtonText: 'Cancel'
            });

            if (!isConfirmed) return;

            Swal.fire({
                title: 'Saving...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            try {
                this.isEditing = false;

                await $wire.call('save');

                await $wire.$refresh();

                Swal.fire({
                    icon: 'success',
                    title: 'Saved!',
                    showConfirmButton: false,
                    timer: 1500
                });
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Save Failed',
                    text: error.message || 'An error occurred while saving. Please try again.'
                });
            }
        }
    }"
    @validation-error.window="
        Swal.close();
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            text: $event.detail.message || 'Please check your input and try again.'
        });
    "
    ::class="{ 'ring-2 ring-green-200': isEditing }"
    :cardTitle="$cardTitle"
    :canEdit="$canEdit"
>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach ($this->fieldValues as $label => $value)
                @php
                    $config = $this->getFieldConfig($label);
                    $fieldName = $config['wire_model'] ?? Str::slug($label, '_');
                @endphp

                <div
                    class="info-field col-span-1 transition-all duration-300 ease-in-out"
                    :class="{ 'bg-green-50 p-2 rounded-md -m-2': isEditing }"
                >
                    <div class="text-sm font-medium text-gray-500 mb-1">
                        {{ $label }}
                        @if($config['required'] ?? false)
                            <span class="text-red-500">*</span>
                        @endif
                    </div>

                    <template x-if="!isEditing">
                        <div class="text-sm text-gray-900 py-1 px-0.5">
                            {{-- Use the live property value instead of cached $value --}}
                            {{ !empty($value) ? $value : "---" }}
                        </div>
                    </template>

                    <template x-if="isEditing">
                        <div>
                            @if($config['type'] === 'select')
                                <select
                                    name="{{ Str::slug($label) }}"
                                    wire:model.live="{{ $fieldName }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-20 transition-all duration-150 text-sm"
                                >
                                    <option value="">Select {{ $label }}</option>
                                    @foreach($config['options'] as $option)
                                        <option value="{{ $option['value'] }}">
                                            {{ $option['label'] }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <input
                                    type="{{ $config['type'] }}"
                                    name="{{ Str::slug($label) }}"
                                    wire:model="{{ $fieldName }}"
                                    placeholder="{{ !empty($this->{$fieldName}) ? $this->{$fieldName} : "---" }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-20 transition-all duration-150 text-sm"
                                >
                            @endif

                            @error($fieldName)
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </template>
                </div>
            @endforeach
        </div>
    </div>
</x-admin.applicant.details.card-body>

@once
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endonce

@script
<script>
Livewire.on('card-value', function(data) {
    console.log(data);
})
</script>
@endscript
