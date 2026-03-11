<?php

namespace App\Traits;

use App\Rules\UniquePlateNumber;
use Livewire\WithFileUploads;

trait HasVehicleDetails
{

    use WithFileUploads;

    public $vehicle_type, $make, $model, $color, $year, $plate_number;
    public $vehicle_registration = [],
        $license = [],
        $proof_of_identification = [];

    public $files = [];

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

    /**
     * Remove a file from the vehicle document upload (vehicle_registration, license, proof_of_identification).
     */
    public function removeVehicleFile($type, $index)
    {
        $validTypes = ['vehicle_registration', 'license', 'proof_of_identification'];

        if (!in_array($type, $validTypes)) {
            return;
        }

        if (isset($this->{$type}[$index])) {
            unset($this->{$type}[$index]);
            $this->{$type} = array_values($this->{$type});

            // Also update the files array
            if (isset($this->files[$type])) {
                $this->files[$type] = $this->{$type};
            }
        }
    }

    protected function vehicleRules()
    {
        return [
            "vehicle_type" => "required|string|max:50",
            "make" => "required|string|max:100",
            "model" => "required|string|max:100",
            "color" => "required|string|max:50",
            "year" => "required|integer|min:1900",
            "plate_number" => ["required", "string", "max:20", new UniquePlateNumber()],

            // Files / Documents
            "files.vehicle_registration.*" =>
            "required|file|mimes:pdf,jpg,jpeg,png|max:10240",
            "files.license.*" => "required|file|mimes:pdf,jpg,jpeg,png|max:10240",
            "files.proof_of_identification.*" =>
            "required|file|mimes:pdf,jpg,jpeg,png|max:10240", // 10MB max per file
        ];
    }
}
