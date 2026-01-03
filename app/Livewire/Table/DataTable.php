<?php

namespace App\Livewire\Table;

use App\Exceptions\ApplicantNotFoundException;
use App\Services\Admin\Applicants\ApplicantReadService;
use App\Services\Admin\Applicants\ApplicantWriteService;
use App\Services\Admin\Admins\AdminReadService;
use App\Services\Admin\Admins\AdminWriteService;
use App\Helpers\ApplicationDisplayHelper;
use Exception;
use Illuminate\Support\Collection;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;

class DataTable extends Component
{
    use WithPagination;

    // REFERS TO TABLE TYPE: applicant, admin etc.
    #[Locked]
    public string $type;

    public array $headers = [];
    public Collection $rows;
    #[Validate("array")]
    public array $selectedRows = [];

    #[Validate("boolean")]
    public bool $selectedAll = false;

    #[Validate("array")]
    public array $bulkActionBtns = [];
    public ?string $caption = null;

    #[Validate("boolean")]
    public bool $showCheckboxes = true;

    #[Validate("boolean")]
    public bool $showActions = true;

    protected $queryString = ["page"];

    private const CONFIG_HELPER = "helper_class";
    private const CONFIG_READ_SERVICE = "read_service_class";
    private const CONFIG_WRITE_SERVICE = "write_service_class";
    private const CONFIG_METHODS = "method";

    // PAGINATION
    private const PER_PAGE = 10;

    protected array $tableTypes = [
        "applicant" => [
            self::CONFIG_HELPER => ApplicationDisplayHelper::class,
            self::CONFIG_READ_SERVICE => ApplicantReadService::class,
            self::CONFIG_WRITE_SERVICE => ApplicantWriteService::class,
            self::CONFIG_METHODS => [
                "fetch" => "getApplicants",
                "delete" => "delete",
                "format" => "formatApplicantsForList",
            ],
        ],
        "admin" => [
            self::CONFIG_HELPER => ApplicationDisplayHelper::class,
            self::CONFIG_READ_SERVICE => AdminReadService::class,
            self::CONFIG_WRITE_SERVICE => AdminWriteService::class,
            self::CONFIG_METHODS => [
                "fetch" => "getAdmins",
                "delete" => "delete",
                "format" => "formatAdminsForList",
            ],
        ],
    ];

    public function mount(): void
    {
        $this->rows = collect();
        $this->bulkActionBtns = $this->defaultBulkActions();

        $this->fetchTableHeaders();
        $this->fetchTableRows();
    }

    /**
     * Get the target table identifier based on type
     */
    private function getTargetTable(): string
    {
        return match($this->type) {
            'admin' => 'admins_table',
            'applicant' => 'applications_table',
            default => 'applications_table',
        };
    }

    public function updatedSelectedAll(bool $value): void
    {
        if($value) {
            $this->selectedRows = $this->rows->pluck('id')->mapWithKeys(fn($id) => [$id => true])->toArray();
        }else {
            $this->selectedRows = [];
        }
    }

    public function toggleRow(?string $id): void
    {
        if (isset($this->selectedRows[$id])) {
            unset($this->selectedRows[$id]);
            $this->selectedAll = false;
        } else {
            $this->selectedRows[$id] = true;
        }
    }

    public function clearSelection(): void
    {
        $this->selectedRows = [];
        $this->selectedAll = false;
    }

    private function defaultBulkActions(): array
    {
        return [
            "approve" => "Approve Selected",
            "delete" => "Delete Selected",
            "bulk-export" => "Export Selected",
            "deactivate" => "Deactivate Selected",
        ];
    }

    #[On("page-changed")]
    public function changePage(array $filters = []): void
    {
        // Only respond to page changes for this table's target
        if (isset($filters['target']) && $filters['target'] !== $this->getTargetTable()) {
            return;
        }

        $this->reset("selectedRows", "selectedAll");
        $this->fetchTableRows($filters);
    }

    public function fetchTableHeaders(): void
    {
        $config = $this->tableTypes[$this->type] ?? null;

        if (!$config) {
            $this->headers = [];
            return;
        }

        $helperClass = $config[self::CONFIG_HELPER];

        $headers = $helperClass::headerHelper("user_{$this->type}", "");

        $this->headers = $headers ?? [];
    }

    private function fetchReadService(): mixed
    {
        $config = $this->tableTypes[$this->type] ?? null;

        if (!$config) {
            return null;
        }

        return app($config[self::CONFIG_READ_SERVICE]);
    }

    private function fetchWriteService(): mixed
    {
        $config = $this->tableTypes[$this->type] ?? null;

        if (!$config) {
            return null;
        }

        return app($config[self::CONFIG_WRITE_SERVICE]);
    }

    #[On("filterTableData")]
    public function fetchTableRows(array $filters = []): void
    {
        // Only respond to filter events for this table's target (if specified)
        if (isset($filters['target']) && $filters['target'] !== $this->getTargetTable()) {
            return;
        }

        $service = $this->fetchReadService();

        if (!$service) {
            $this->rows = collect();
            return;
        }

        $config = $this->tableTypes[$this->type];
        $paginatedTableRows = $service
            ->{$config[self::CONFIG_METHODS]["fetch"]}($filters)
            ->paginate(self::PER_PAGE, ["*"], "page", $filters["page"] ?? 1);

        $formattedTableRows = $paginatedTableRows;
        if (isset($config[self::CONFIG_METHODS]["format"])) {
            $formattedTableRows = $service->{$config[self::CONFIG_METHODS][
                "format"
            ]}($paginatedTableRows);
        }

        $this->rows = $formattedTableRows;

        $this->dispatch(
            "pagination-updated",
            currentPage: $paginatedTableRows->currentPage(),
            lastPage: $paginatedTableRows->lastPage(),
            perPage: $paginatedTableRows->perPage(),
            total: $paginatedTableRows->total(),
            from: $paginatedTableRows->firstItem(),
            to: $paginatedTableRows->lastItem(),
            path: $paginatedTableRows->path(),
            targetComponent: $this->getTargetTable()
        );
    }

    #[On("refetchTableData")]
    public function refetchTableRows(): void
    {
        $this->fetchTableRows();
    }

    public function editRow(?string $id): void
    {
        // Dispatch event to open the edit modal with the admin ID
        $eventName = match($this->type) {
            'admin' => 'openEditAdminModal',
            'applicant' => 'openEditApplicantModal',
            default => 'openEditModal',
        };

        $this->dispatch($eventName, id: $id);
    }

    public function deleteRow(?string $id): void
    {
        try {
            $service = $this->fetchWriteService();

            if (!$service) {
                $this->dispatch("toast", type: "error", message: "Invalid Configuration");
                return;
            }

            $config = $this->tableTypes[$this->type];

            $service->{$config[self::CONFIG_METHODS]["delete"]}($id);

            $this->dispatch("toast", type: "success", message: "Deleted Successfully");
            $this->dispatch("fetchCardData");
            $this->fetchTableRows();
        } catch (ApplicantNotFoundException $e) {
            $this->dispatch("toast", type: "error", message: "Record not found");
        } catch (Exception $e) {
            Log::error("Delete failed in Livewire", [
                "id" => $id,
                "error" => $e->getMessage(),
            ]);

            $this->dispatch("toast", type: "error", message: "An error occurred. Please try again.");
        }
    }

    public function render(): \Illuminate\View\View
    {
        return view("livewire.table.data-table");
    }
}
