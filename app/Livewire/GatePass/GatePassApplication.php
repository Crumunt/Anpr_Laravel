<?php

namespace App\Livewire\GatePass;

use App\Services\Admin\Applicants\ApplicantWriteService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Traits\HasPhilippineAddress;

#[Layout('layouts.app-layout')]
class GatePassApplication extends Component
{
    use WithFileUploads;
    use HasPhilippineAddress;

    public $currentStep = 1;
    public $applicationSubmitted = false;

    // Form data - Personal Info
    public $first_name, $middle_name, $last_name, $suffix, $phone, $email;
    public $zip_code;
    public $clsu_id, $department, $position, $applicant_type;

    // Vehicle Info
    public $vehicle_type, $make, $model, $color, $year, $plate_number;

    // Documents
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
        "email" => "required|email|unique:users,email",

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
            "nullable|required_if:applicant_type,faculty,staff,student|max:255",
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
            "required|file|mimes:pdf,jpg,jpeg,png|max:10240",
    ];

    protected $messages = [
        'email.unique' => 'This email address is already registered. Please use a different email or login to your existing account.',
        'first_name.required' => 'Please enter your first name.',
        'last_name.required' => 'Please enter your last name.',
        'phone.required' => 'Please enter your phone number.',
        'email.required' => 'Please enter your email address.',
        'email.email' => 'Please enter a valid email address.',
        'applicant_type.required' => 'Please select an applicant type.',
        'selectedRegion.required' => 'Please select your region.',
        'selectedProvince.required' => 'Please select your province.',
        'selectedMunicipality.required' => 'Please select your city/municipality.',
        'selectedBarangay.required' => 'Please select your barangay.',
        'zip_code.required' => 'Please enter your zip code.',
        'zip_code.digits' => 'Zip code must be exactly 4 digits.',
        'vehicle_type.required' => 'Please enter the vehicle type.',
        'make.required' => 'Please enter the vehicle make.',
        'model.required' => 'Please enter the vehicle model.',
        'color.required' => 'Please enter the vehicle color.',
        'year.required' => 'Please enter the vehicle year.',
        'plate_number.required' => 'Please enter the plate number.',
    ];

    protected $applicantWriteService;

    public function boot(ApplicantWriteService $applicantWriteService)
    {
        $this->applicantWriteService = $applicantWriteService;
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
            true // Send invitation email
        );

        $created = $result['user'];
        $emailSent = $result['emailSent'];

        if (!$created || !$created->hasRole("applicant")) {
            session()->flash('error', 'An error occurred while processing your application. Please try again.');
            return;
        }

        // Mark application as submitted
        $this->applicationSubmitted = true;
        $this->resetForm();

        // Store email sent status for display
        session()->flash('emailSent', $emailSent);
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
            "vehicle_registration",
            "license",
            "proof_of_identification",
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
        return view("livewire.gate-pass.gate-pass-application");
    }
}
