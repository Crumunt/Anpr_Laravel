<?php

namespace App\Livewire\Admin\Settings;

use App\Models\ApplicantType;
use App\Models\ApplicantTypeDocument;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Str;

#[Layout('layouts.app-layout')]
class ApplicantTypeSettings extends Component
{
    // Modal states
    public bool $showTypeModal = false;
    public bool $showDocumentModal = false;
    public bool $showDeleteModal = false;

    // Editing states
    public ?string $editingTypeId = null;
    public ?string $editingDocumentId = null;
    public ?string $deletingTypeId = null;

    // Expanded accordions
    public array $expandedTypes = [];

    // Form data for applicant type
    public string $typeName = '';
    public string $typeLabel = '';
    public string $typeDescription = '';
    public bool $requiresClsuId = true;
    public bool $requiresDepartment = true;
    public bool $requiresPosition = false;
    public bool $isActive = true;

    // Form data for document
    public ?string $documentTypeId = null;
    public string $documentName = '';
    public string $documentLabel = '';
    public string $documentDescription = '';
    public string $acceptedFormats = 'pdf,jpg,jpeg,png';
    public int $maxFileSize = 10240;
    public bool $documentIsRequired = true;

    protected function rules()
    {
        $typeId = $this->editingTypeId;

        return [
            'typeName' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-z_]+$/',
                $typeId
                    ? "unique:applicant_types,name,{$typeId}"
                    : 'unique:applicant_types,name'
            ],
            'typeLabel' => 'required|string|max:100',
            'typeDescription' => 'nullable|string|max:500',
            'requiresClsuId' => 'boolean',
            'requiresDepartment' => 'boolean',
            'requiresPosition' => 'boolean',
            'isActive' => 'boolean',
        ];
    }

    protected function documentRules()
    {
        return [
            'documentTypeId' => 'required|exists:applicant_types,id',
            'documentName' => 'required|string|max:50|regex:/^[a-z_]+$/',
            'documentLabel' => 'required|string|max:100',
            'documentDescription' => 'nullable|string|max:500',
            'acceptedFormats' => 'required|string|max:100',
            'maxFileSize' => 'required|integer|min:1|max:51200',
            'documentIsRequired' => 'boolean',
        ];
    }

    protected $messages = [
        'typeName.regex' => 'Name must be lowercase with underscores only (e.g., visiting_professor)',
        'documentName.regex' => 'Name must be lowercase with underscores only (e.g., certificate_of_employment)',
    ];

    public function mount()
    {
        // Expand all types by default
        $this->expandedTypes = ApplicantType::pluck('id')->toArray();
    }

    public function toggleAccordion(string $typeId)
    {
        if (in_array($typeId, $this->expandedTypes)) {
            $this->expandedTypes = array_diff($this->expandedTypes, [$typeId]);
        } else {
            $this->expandedTypes[] = $typeId;
        }
    }

    public function openTypeModal(?string $typeId = null)
    {
        $this->resetTypeForm();

        if ($typeId) {
            $type = ApplicantType::find($typeId);
            if ($type) {
                $this->editingTypeId = $typeId;
                $this->typeName = $type->name;
                $this->typeLabel = $type->label;
                $this->typeDescription = $type->description ?? '';
                $this->requiresClsuId = $type->requires_clsu_id;
                $this->requiresDepartment = $type->requires_department;
                $this->requiresPosition = $type->requires_position;
                $this->isActive = $type->is_active;
            }
        }

        $this->showTypeModal = true;
    }

    public function closeTypeModal()
    {
        $this->showTypeModal = false;
        $this->resetTypeForm();
    }

    public function resetTypeForm()
    {
        $this->editingTypeId = null;
        $this->typeName = '';
        $this->typeLabel = '';
        $this->typeDescription = '';
        $this->requiresClsuId = true;
        $this->requiresDepartment = true;
        $this->requiresPosition = false;
        $this->isActive = true;
        $this->resetErrorBag();
    }

    public function saveType()
    {
        $this->validate($this->rules());

        $data = [
            'name' => $this->typeName,
            'label' => $this->typeLabel,
            'description' => $this->typeDescription ?: null,
            'requires_clsu_id' => $this->requiresClsuId,
            'requires_department' => $this->requiresDepartment,
            'requires_position' => $this->requiresPosition,
            'is_active' => $this->isActive,
        ];

        if ($this->editingTypeId) {
            $type = ApplicantType::find($this->editingTypeId);
            $type->update($data);
            session()->flash('success', 'Applicant type updated successfully.');
        } else {
            $data['sort_order'] = ApplicantType::max('sort_order') + 1;
            $type = ApplicantType::create($data);
            $this->expandedTypes[] = $type->id;
            session()->flash('success', 'Applicant type created successfully.');
        }

        $this->closeTypeModal();
    }

    public function openDocumentModal(string $typeId, ?string $documentId = null)
    {
        $this->resetDocumentForm();
        $this->documentTypeId = $typeId;

        if ($documentId) {
            $document = ApplicantTypeDocument::find($documentId);
            if ($document) {
                $this->editingDocumentId = $documentId;
                $this->documentName = $document->name;
                $this->documentLabel = $document->label;
                $this->documentDescription = $document->description ?? '';
                $this->acceptedFormats = $document->accepted_formats;
                $this->maxFileSize = $document->max_file_size;
                $this->documentIsRequired = $document->is_required;
            }
        }

        $this->showDocumentModal = true;
    }

    public function closeDocumentModal()
    {
        $this->showDocumentModal = false;
        $this->resetDocumentForm();
    }

    public function resetDocumentForm()
    {
        $this->editingDocumentId = null;
        $this->documentTypeId = null;
        $this->documentName = '';
        $this->documentLabel = '';
        $this->documentDescription = '';
        $this->acceptedFormats = 'pdf,jpg,jpeg,png';
        $this->maxFileSize = 10240;
        $this->documentIsRequired = true;
        $this->resetErrorBag();
    }

    public function saveDocument()
    {
        $this->validate($this->documentRules());

        $data = [
            'applicant_type_id' => $this->documentTypeId,
            'name' => $this->documentName,
            'label' => $this->documentLabel,
            'description' => $this->documentDescription ?: null,
            'accepted_formats' => $this->acceptedFormats,
            'max_file_size' => $this->maxFileSize,
            'is_required' => $this->documentIsRequired,
        ];

        if ($this->editingDocumentId) {
            $document = ApplicantTypeDocument::find($this->editingDocumentId);
            $document->update($data);
            session()->flash('success', 'Document requirement updated successfully.');
        } else {
            $data['sort_order'] = ApplicantTypeDocument::where('applicant_type_id', $this->documentTypeId)
                ->max('sort_order') + 1;
            ApplicantTypeDocument::create($data);
            session()->flash('success', 'Document requirement added successfully.');
        }

        $this->closeDocumentModal();
    }

    public function confirmDeleteType(string $typeId)
    {
        $this->deletingTypeId = $typeId;
        $this->showDeleteModal = true;
    }

    public function deleteType()
    {
        if ($this->deletingTypeId) {
            $type = ApplicantType::find($this->deletingTypeId);
            if ($type) {
                // Check if there are existing applications using this type
                if ($type->applications()->count() > 0) {
                    session()->flash('error', 'Cannot delete this applicant type because it has existing applications.');
                } else {
                    $type->delete();
                    session()->flash('success', 'Applicant type deleted successfully.');
                }
            }
        }

        $this->showDeleteModal = false;
        $this->deletingTypeId = null;
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->deletingTypeId = null;
    }

    public function deleteDocument(string $documentId)
    {
        $document = ApplicantTypeDocument::find($documentId);
        if ($document) {
            $document->delete();
            session()->flash('success', 'Document requirement removed successfully.');
        }
    }

    public function updateTypeLabel()
    {
        // Auto-generate name from label if name is empty
        if (empty($this->typeName) && !empty($this->typeLabel)) {
            $this->typeName = Str::snake(Str::lower($this->typeLabel));
        }
    }

    public function updateDocumentLabel()
    {
        // Auto-generate name from label if name is empty
        if (empty($this->documentName) && !empty($this->documentLabel)) {
            $this->documentName = Str::snake(Str::lower($this->documentLabel));
        }
    }

    public function render()
    {
        $applicantTypes = ApplicantType::with('requiredDocuments')
            ->orderBy('sort_order')
            ->get();

        return view('livewire.admin.settings.applicant-type-settings', [
            'applicantTypes' => $applicantTypes,
        ]);
    }
}
