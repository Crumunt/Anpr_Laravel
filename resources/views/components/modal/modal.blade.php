@props([
'id' => 'entity-modal',
'type' => 'applicant', // Options: applicant, vehicle, rfid, admin, document_upload
'maxWidth' => '2xl',
'entityId' => null, // For document upload modal
])
@php
$maxWidth = match($maxWidth) {
'sm' => 'max-w-sm',
'md' => 'max-w-md',
'lg' => 'max-w-lg',
'xl' => 'max-w-xl',
'2xl' => 'max-w-2xl',
'full' => 'max-w-full',
default => 'max-w-md'
};
$titles = [
'applicant' => 'Add Applicant',
'vehicle' => 'Add Vehicle',
'gate_pass' => 'Add RFID Tag',
'admin' => 'Add Administrator',
'document_upload' => 'Upload Document',
];
$title = $titles[$type] ?? 'Add Entry';
$formId = "{$type}-form";
@endphp
<div
    id="{{ $id }}"
    class="fixed inset-0 z-50 hidden"
    role="dialog"
    aria-modal="true"
    aria-labelledby="{{ $id }}-title"
    x-data="entityFormHandler('{{ $type }}', '{{ $entityId }}', '{{ $id }}')">
    <div class="fixed inset-0 transition-opacity" aria-hidden="true">
        <div class="absolute inset-0 bg-gray-800/70 backdrop-blur-sm transition-opacity duration-300 ease-in-out opacity-0 modal-backdrop"></div>
    </div>
    <div class="flex min-h-screen items-center justify-center p-4">
        <div
            class="bg-white rounded-xl shadow-2xl overflow-hidden transform transition-all scale-95 opacity-0 duration-300 ease-out {{ $maxWidth }} w-full modal-content border border-gray-100">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h2 id="{{ $id }}-title" class="text-xl font-semibold text-gray-800">{{ $title }}</h2>
                <button
                    class="text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-all duration-150 ease-in-out close-modal-btn focus:outline-none focus:ring-2 focus:ring-green-500 rounded-full p-2"
                    data-modal="{{ $id }}"
                    aria-label="Close">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="p-6 max-h-[70vh] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
                <form id="{{ $formId }}" @submit.prevent="submitForm">
                    @if ($type == 'applicant')
                        @include('components.modal.partials.applicant')                    
                    @elseif($type == 'admin')
                        @include('components.modal.partials.admin')
                    @else
                        @include('components.modal.partials.document-upload')
                    @endif
                </form>
            </div>

            {{ $controlSlot ?? null }}
        </div>
    </div>
</div>
<script src="{{ asset('js/components/modal.js') }}"></script>
<style>
    .modal-content input[type="text"],
    .modal-content input[type="email"],
    .modal-content input[type="password"],
    .modal-content input[type="tel"],
    .modal-content input[type="number"],
    .modal-content input[type="date"],
    .modal-content select,
    .modal-content textarea {
        transition: box-shadow 0.2s, border-color 0.2s;
        border-radius: 0.75rem;
        box-shadow: 0 1px 2px 0 rgba(60,60,60,0.04);
        background: #fafbfc;
    }
    .modal-content input:focus,
    .modal-content select:focus,
    .modal-content textarea:focus {
        border-color: #43A047;
        box-shadow: 0 0 0 2px #a7f3d0;
        background: #fff;
    }
    .modal-content input:hover,
    .modal-content select:hover,
    .modal-content textarea:hover {
        border-color: #a7f3d0;
    }
    .modal-content label {
        font-weight: 500;
        color: #374151;
    }
    .modal-content .shadow-sm {
        box-shadow: 0 1px 2px 0 rgba(60,60,60,0.04);
    }
</style>