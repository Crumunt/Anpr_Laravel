<?php

namespace App\Livewire\Admin\Modal;

use App\Models\ApplicantType;
use App\Services\Admin\Applicants\ApplicantWriteService;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Traits\HasPhilippineAddress;

class Modal extends Component
{
    use WithFileUploads;
    use HasPhilippineAddress;

    public $showModal = false;
    public $currentStep = 1;

    // Form data
    public $first_name, $middle_name, $last_name, $suffix, $phone, $email;
    public $zip_code;
    public $clsu_id, $department, $position, $applicant_type;
    public $vehicle_type, $make, $model, $color, $year, $plate_number;
    public $vehicle_registration = [],
        $license = [],
        $proof_of_identification = [];

    public $files = [];

    // Dynamic applicant types
    public $applicantTypes = [];
    public $selectedApplicantType = null;
    public $requiredDocuments = [];

    protected $rules = [
        // Personal / Profile Info
        "first_name" => "required|string|max:255",
        "middle_name" => "nullable|string|max:255",
        "last_name" => "required|string|max:255",
        "suffix" => "nullable|string|max:10",
        "phone" => "required|string|max:20",
        "email" => "required|email|unique:users",

        // Location
        "selectedRegion" => "required|string",
        "selectedProvince" => "required|string",
        "selectedMunicipality" => "required|string",
        "selectedBarangay" => "required|string",
        "zip_code" => "required|string|digits:4",

        // Applicant-specific
        "applicant_type" => "required|string|exists:applicant_types,id",
        "clsu_id" => "nullable|max:50",
        "department" => "nullable|max:255",
        "position" => "nullable|max:255",

        // Vehicle Info
        "vehicle_type" => "required|string|max:50",
        "make" => "required|string|max:100",
        "model" => "required|string|max:100",
        "color" => "required|string|max:50",
        "year" => "required|integer|min:1900",
        "plate_number" => "required|string|max:20",
    ];

    protected $applicantWriteService;

    public function boot(ApplicantWriteService $applicantWriteService)
    {
        $this->applicantWriteService = $applicantWriteService;
    }

    public function mount()
    {
        $this->loadApplicantTypes();
    }

    public function loadApplicantTypes()
    {
        $this->applicantTypes = ApplicantType::active()
            ->ordered()
            ->get();
    }

    public function updatedApplicantType($value)
    {
        $this->loadRequiredDocuments($value);
    }

    public function loadRequiredDocuments($applicantTypeId)
    {
        if (!$applicantTypeId) {
            $this->selectedApplicantType = null;
            $this->requiredDocuments = [];
            return;
        }

        $this->selectedApplicantType = ApplicantType::with('requiredDocuments')
            ->find($applicantTypeId);

        $this->requiredDocuments = $this->selectedApplicantType?->requiredDocuments ?? collect();

        // Reset files when applicant type changes
        $this->files = [];
    }

    public function updatedFiles($value, $key)
    {
        // Handle dynamic file uploads
        // $key format: "document_name" or "document_name.0"
    }

    public function openModal()
    {
        try {
            $this->resetForm();
            $this->showModal = true;
        } catch (\Exception $e) {
            $this->dispatch("log-action", $e->getMessage());
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function nextStep()
    {
        $this->validate($this->getStepRules());
        $this->currentStep++;
    }

    public function prevStep()
    {
        $this->currentStep--;
    }

    public function submitForm()
    {
        $validated = $this->validate();
        // TODO: Save applicant and files
        $uploadedTempPaths = [];
        foreach ($this->files as $file_type => $files) {
            foreach ($files as $file) {
                $uploadedTempPaths[] = [
                    "type" => $file_type,
                    "file" => $file,
                    "mime_type" => $file->getMimeType(),
                    "file_size" => $file->getSize(),
                ];
            }
        }

        $result = $this->applicantWriteService->create(
            $validated,
            $uploadedTempPaths,
        );

        $created = $result['user'];
        $emailSent = $result['emailSent'];

        if (!$created->hasRole("applicant")) {
            $this->dispatch("log-action", $created->role);
            return;
        }

        $this->resetForm();
        $this->showModal = false;

        // Show appropriate success message based on email status
        if ($emailSent) {
            $this->dispatch(
                "notify",
                message: "Applicant added successfully! Invitation email sent.",
                type: "success",
            );
        } else {
            $this->dispatch(
                "notify",
                message: "Applicant added successfully! (Invitation email could not be sent)",
                type: "warning",
            );
        }

        $this->dispatch("fetchCardData");
        $this->dispatch("refetchTableData");
    }

    public function resetForm()
    {
        $this->reset([
            "first_name",
            "middle_name",
            "last_name",
            "suffix",
            "phone",
            "email",
            "clsu_id",
            "department",
            "position",
            "selectedRegion",
            "selectedProvince",
            "selectedMunicipality",
            "selectedBarangay",
            "zip_code",
            "applicant_type",
            "vehicle_type",
            "make",
            "model",
            "color",
            "year",
            "plate_number",
            "files",
            "currentStep",
            "selectedApplicantType",
            "requiredDocuments",
        ]);
        $this->loadApplicantTypes();
        $this->resetValidation();
    }

    private function getStepRules()
    {
        $baseRules = match ($this->currentStep) {
            1 => $this->getStep1Rules(),
            2 => array_intersect_key(
                $this->rules,
                array_flip([
                    "vehicle_type",
                    "make",
                    "model",
                    "color",
                    "year",
                    "plate_number",
                ]),
            ),
            3 => $this->getDocumentRules(),
            default => [],
        };

        return $baseRules;
    }

    private function getStep1Rules()
    {
        $rules = array_intersect_key(
            $this->rules,
            array_flip([
                "first_name",
                "last_name",
                "phone",
                "email",
                "applicant_type",
                "selectedRegion",
                "selectedProvince",
                "selectedMunicipality",
                "selectedBarangay",
                "zip_code",
            ]),
        );

        // Add dynamic rules based on selected applicant type
        if ($this->selectedApplicantType) {
            if ($this->selectedApplicantType->requires_clsu_id) {
                $rules['clsu_id'] = 'required|max:50';
            }
            if ($this->selectedApplicantType->requires_department) {
                $rules['department'] = 'required|max:255';
            }
            if ($this->selectedApplicantType->requires_position) {
                $rules['position'] = 'required|max:255';
            }
        }

        return $rules;
    }

    private function getDocumentRules()
    {
        $rules = [];

        if ($this->requiredDocuments) {
            foreach ($this->requiredDocuments as $document) {
                $rulePrefix = $document->is_required ? 'required' : 'nullable';
                $rules["files.{$document->name}.*"] = "{$rulePrefix}|file|mimes:{$document->accepted_formats}|max:{$document->max_file_size}";
            }
        }

        return $rules;
    }

    public function render()
    {
        return view("livewire.admin.modal.modal", [
            'applicantTypeOptions' => $this->applicantTypes,
            'documentRequirements' => $this->requiredDocuments,
        ]);
    }
}
