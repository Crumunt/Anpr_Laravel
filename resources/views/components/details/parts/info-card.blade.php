
<!-- info-card.blade.php -->
@props([
    'title' => 'Information',
    'editButton' => true,
    'editId' => 'edit-info-btn',
    'cardId' => null
])
<div id="{{ $cardId ?? 'card-'.Str::slug($title) }}" class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden transition-all duration-300 hover:shadow-md">
  <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
    <h2 class="text-lg font-semibold text-gray-800">{{ $title }}</h2>
    @if($editButton)
    <div class="card-actions">
      <!-- View mode button -->
      <button 
        type="button"
        class="edit-button inline-flex items-center px-3 py-1.5 border border-green-600 text-sm font-medium rounded-md text-green-600 bg-white hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
      >
        <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
        </svg>
        Edit
      </button>
      
      <!-- Edit mode buttons (hidden by default) -->
      <div class="edit-actions hidden space-x-2">
        <button type="button" class="save-button inline-flex items-center px-3 py-1.5 border border-green-600 text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none">
          <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
          Save
        </button>
        <button type="button" class="cancel-button inline-flex items-center px-3 py-1.5 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
          Cancel
        </button>
      </div>
    </div>
    @endif
  </div>
  <div class="p-6">
    <form class="card-form">
      {{ $slot }}
    </form>
  </div>
</div>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const editableCards = document.querySelectorAll('.card-form');
    
    editableCards.forEach(card => {
        const cardContainer = card.closest('[id^="card-"]');
        if (!cardContainer) return;
        
        const editButton = cardContainer.querySelector('.edit-button');
        const saveButton = cardContainer.querySelector('.save-button');
        const cancelButton = cardContainer.querySelector('.cancel-button');
        const editActions = cardContainer.querySelector('.edit-actions');
        const cardTitle = cardContainer.querySelector('h2');
        const originalTitle = cardTitle ? cardTitle.textContent : '';
        let originalValues = {};

        // Edit Mode Toggle
        if (editButton) {
            editButton.addEventListener('click', function() {
                cardContainer.classList.add('ring-2', 'ring-green-200');
                if (cardTitle) cardTitle.innerHTML = `<span class="text-green-600">Editing:</span> ${originalTitle}`;

                // Store original values
                originalValues = {};
                card.querySelectorAll('.info-field').forEach(field => {
                    const input = field.querySelector('input, textarea, select');
                    if (input) originalValues[input.name] = input.value;
                    field.classList.add('bg-green-50', 'p-2', 'rounded-md', '-m-2');
                });

                // Toggle visibility
                card.querySelectorAll('.info-value').forEach(el => {
                    el.classList.add('opacity-0');
                    setTimeout(() => el.classList.add('hidden'), 150);
                });

                setTimeout(() => {
                    card.querySelectorAll('.info-edit').forEach(el => {
                        el.classList.remove('hidden');
                        setTimeout(() => el.classList.add('opacity-100'), 50);
                    });
                }, 150);

                // Toggle buttons
                editButton.classList.add('opacity-0');
                setTimeout(() => {
                    editButton.classList.add('hidden');
                    editActions.classList.remove('hidden');
                    editActions.classList.add('opacity-100');
                }, 150);
            });
        }

        // Cancel Edit
        if (cancelButton) {
            cancelButton.addEventListener('click', function() {
                Swal.fire({
                    title: 'Discard Changes?',
                    text: 'Any unsaved changes will be lost.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Discard',
                    cancelButtonText: 'Continue Editing'
                }).then((result) => {
                    if (result.isConfirmed) {
                        resetCardState();
                    }
                });
            });
        }

        // Save Changes
        if (saveButton) {
            saveButton.addEventListener('click', async function() {
                // Validate form
                let isValid = true;
                card.querySelectorAll('[required]').forEach(input => {
                    if (!input.value.trim()) {
                        input.classList.add('border-red-500', 'ring-red-200');
                        isValid = false;
                        showValidationError(input, 'This field is required');
                    } else {
                        input.classList.remove('border-red-500', 'ring-red-200');
                        clearValidationError(input);
                    }
                });

                if (!isValid) return;

                // Confirmation dialog
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

                // Show loading state
                Swal.fire({
                    title: 'Saving...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                try {
                    // Simulated save delay - REPLACE WITH ACTUAL AJAX CALL
                    await new Promise(resolve => setTimeout(resolve, 1000));

                    // Update display values
                    card.querySelectorAll('.info-field').forEach(field => {
                        const input = field.querySelector('input, textarea, select');
                        const display = field.querySelector('.info-value');
                        
                        if (input && display) {
                            display.textContent = input.tagName === 'SELECT' 
                                ? input.options[input.selectedIndex].text 
                                : input.value;
                        }
                    });

                    // Show success
                    Swal.fire({
                        icon: 'success',
                        title: 'Saved!',
                        showConfirmButton: false,
                        timer: 1500
                    });

                    resetCardState();
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Save Failed',
                        text: 'An error occurred while saving. Please try again.'
                    });
                }
            });
        }

        // Color picker synchronization
        card.querySelectorAll('input[type="color"]').forEach(colorInput => {
            const textInput = colorInput.parentNode.querySelector('[data-color-input]');
            if (!textInput) return;
            
            colorInput.addEventListener('input', () => textInput.value = colorInput.value);
            textInput.addEventListener('input', () => {
                if (/^#[0-9A-F]{6}$/i.test(textInput.value)) {
                    colorInput.value = textInput.value;
                    textInput.classList.remove('border-red-500');
                } else {
                    textInput.classList.add('border-red-500');
                }
            });
        });

        function resetCardState() {
            // Reset card styling
            cardContainer.classList.remove('ring-2', 'ring-green-200');
            if (cardTitle) cardTitle.textContent = originalTitle;

            // Reset form values
            for (const [name, value] of Object.entries(originalValues)) {
                const input = card.querySelector(`[name="${name}"]`);
                if (input) input.value = value;
            }

            // Toggle visibility
            card.querySelectorAll('.info-edit').forEach(el => {
                el.classList.remove('opacity-100');
                setTimeout(() => el.classList.add('hidden'), 150);
            });

            setTimeout(() => {
                card.querySelectorAll('.info-value').forEach(el => {
                    el.classList.remove('hidden');
                    setTimeout(() => el.classList.remove('opacity-0'), 50);
                });
                
                card.querySelectorAll('.info-field').forEach(field => {
                    field.classList.remove('bg-green-50', 'p-2', 'rounded-md', '-m-2');
                });
            }, 150);

            // Toggle buttons
            editActions.classList.remove('opacity-100');
            setTimeout(() => {
                editActions.classList.add('hidden');
                editButton.classList.remove('hidden');
                editButton.classList.remove('opacity-0');
            }, 150);
        }

        function showValidationError(input, message) {
            clearValidationError(input);
            const error = document.createElement('p');
            error.className = 'text-red-500 text-xs mt-1 validation-error';
            error.textContent = message;
            input.parentNode.appendChild(error);
        }

        function clearValidationError(input) {
            input.parentNode.querySelectorAll('.validation-error').forEach(el => el.remove());
        }
    });

    // Add transition styles
    const style = document.createElement('style');
    style.textContent = `
        .info-edit { opacity: 0; transition: opacity 150ms ease; }
        .info-edit.opacity-100 { opacity: 1; }
        .info-value { transition: opacity 150ms ease; }
        .edit-actions { opacity: 0; transition: opacity 150ms ease; }
        .edit-actions.opacity-100 { opacity: 1; }
    `;
    document.head.appendChild(style);
});
</script>