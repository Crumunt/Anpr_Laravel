<?php

namespace App\Livewire\Applicant;

use App\Models\Status;
use App\Models\User;
use App\Models\Vehicle\Vehicle;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class RenewGatePassModal extends Component
{
    use WithFileUploads;

    public bool $showModal = false;
    public int $currentStep = 1;
    public int $totalSteps = 2;

    public ?string $vehicleId = null;
    public ?Vehicle $vehicle = null;

    // Document uploads
    public $files = [
        'vehicle_registration' => [],
        'license' => [],
        'proof_of_identification' => [],
    ];

    protected $listeners = ['refreshRenewalModal' => '$refresh'];

    #[On('openRenewGatePassModal')]
    public function openModal(string $vehicleId)
    {
        $this->resetForm();
        $this->vehicleId = $vehicleId;
        $this->vehicle = Vehicle::with(['user', 'application', 'status'])->find($vehicleId);

        if (!$this->vehicle) {
            $this->dispatch('toast', message: 'Vehicle not found.', type: 'error', duration: 3000);
            return;
        }

        if (!$this->vehicle->canRenew()) {
            $this->dispatch('toast', message: 'This vehicle is not eligible for renewal.', type: 'warning', duration: 3000);
            return;
        }

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
            'vehicleId',
            'vehicle',
            'files',
            'currentStep',
        ]);
        $this->currentStep = 1;
        $this->files = [
            'vehicle_registration' => [],
            'license' => [],
            'proof_of_identification' => [],
        ];
        $this->resetValidation();
    }

    public function removeFile($type, $index)
    {
        if (isset($this->files[$type][$index])) {
            unset($this->files[$type][$index]);
            $this->files[$type] = array_values($this->files[$type]);
        }
    }

    public function submitRenewal()
    {
        try {
            if (!$this->vehicle || !$this->vehicle->canRenew()) {
                $this->dispatch('toast', message: 'This vehicle is not eligible for renewal.', type: 'error', duration: 3000);
                return;
            }

            $user = User::findOrFail(Auth::id());

            DB::transaction(function () use ($user) {
                // Get statuses
                $applicationStatus = Status::applicationPending();

                // Create a renewal application (for admin review workflow)
                $application = $user->applications()->create([
                    'user_id' => $user->id,
                    'applicant_type' => $this->vehicle->application?->applicant_type ?? 'regular',
                    'status_id' => $applicationStatus->id,
                ]);

                // Mark the existing vehicle as having a pending renewal
                $this->vehicle->update([
                    'has_pending_renewal' => true,
                    'pending_renewal_application_id' => $application->id,
                    'renewal_requested_at' => now(),
                ]);

                // Process and store documents - link directly to the vehicle
                $fileRecords = [];
                foreach ($this->files as $fileType => $files) {
                    foreach ($files as $file) {
                        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                        $storePath = "application/{$user->id}/{$application->id}";
                        $finalPath = "{$storePath}/" . $filename;

                        $file->storeAs($storePath, $filename, 'local');

                        $fileRecords[] = [
                            'vehicle_id' => $this->vehicle->id,
                            'type' => $fileType,
                            'file_path' => $finalPath,
                            'mime_type' => $file->getMimeType(),
                            'file_size' => $file->getSize(),
                            'status_id' => $applicationStatus->id,
                            'is_renewal_document' => true,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }

                if (!empty($fileRecords)) {
                    $application->documents()->createMany($fileRecords);
                }

                // Log the renewal request
                ActivityLogService::log(
                    $user,
                    "Gate pass renewal requested for vehicle {$this->vehicle->plate_number}",
                    'vehicle',
                    $user,
                    ['vehicle_id' => $this->vehicle->id, 'application_id' => $application->id]
                );
            });

            $this->closeModal();
            $this->dispatch('vehicleRenewalRequested');
            $this->dispatch('toast',
                message: 'Gate pass renewal submitted successfully! Please wait for admin approval.',
                type: 'success',
                duration: 5000
            );

            return redirect()->route('applicant.dashboard');
        } catch (\Exception $e) {
            $this->dispatch('toast',
                message: 'Failed to submit renewal: ' . $e->getMessage(),
                type: 'error',
                duration: 5000
            );
        }
    }

    public function render()
    {
        return view('livewire.applicant.renew-gate-pass-modal');
    }
}
