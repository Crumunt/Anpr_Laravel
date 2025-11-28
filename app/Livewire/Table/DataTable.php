<?php

namespace App\Livewire\Table;

use App\Http\Resources\ApplicantResource;
use App\Services\ApplicantService;
use App\Helpers\ApplicationDisplayHelper;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class DataTable extends Component
{
    use WithPagination;

    // REFERS TO TABLE TYPE: applicant, admin etc.
    public $type;

    public $headers = [];
    public $rows = [];
    public $selectedRows = [];
    public $selectedAll = false;
    public $bulkActionBtns = [];
    public $caption;
    public $showCheckboxes = true;
    public $showActions = true;

    protected $queryString = ["page"];

    // SERVICES
    protected $applicantService;
    // HELPERS
    protected $applicationDisplayHelper;

    protected array $tableTypes = [
        "applicant" => [
            "helper" => "applicationDisplayHelper",
            "service" => "applicantService",
            "method" => "getApplicants",
            "format" => "formatApplicantsForList",
        ],
    ];

    public function boot(ApplicantService $applicantService, ApplicationDisplayHelper $applicationDisplayHelper)
    {
        $this->applicantService = $applicantService;
        $this->applicationDisplayHelper = $applicationDisplayHelper;
    }

    public function mount()
    {
        $this->bulkActionBtns = $this->defaultBulkActions();

        $this->fetchTableHeaders();
        $this->fetchTableRows();
    }

    public function updatedSelectedAll($value)
    {
        $this->selectedRows = $value ? $this->rows->pluck("id")->toArray() : [];
    }

    public function toggleRow($id)
    {
        if (in_array($id, $this->selectedRows)) {
            $this->selectedRows = array_diff($this->selectedRows, [$id]);
            $this->selectedAll = false;
        } else {
            $this->selectedRows[] = $id;
        }
    }

    public function clearSelection()
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
    public function changePage($filters)
    {
        $this->reset("selectedRows", "selectedAll");
        // $this->dispatch('log-action', [$this->selectedRows, $this->selectedAll]);
        $this->fetchTableRows($filters);
    }

    public function fetchTableHeaders()
    {
        $type = $this->type ?? "applicant";

        if (!isset($this->tableTypes[$type])) {
            return;
        }

        $config = $this->tableTypes[$type];
        $helper = $this->{$config["helper"]};

        $headers = $helper::headerHelper("user_{$type}", "");

        $this->headers = $headers;
    }

    #[On("table-filter")]
    public function fetchTableRows($filters = [])
    {
        $type = $filters["filter_type"] ?? $this->type;

        if (!isset($this->tableTypes[$type])) {
            return;
        }

        $config = $this->tableTypes[$type];
        $service = $this->{$config["service"]};

        $paginatorRows = $service
            ->{$config["method"]}($filters)
            ->paginate(10, ["*"], "page", $filters["page"] ?? 1);

        if (isset($config["format"])) {
            $rows = $service->{$config["format"]}($paginatorRows);
        }

        $this->rows = $rows;

        $this->dispatch(
            "pagination-updated",
            currentPage: $paginatorRows->currentPage(),
            lastPage: $paginatorRows->lastPage(),
            perPage: $paginatorRows->perPage(),
            total: $paginatorRows->total(),
            from: $paginatorRows->firstItem(),
            to: $paginatorRows->lastItem(),
            path: $paginatorRows->path(),
        );

        $this->dispatch("log-action", $filters["page"] ?? 1);
    }

    public function render()
    {
        return view("livewire.table.data-table");
    }
}
