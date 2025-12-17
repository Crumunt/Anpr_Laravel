<?php

namespace App\Livewire\Table;

use App\Exceptions\ApplicantNotFoundException;
use App\Services\Admin\Applicants\ApplicantReadService;
use App\Services\Admin\Applicants\ApplicantWriteService;
use App\Helpers\ApplicationDisplayHelper;
use Exception;
use Illuminate\Support\Collection;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Log;

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
    ];

    public function mount(): void
    {
        $this->rows = collect();
        $this->bulkActionBtns = $this->defaultBulkActions();

        $this->fetchTableHeaders();
        $this->fetchTableRows();
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

    private function fetchReadService(): ?ApplicantReadService
    {
        $config = $this->tableTypes[$this->type] ?? null;

        if (!$config) {
            return null;
        }

        return app($config[self::CONFIG_READ_SERVICE]);
    }

    private function fetchWriteService(): ?ApplicantWriteService
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
        );
    }

    #[On("refetchTableData")]
    public function refetchTableRows(): void
    {
        $this->fetchTableRows();
    }

    public function deleteRow(?string $id): void
    {
        try {
            $service = $this->fetchWriteService();

            if (!$service) {
                $this->dispatch("notify", [
                    "message" => "Invalid Configuration",
                    "type" => "error",
                ]);
                return;
            }

            $config = $this->tableTypes[$this->type];

            $service->{$config[self::CONFIG_METHODS]["delete"]}($id);

            $this->dispatch(
                "notify",
                message: "Deleted Successfully",
                type: "success",
            );
            $this->dispatch("fetchCardData");
            $this->fetchTableRows();
        } catch (ApplicantNotFoundException $e) {
            $this->dispatch(
                "notify",
                message: "Record not found",
                type: "error",
            );
        } catch (Exception $e) {
            Log::error("Delete failed in Livewire", [
                "id" => $id,
                "error" => $e->getMessage(),
            ]);

            $this->dispatch(
                "notify",
                message: "An error occurred. Please try again.",
                type: "error",
            );
        }
    }

    public function render(): \Illuminate\View\View
    {
        return view("livewire.table.data-table");
    }
}
