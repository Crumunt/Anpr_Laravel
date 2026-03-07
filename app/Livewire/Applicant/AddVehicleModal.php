<?php

namespace App\Livewire\Applicant;

use App\Models\ApplicantType;
use App\Models\Status;
use App\Models\User;
use App\Rules\UniquePlateNumber;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class AddVehicleModal extends Component
{
    use WithFileUploads;

    public bool $showModal = false;
    public int $currentStep = 1;
    public int $totalSteps = 3;

    // Vehicle details
    public $vehicle_type, $make, $model, $color, $year, $plate_number;

    // Dynamic files array
    public $files = [];

    // Required documents for the applicant type (loaded dynamically)
    public $requiredDocuments = [];

    public function mount()
    {
        $this->loadRequiredDocuments();
    }

    /**
     * Load required documents based on the user's applicant type.
     */
    public function loadRequiredDocuments()
    {
        $user = Auth::user();
        $applicantTypeId = $user->applications()->first()?->applicant_type_id;

        if ($applicantTypeId) {
            $applicantType = ApplicantType::with('requiredDocuments')->find($applicantTypeId);
            $this->requiredDocuments = $applicantType?->requiredDocuments?->toArray() ?? [];
        }
    }

    #[On('openAddVehicleModal')]
    public function openModal()
    {
        $this->resetForm();
        $this->loadRequiredDocuments();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    /**
     * Build dynamic validation rules for documents based on applicant type.
     */
    protected function getDocumentValidationRules(): array
    {
        $rules = [];

        foreach ($this->requiredDocuments as $document) {
            $docName = $document['name'];
            $mimes = $document['accepted_formats'] ?? 'pdf,jpg,jpeg,png';
            $maxSize = $document['max_file_size'] ?? 10240;
            $isRequired = $document['is_required'] ?? true;

            if ($isRequired) {
                $rules["files.{$docName}"] = "required|array|min:1";
            } else {
                $rules["files.{$docName}"] = "nullable|array";
            }

            $rules["files.{$docName}.*"] = "file|mimes:{$mimes}|max:{$maxSize}";
        }

        return $rules;
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
                "plate_number" => ["required", "string", "max:20", new UniquePlateNumber()],
            ],
            2 => $this->getDocumentValidationRules(),
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
            "currentStep"
        ]);
        $this->currentStep = 1;
        $this->resetValidation();
    }

    /**
     * Handle dynamic file uploads for any document type.
     */
    public function updatedFiles($value, $key)
    {
        // The $key will be the document name (e.g., 'vehicle_registration')
        // Files are automatically bound to $this->files[$key]
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
                $applicant_type_id = $user->applications()->first()?->applicant_type_id;

                // Get statuses
                $application_status = Status::applicationPending();
                $vehicle_status = Status::vehiclePending();

                // Create application
                $application = $user->applications()->create([
                    'user_id' => $user_id,
                    'applicant_type_id' => $applicant_type_id,
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
