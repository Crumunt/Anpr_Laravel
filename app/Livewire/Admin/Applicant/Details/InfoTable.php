<?php

namespace App\Livewire\Admin\Applicant\Details;

use App\Http\Resources\ApplicationDetailsResource;
use App\Http\Resources\VehicleResource;
use App\Models\Application;
use App\Models\ApplicantType;
use App\Models\ApplicantTypeDocument;
use App\Models\Status;
use App\Models\User;
use App\Models\Vehicle\Vehicle;
use App\Notifications\ApplicationRejectedNotification;
use App\Rules\UniquePlateNumber;
use App\Services\Admin\Applicants\ApplicantReadService;
use App\Services\Admin\Vehicles\VehicleWriteService;
use App\Services\Application\SaveVehicleService;
use App\Services\ActivityLogService;
use App\Traits\HasVehicleDetails;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use League\Config\Exception\ValidationException;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class InfoTable extends Component
{
    use HasVehicleDetails;
    use WithPagination;

    protected string $paginationTheme = 'tailwind';

    public string $tableId;
    public string $cardTitle = "";
    public array $headers = [];
    public $rows = [];
    public bool $canCreate = false;
    public bool $canApprove = false;
    public bool $canDelete = false;
    public string $userId = '';
    public string $type = 'applicant';
    public $page = 1;
    public string $selectedStatusFilter = '';

    public $application;

    protected array $config = [
        'applicant' => [
            "model" => User::class,
            "resource" => ApplicationDetailsResource::class,
            "relation" => 'applications',
        ],
        'vehicle' => [
            "model" => User::class,
            "resource" => VehicleResource::class,
            "relation" => 'vehicles'
        ],
    ];

    public $currentStep = 1;

    protected $rules = [];

    protected $vehicleWriteService;

    // Dynamic document uploads for applicant type
    public $applicantType = null;
    public $requiredDocuments = [];
    public $documentFiles = [];

    public function boot(VehicleWriteService $vehicleWriteService)
    {
        $this->vehicleWriteService = $vehicleWriteService;
    }

    public function mount()
    {
        // Set permission defaults based on authenticated user if not explicitly passed
        if (!$this->canApprove) {
            $this->canApprove = auth()->user()->can('approve applicants');
        }
        if (!$this->canDelete) {
            $this->canDelete = auth()->user()->hasAnyRole(['super_admin', 'admin_editor']);
        }

        $this->loadData();
        $this->loadApplicantTypeDocuments();
    }

    /**
     * Load the applicant type and its required documents for the user
     */
    public function loadApplicantTypeDocuments()
    {
        $user = User::with(['applications.applicantTypeModel.requiredDocuments'])->find($this->userId);

        if (!$user) {
            return;
        }

        // Get the applicant type from the user's first application
        $firstApplication = $user->applications()->with('applicantTypeModel.requiredDocuments')->first();

        if ($firstApplication && $firstApplication->applicantTypeModel) {
            $this->applicantType = $firstApplication->applicantTypeModel;
            $this->requiredDocuments = $firstApplication->applicantTypeModel->requiredDocuments->toArray();
        } else {
            // Fallback: load default required documents (vehicle_registration, license, proof_of_identification)
            $this->requiredDocuments = [
                ['name' => 'vehicle_registration', 'label' => 'Vehicle Registration', 'description' => 'Official vehicle registration document', 'accepted_formats' => 'pdf,jpg,jpeg,png', 'max_file_size' => 10240, 'is_required' => true],
                ['name' => 'license', 'label' => "Driver's License", 'description' => 'Valid driver\'s license', 'accepted_formats' => 'pdf,jpg,jpeg,png', 'max_file_size' => 10240, 'is_required' => true],
                ['name' => 'proof_of_identification', 'label' => 'Proof of Identification', 'description' => 'Valid ID (CLSU ID, National ID, etc.)', 'accepted_formats' => 'pdf,jpg,jpeg,png', 'max_file_size' => 10240, 'is_required' => true],
            ];
        }

        // Initialize document files array
        foreach ($this->requiredDocuments as $doc) {
            $docName = $doc['name'] ?? $doc['id'] ?? '';
            if ($docName && !isset($this->documentFiles[$docName])) {
                $this->documentFiles[$docName] = [];
            }
        }
    }

    public function loadData()
    {
        $config = $this->config[$this->type];
        $user = User::with($config['relation'])->find($this->userId);

        if (!$user) {
            $this->rows = [];
            return;
        }

        $rawData = $user->{$config['relation']};

        $this->rows = $rawData->map(function ($data) use ($config) {
            return (new $config['resource']($data))->resolve();
        })->toArray();
    }

    public function applicationApprove(Application $application)
    {
        $approvedStatus = Status::select('id')->where('code', 'approved')->first();
        $vehicleApprovedStatus = Status::where('type', 'vehicle')->where('code', 'active')->first();

        try {
            // Only check current documents (exclude superseded/replaced documents)
            $currentDocuments = $application->documents->filter(function ($doc) {
                return $doc->is_current;
            });

            $hasUnverified = $currentDocuments->contains(function ($doc) use ($approvedStatus) {
                return $doc->status_id !== $approvedStatus->id;
            });

            if ($hasUnverified) {
                $this->dispatch(
                    "alert",
                    message: "Please verify the remaining documents.",
                    type: "warning",
                    duration: 3000,
                );
                return;
            }

            DB::transaction(function () use ($application, $approvedStatus, $vehicleApprovedStatus) {
                $application->update([
                    'status_id' => $approvedStatus->id,
                    'approved_by' => auth()->id(),
                ]);

                $approvalDate = now();
                $defaultValidityYears = config('anpr.gate_pass.default_validity_years', 4);

                // Check if this is a renewal application (linked to existing vehicle)
                $renewalVehicle = Vehicle::where('pending_renewal_application_id', $application->id)->first();

                if ($renewalVehicle) {
                    // This is a RENEWAL - extend the existing vehicle's validity
                    $renewalVehicle->setExpirationFromDate($approvalDate, $defaultValidityYears);
                    $renewalVehicle->has_pending_renewal = false;
                    $renewalVehicle->pending_renewal_application_id = null;
                    $renewalVehicle->is_renewal = true; // Mark that this vehicle has been renewed at least once
                    $renewalVehicle->save();
                } else {
                    // This is a NEW application - activate associated vehicles
                    if ($vehicleApprovedStatus) {
                        $vehicles = Vehicle::where('application_id', $application->id)->get();

                        foreach ($vehicles as $vehicle) {
                            $vehicle->status_id = $vehicleApprovedStatus->id;
                            $vehicle->setExpirationFromDate($approvalDate, $defaultValidityYears);
                            $vehicle->save();
                        }
                    }
                }
            });

            // Log activity
            $user = User::find($this->userId);
            if ($user) {
                ActivityLogService::logApplicationApproved($user, auth()->user());
            }

            $this->loadData();

            $this->dispatch(
                "toast",
                message: "Application approved successfully",
                type: "success",
                duration: 3000,
            );

            $this->dispatch('close-dropdown');
            $this->dispatch('refreshApplicationCard');
            $this->dispatch('refreshVehicleTable');
            $this->dispatch('refreshActivityLog');
        } catch (\Exception $e) {
            $this->dispatch(
                "toast",
                message: "Failed to approve application: " . $e->getMessage(),
                type: "error",
            );
        }
    }

    public function rejectApplication(Application $application)
    {
        $rejectedStatus = Status::select('id')->where('code', 'rejected')->first();
        $vehicleRejectedStatus = Status::where('type', 'vehicle')->where('code', 'inactive')->first();

        try {
            $documents = $application->documents;

            DB::transaction(function () use ($documents, $application, $rejectedStatus, $vehicleRejectedStatus) {
                foreach ($documents as $document) {
                    $document->update(['status_id' => $rejectedStatus->id]);
                }

                $application->update(['status_id' => $rejectedStatus->id]);

                // Also update all associated vehicles to rejected status
                if ($vehicleRejectedStatus) {
                    Vehicle::where('application_id', $application->id)
                        ->update(['status_id' => $vehicleRejectedStatus->id]);
                }
            });

            // Log activity
            $user = User::find($this->userId);
            if ($user) {
                ActivityLogService::logApplicationRejected($user, auth()->user());

                // Send notification to the applicant about application rejection
                $user->notify(new ApplicationRejectedNotification(
                    $application,
                    auth()->user(),
                    'Your application has been rejected. Please review and resubmit your documents.'
                ));
            }

            $this->loadData();

            $this->dispatch(
                "toast",
                message: "Application rejected",
                type: "error",
                duration: 3000,
            );
            $this->dispatch('refreshApplicationCard');
            $this->dispatch('refreshVehicleTable');
            $this->dispatch('refreshActivityLog');
        } catch (\Exception $e) {
            $this->dispatch(
                "toast",
                message: "Failed to reject application: " . $e->getMessage(),
                type: "error",
            );
        }
    }

    public function assignVehicleNumber(Vehicle $vehicle, $gatePassNumber)
    {
        // Check for vehicle 'active' status (vehicle type) or application 'approved' status
        $vehicleActiveStatus = Status::where('type', 'vehicle')->where('code', 'active')->first();
        $applicationApprovedStatus = Status::where('type', 'application')->where('code', 'approved')->first();

        try {
            Validator::make(
                ['gate_pass' => $gatePassNumber],
                [
                    'gate_pass' => [
                        'required',
                        Rule::unique('vehicles', 'assigned_gate_pass')->ignore($vehicle->id)
                    ]
                ]
            )->validate();

            // Check if vehicle is active OR if the application is approved
            $isVehicleActive = $vehicleActiveStatus && $vehicle->status_id === $vehicleActiveStatus->id;
            $isApplicationApproved = $applicationApprovedStatus && $vehicle->application?->status_id === $applicationApprovedStatus->id;

            if (!$isVehicleActive && !$isApplicationApproved) {
                $this->dispatch('alert', message: 'Please approve the application first.', type: 'warning', duration: 3000);
                return;
            }
        } catch (ValidationException $e) {
            $this->dispatch('alert', message: "Gate pass already assigned!", type: "error", duration: 3000);
            throw $e;
        }

        // Track previous gate pass if re-assigning
        $updateData = ['assigned_gate_pass' => $gatePassNumber];

        if ($vehicle->assigned_gate_pass && $vehicle->assigned_gate_pass !== $gatePassNumber) {
            // Store the old gate pass as previous
            $updateData['previous_gate_pass'] = $vehicle->assigned_gate_pass;
        }

        // Increment assignment count
        $updateData['gate_pass_assignment_count'] = ($vehicle->gate_pass_assignment_count ?? 0) + 1;

        $vehicle->update($updateData);

        // Log activity
        $user = User::find($this->userId);
        if ($user) {
            ActivityLogService::logGatePassIssued($user, $gatePassNumber, auth()->user());
        }

        $this->dispatch("toast", message: "Gate pass assigned!", type: "success", duration: 3000);
        $this->dispatch('refreshActivityLog');
        $this->dispatch('gate-pass-assigned');

        $this->loadData();
    }

    public function deleteApplication(Application $application)
    {
        try {
            $application->delete();

            $this->loadData();

            // Reset to page 1 if current page is now empty
            $this->resetPage();

            $this->dispatch(
                "toast",
                message: "Application deleted successfully",
                type: "success",
            );
        } catch (\Exception $e) {
            $this->dispatch(
                "toast",
                message: "Failed to delete application: " . $e->getMessage(),
                type: "error",
            );
        }
    }

    public function nextStep()
    {
        $validationRules = match ($this->currentStep) {
            1 => [
                "vehicle_type" => "required|string|max:50",
                "make" => "required|string|max:100",
                "model" => "required|string|max:100",
                "color" => "required|string|max:50",
                "year" => "required|integer|min:1900",
                "plate_number" => ["required", "string", "max:20", new UniquePlateNumber()],
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

    /**
     * Next step for vehicle application with dynamic document validation
     */
    public function nextVehicleStep()
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
            2 => $this->getDynamicDocumentRules(),
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
            "documentFiles",
            "currentStep"
        ]);

        // Re-initialize document files array
        foreach ($this->requiredDocuments as $doc) {
            $docName = $doc['name'] ?? $doc['id'] ?? '';
            if ($docName) {
                $this->documentFiles[$docName] = [];
            }
        }

        $this->resetValidation();
    }

    /**
     * Handle dynamic document file upload
     */
    public function updatedDocumentFiles($value, $key)
    {
        // Key format is like "document_name" or nested for array updates
        // The files are automatically stored in documentFiles array
    }

    /**
     * Remove a file from document uploads
     */
    public function removeDocumentFile($docName, $index)
    {
        if (isset($this->documentFiles[$docName][$index])) {
            unset($this->documentFiles[$docName][$index]);
            $this->documentFiles[$docName] = array_values($this->documentFiles[$docName]);
        }
    }

    /**
     * Get validation rules for dynamic documents
     */
    protected function getDynamicDocumentRules(): array
    {
        $rules = [];
        foreach ($this->requiredDocuments as $doc) {
            $docName = $doc['name'] ?? '';
            if (!$docName) continue;

            $formats = $doc['accepted_formats'] ?? 'pdf,jpg,jpeg,png';
            $maxSize = $doc['max_file_size'] ?? 10240;
            $isRequired = $doc['is_required'] ?? true;

            if ($isRequired) {
                $rules["documentFiles.{$docName}"] = "required|array|min:1";
            }
            $rules["documentFiles.{$docName}.*"] = "file|mimes:{$formats}|max:{$maxSize}";
        }
        return $rules;
    }

    /**
     * Submit vehicle application form (for vehicle type table)
     */
    public function submitVehicleApplication()
    {
        // Validate vehicle details
        $vehicleRules = [
            "vehicle_type" => "required|string|max:50",
            "make" => "required|string|max:100",
            "model" => "required|string|max:100",
            "color" => "required|string|max:50",
            "year" => "required|integer|min:1900|max:" . (date('Y') + 1),
            "plate_number" => ["required", "string", "max:20", new UniquePlateNumber()],
        ];

        // Add dynamic document validation rules
        $documentRules = $this->getDynamicDocumentRules();
        $allRules = array_merge($vehicleRules, $documentRules);

        $this->validate($allRules);

        $user = User::find($this->userId);
        if (!$user) {
            $this->dispatch('toast', message: 'User not found', type: 'error');
            return;
        }

        try {
            DB::transaction(function () use ($user) {
                // Get applicant type from existing application
                $applicantTypeId = $user->applications()->first()?->applicant_type_id ?? $this->applicantType?->id;
                $applicantTypeLabel = $this->applicantType?->label ?? 'Unknown';

                // Get statuses
                $applicationStatus = Status::applicationPending();
                $vehicleStatus = Status::vehiclePending();

                // Create application
                $application = $user->applications()->create([
                    'user_id' => $user->id,
                    'applicant_type_id' => $applicantTypeId,
                    'status_id' => $applicationStatus->id
                ]);

                // Create vehicle
                $user->vehicles()->create([
                    'application_id' => $application->id,
                    'plate_number' => $this->plate_number,
                    'type' => $this->vehicle_type,
                    'make' => $this->make,
                    'model' => $this->model,
                    'year' => $this->year,
                    'color' => $this->color,
                    'status_id' => $vehicleStatus->id,
                ]);

                // Process documents
                $fileRecords = [];
                foreach ($this->documentFiles as $docType => $files) {
                    if (!is_array($files)) continue;
                    foreach ($files as $file) {
                        $filename = \Illuminate\Support\Str::uuid() . '.' . $file->getClientOriginalExtension();
                        $storePath = "application/{$user->id}/{$application->id}";
                        $finalPath = "{$storePath}/{$filename}";

                        $file->storeAs($storePath, $filename, 'local');

                        $fileRecords[] = [
                            'type' => $docType,
                            'file_path' => $finalPath,
                            'mime_type' => $file->getMimeType(),
                            'file_size' => $file->getSize(),
                            'status_id' => $applicationStatus->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }

                if (!empty($fileRecords)) {
                    $application->documents()->createMany($fileRecords);
                }

                // Log activity
                ActivityLogService::logApplicationSubmitted($user, $applicantTypeLabel);
            });

            $this->resetForm();
            $this->loadData();

            $this->dispatch('close-vehicle-add-modal');
            $this->dispatch('refreshActivityLog');
            $this->dispatch('toast', message: 'Application submitted successfully!', type: 'success', duration: 3000);

        } catch (\Exception $e) {
            $this->dispatch('toast', message: 'Failed to submit application: ' . $e->getMessage(), type: 'error');
        }
    }

    public function submitForm()
    {
        $rules = $this->vehicleRules();
        $validatedData = $this->validate($rules);
        $validatedData['user_id'] = $this->userId;

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

        $created = $this->vehicleWriteService->create(
            $validatedData,
            $uploadedTempPaths
        );

        $this->loadData();

        $this->dispatch('close-add-modal');
        $this->dispatch('refreshApplicationData');
        $this->dispatch('toast', message: 'Application added!.', type: 'success', duration: 3000);
    }

    #[On('refreshApplicationData')]
    public function refreshTable()
    {
        $this->loadData();
    }

    #[On('refreshVehicleTable')]
    public function refreshVehicleData()
    {
        if ($this->type === 'vehicle') {
            $this->loadData();
        }
    }

    #[On('documentUpdated')]
    public function handleDocumentUpdated()
    {
        // Refresh the table when documents are updated (e.g., all docs approved = application approved)
        $this->loadData();
    }

    #[On('page-changed')]
    public function fetchTableRows(array $filters = [])
    {

        if (isset($filters['target']) && $filters['target'] !== $this->tableId) return;

        $this->page = $filters['page'] ?? 1;

        $this->setPage($this->page, $this->tableId);
    }

    public function render()
    {

        $itemsArray = $this->rows;
        $perPage = 5;

        $collection = collect($itemsArray);

        $page = $this->page ?? 1;

        // Calculate total pages
        $totalPages = ceil($collection->count() / $perPage);

        // If current page is greater than total pages, reset to last valid page
        if ($page > $totalPages && $totalPages > 0) {
            $this->setPage($totalPages);
            $page = $totalPages;
        }

        $paginatedItems = new LengthAwarePaginator(
            $collection->forPage($page, $perPage),
            $collection->count(),
            $perPage,
            $page,
            [
                'path' => LengthAwarePaginator::resolveCurrentPath(),
                'pageName' => $this->tableId ?? 'page',
            ]
        );

        $this->page = $page;

        $this->dispatch(
            "pagination-updated",
            currentPage: $paginatedItems->currentPage(),
            lastPage: $paginatedItems->lastPage(),
            perPage: $paginatedItems->perPage(),
            total: $paginatedItems->total(),
            from: $paginatedItems->firstItem(),
            to: $paginatedItems->lastItem(),
            path: $paginatedItems->path(),
            targetComponent: $this->tableId
        );

        return view("livewire.admin.applicant.details.info-table", ['items' => $paginatedItems]);
    }
}
