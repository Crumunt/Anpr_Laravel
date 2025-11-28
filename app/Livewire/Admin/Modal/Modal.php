<?php

namespace App\Livewire\Admin\Modal;

use App\Services\Application\SaveApplicationService;
use Livewire\Component;
use Livewire\WithFileUploads;

class Modal extends Component
{
    use WithFileUploads;

    public $showModal = false;
    public $currentStep = 1;

    // Form data
    public $first_name, $middle_name, $last_name, $suffix, $phone, $email;
    public $regions = [],
        $provinces = [],
        $municipalities = [],
        $barangays = [];
    public $selectedRegion = null,
        $selectedProvince = null,
        $selectedMunicipality = null,
        $selectedBarangay = null,
        $zip_code;
    public $clsu_id, $department, $position, $applicant_type;
    public $vehicle_type, $make, $model, $color, $year, $plate_number;
    public $vehicle_registration = [],
        $license = [],
        $proof_of_identification = [];

    public $files = [];

    protected $rules = [
        // Personal / Profile Info
        "first_name" => "required|string|max:255",
        "middle_name" => "nullable|string|max:255",
        "last_name" => "required|string|max:255",
        "suffix" => "nullable|string|max:10",
        "phone" => "required|string|max:20",
        "email" => "required|email",

        // Location
        "selectedRegion" => "required|string",
        "selectedProvince" => "required|string",
        "selectedMunicipality" => "required|string",
        "selectedBarangay" => "required|string",
        "zip_code" => "required|string|digits:4",

        // Applicant-specific
        "applicant_type" => "required|string",
        "clsu_id" =>
            "nullable|required_if:applicant_type,student,faculty,staff|max:50",
        "department" =>
            "nullable|required_if:applicant_type,faculty,staff|max:255",
        "position" =>
            "nullable|required_if:applicant_type,faculty,staff|max:255",

        // Vehicle Info
        "vehicle_type" => "required|string|max:50",
        "make" => "required|string|max:100",
        "model" => "required|string|max:100",
        "color" => "required|string|max:50",
        "year" => "required|integer|min:1900",
        "plate_number" => "required|string|max:20",

        // Files / Documents
        "files.vehicle_registration.*" =>
            "required|file|mimes:pdf,jpg,jpeg,png|max:10240",
        "files.license.*" => "required|file|mimes:pdf,jpg,jpeg,png|max:10240",
        "files.proof_of_identification.*" =>
            "required|file|mimes:pdf,jpg,jpeg,png|max:10240", // 10MB max per file
    ];

    protected $saveApplicationService;

    public function boot(SaveApplicationService $saveApplicationService)
    {
        $this->saveApplicationService = $saveApplicationService;
    }

    public function mount()
    {
        $raw = file_get_contents(storage_path("app/json/cluster.json"));
        $this->regions = json_decode($raw, true);
    }

    public function updatedSelectedRegion($value)
    {
        if (!isset($this->regions[$value])) {
            $this->provinces = [];
            return;
        }

        $this->provinces = array_keys($this->regions[$value]["province_list"]);
    }

    public function updatedSelectedProvince($value)
    {
        if (
            !$this->selectedRegion ||
            !isset(
                $this->regions[$this->selectedRegion]["province_list"][$value],
            )
        ) {
            $this->municipalities = [];
            return;
        }

        $this->municipalities = array_keys(
            $this->regions[$this->selectedRegion]["province_list"][$value][
                "municipality_list"
            ],
        );
    }

    public function updatedSelectedMunicipality($value)
    {
        if (
            !$this->selectedProvince ||
            !isset(
                $this->regions[$this->selectedRegion]["province_list"][
                    $this->selectedProvince
                ]["municipality_list"][$value],
            )
        ) {
            $this->barangays = [];
            return;
        }

        $this->barangays =
            $this->regions[$this->selectedRegion]["province_list"][
                $this->selectedProvince
            ]["municipality_list"][$this->selectedMunicipality][
                "barangay_list"
            ];
    }

    public function updatedVehicleRegistration($value)
    {
        $this->files["vehicle_registration"] = $value;
    }

    public function updatedLicense($value)
    {
        $this->files["license"] = $value;
    }

    public function updatedProofOfIdentification($value)
    {
        $this->files["proof_of_identification"] = $value;
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
                    'type' => $file_type,
                    'tmp_path' => $file->getRealPath()
                ];
            }
        }

        $created = $this->saveApplicationService->handle($validated, $uploadedTempPaths);

        $this->resetForm();
        $this->showModal = false;

        session()->flash("message", "Applicant successfully added!");
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
        ]);
        $this->resetValidation();
    }

    private function getStepRules()
    {
        return match ($this->currentStep) {
            1 => array_intersect_key(
                $this->rules,
                array_flip([
                    "first_name",
                    "last_name",
                    "phone",
                    "email",
                    "applicant_type",
                    "clsu_id",
                    "department",
                    "position",
                    "selectedRegion",
                    "selectedProvince",
                    "selectedMunicipality",
                    "selectedBarangay",
                    "zip_code",
                ]),
            ),
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
            3 => array_intersect_key(
                $this->rules,
                array_flip([
                    "files.vehicle_registration.*",
                    "files.license.*",
                    "files.proof_of_identification.*",
                ]),
            ),
            default => [],
        };
    }

    public function render()
    {
        return view("livewire.admin.modal.modal");
    }
}
