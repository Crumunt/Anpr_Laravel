<?php

namespace App\Livewire\Applicant;

use App\Models\Status;
use App\Models\User;
use App\Traits\HasVehicleDetails;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;

class AddVehicleModal extends Component
{
    use HasVehicleDetails;

    public bool $showModal = false;
    public int $currentStep = 1;
    public int $totalSteps = 3;

    #[On('openAddVehicleModal')]
    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function nextStep()
    {
        $validationRules = match ($this->currentStep) {
            1 => [
                "vehicle_type" => "required|string|max:50",
                "make" => "required|string|max:100",
                "model" => "required|string|max:100",
                "color" => "required|string|max:50",
                "year" => "required|integer|min:1900|max:" . (date('Y') + 1),
                "plate_number" => "required|string|max:20",
            ],
            2 => [
                "files.vehicle_registration" => "required|array|min:1",
                "files.vehicle_registration.*" => "file|mimes:pdf,jpg,jpeg,png|max:10240",
                "files.license" => "required|array|min:1",
                "files.license.*" => "file|mimes:pdf,jpg,jpeg,png|max:10240",
                "files.proof_of_identification" => "required|array|min:1",
                "files.proof_of_identification.*" => "file|mimes:pdf,jpg,jpeg,png|max:10240",
            ],
            default => []
        };

        $this->validate($validationRules);
        $this->currentStep++;
    }

    public function prevStep()
    {
        $this->currentStep--;
        $this->resetErrorBag();
    }

    public function resetForm()
    {
        $this->reset([
            "vehicle_type",
            "make",
            "model",
            "color",
            "year",
            "plate_number",
            "files",
            "vehicle_registration",
            "license",
            "proof_of_identification",
            "currentStep"
        ]);
        $this->currentStep = 1;
        $this->resetValidation();
    }

    public function removeFile($type, $index)
    {
        if (isset($this->files[$type][$index])) {
            unset($this->files[$type][$index]);
            $this->files[$type] = array_values($this->files[$type]);
        }
    }

    public function submitForm()
    {
        try {
            $user = User::findOrFail(Auth::id());

            $created = DB::transaction(function () use ($user) {
                $user_id = $user->id;
                $applicant_type = $user->applications()->first()?->applicant_type ?? 'regular';

                // Get statuses
                $application_status = Status::applicationPending();
                $vehicle_status = Status::vehiclePending();

                // Create application
                $application = $user->applications()->create([
                    'user_id' => $user_id,
                    'applicant_type' => $applicant_type,
                    'status_id' => $application_status->id
                ]);

                // Create vehicle
                $user->vehicles()->create([
                    "application_id" => $application->id,
                    "plate_number" => $this->plate_number,
                    "type" => $this->vehicle_type,
                    "make" => $this->make,
                    "model" => $this->model,
                    "year" => $this->year,
                    "color" => $this->color,
                    "status_id" => $vehicle_status->id,
                ]);

                // Process and store documents
                $file_records = [];
                foreach ($this->files as $file_type => $files) {
                    foreach ($files as $file) {
                        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                        $store_path = "application/{$user_id}/{$application->id}";
                        $final_path = "{$store_path}/" . $filename;

                        $file->storeAs($store_path, $filename, 'local');

                        $file_records[] = [
                            "type" => $file_type,
                            "file_path" => $final_path,
                            'mime_type' => $file->getMimeType(),
                            'file_size' => $file->getSize(),
                            'status_id' => $application_status->id,
                            "created_at" => now(),
                            "updated_at" => now(),
                        ];
                    }
                }

                if (!empty($file_records)) {
                    $application->documents()->createMany($file_records);
                }

                return $application;
            });

            $this->closeModal();
            $this->dispatch('vehicleAdded');
            $this->dispatch('toast', message: 'Vehicle registration submitted successfully! Please wait for approval.', type: 'success', duration: 5000);

            // Redirect to refresh the page
            return redirect()->route('applicant.dashboard');
        } catch (\Exception $e) {
            $this->dispatch('toast', message: 'Failed to register vehicle: ' . $e->getMessage(), type: 'error', duration: 5000);
        }
    }

    public function render()
    {
        return view('livewire.applicant.add-vehicle-modal');
    }
}
