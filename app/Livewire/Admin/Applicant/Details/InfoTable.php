<?php

namespace App\Livewire\Admin\Applicant\Details;

use App\Http\Resources\ApplicationDetailsResource;
use App\Http\Resources\VehicleResource;
use App\Models\Application;
use App\Models\Status;
use App\Models\User;
use App\Models\Vehicle\Vehicle;
use App\Services\Admin\Applicants\ApplicantReadService;
use App\Services\Admin\Vehicles\VehicleWriteService;
use App\Services\Application\SaveVehicleService;
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
        try {
            $documents = $application->documents;

            $hasUnverified = $documents->contains(function ($doc) use ($approvedStatus) {
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

            $application->update(['status_id' => $approvedStatus->id]);

            $this->loadData();

            $this->dispatch(
                "toast",
                message: "Application approved successfully",
                type: "success",
                duration: 3000,
            );

            $this->dispatch('close-dropdown');
            $this->dispatch('refreshApplicationCard');
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
        try {
            $documents = $application->documents;

            DB::transaction(function () use ($documents, $application, $rejectedStatus) {
                foreach ($documents as $document) {
                    $document->update(['status_id' => $rejectedStatus->id]);
                }

                $application->update(['status_id' => $rejectedStatus->id]);
            });

            $this->loadData();

            $this->dispatch(
                "toast",
                message: "Application rejected",
                type: "error",
                duration: 3000,
            );
            $this->dispatch('refreshApplicationCard');
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

        $approved_status = Status::where('code', 'approved')->first();

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

            if ($vehicle->status_id !== $approved_status->id) {
                $this->dispatch('alert', message: 'Please approved application first.', type: 'warning', duration: 3000);
                return;
            }
        } catch (ValidationException $e) {
            $this->dispatch('alert', message: "Gate pass already assigned!", type: "error", duration: 3000);
            throw $e;
        }

        $vehicle->update(['assigned_gate_pass' => $gatePassNumber]);

        $this->dispatch("toast", message: "Gate pass assigned!", type: "success", duration: 3000);

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
            "currentStep"
        ]);
        $this->resetValidation();
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
