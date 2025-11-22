<?php

namespace App\Livewire\Admin\Modal;

use Livewire\Component;
use Livewire\WithFileUploads;

class Modal extends Component
{
    use WithFileUploads;


    public $showModal = false;
    public $currentStep = 1;

    // Form data
    public $full_name, $middle_name, $last_name, $phone, $email;
    public $regions = [], $provinces = [], $municipalities = [], $barangays = [];
    public $selectedRegion = null, $selectedProvince = null, $selectedMunicipality = null, $selectedBarangay = null;
    public $clsu_id, $department, $position, $applicant_type;
    public $vehicle_type, $make, $model, $color, $year, $plate_number;

    public $files = [];

    protected $rules = [
        'full_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'phone' => 'required|string|max:20',
        'email' => 'required|email',
        'applicant_type' => 'required|string',
        'selectedRegion' => 'required|string',
        'selectedProvince' => 'required|string',
        'selectedMunicipality' => 'required|string',
        'selectedBarangay' => 'required|string',
        'clsu_id' => 'required_if:applicant_type,student,faculty,staff',
        'department' => 'required_if:applicant_type,faculty,staff',
        'position' => 'required_if:applicant_type,faculty,staff',
        'vehicle_type' => 'required|string',
        'make' => 'required|string',
        'model' => 'required|string',
        'color' => 'required|string',
        'year' => 'required|integer|min:1900|max:2099',
        'plate_number' => 'required|string',
        'files.*' => 'file|max:10240', // 10MB max per file
    ];

    public function mount()
    {
        $raw = file_get_contents(storage_path('app/json/cluster.json'));
        $this->regions = json_decode($raw, true);
    }

    public function updatedSelectedRegion($value)
    {
        if (!isset($this->regions[$value])) {
            $this->provinces = [];
            return;
        }

        $this->provinces = array_keys($this->regions[$value]['province_list']);
    }

    public function updatedSelectedProvince($value)
    {
        if (!$this->selectedRegion || !isset($this->regions[$this->selectedRegion]['province_list'][$value])) {
            $this->municipalities = [];
            return;
        }

        $this->municipalities = array_keys($this->regions[$this->selectedRegion]['province_list'][$value]['municipality_list']);
    }

    public function updatedSelectedMunicipality($value)
    {
        if (!$this->selectedProvince || !isset($this->regions[$this->selectedRegion]['province_list'][$this->selectedProvince]['municipality_list'][$value])) {
            $this->barangays = [];
            return;
        }

        $this->barangays = $this->regions[$this->selectedRegion]['province_list'][$this->selectedProvince]['municipality_list'][$this->selectedMunicipality]['barangay_list'];
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
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
        $this->validate();

        // TODO: Save applicant and files

        $this->resetForm();
        $this->showModal = false;

        session()->flash('message', 'Applicant successfully added!');
    }

    public function resetForm()
    {
        $this->reset([
            'full_name',
            'middle_name',
            'last_name',
            'phone',
            'email',
            'clsu_id',
            'department',
            'position',
            'selectedRegion',
            'selectedProvince',
            'selectedMunicipality',
            'selectedBarangay',
            'applicant_type',
            'vehicle_type',
            'make',
            'model',
            'color',
            'year',
            'plate_number',
            'files',
            'currentStep',
        ]);
        $this->resetValidation();
    }

    private function getStepRules()
    {
        return match ($this->currentStep) {
            1 => array_intersect_key(
                $this->rules,
                array_flip(['full_name', 'last_name', 'phone', 'email', 'applicant_type', 'clsu_id', 'department', 'position', 'selectedRegion', 'selectedProvince', 'selectedMunicipality', 'selectedBarangay'])
            ),
            2 => array_intersect_key(
                $this->rules,
                array_flip(['vehicle_type', 'make', 'model', 'color', 'year', 'plate_number'])
            ),
            3 => array_intersect_key(
                $this->rules,
                array_flip(['files.*'])
            ),
            default => [],
        };
    }

    public function render()
    {
        return view('livewire.admin.modal.modal');
    }
}
