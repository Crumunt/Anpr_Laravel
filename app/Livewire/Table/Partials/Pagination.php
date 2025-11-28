<?php

namespace App\Livewire\Table\Partials;

use App\View\Components\Table\DataTable;
use Livewire\Attributes\On;
use Livewire\Component;

class Pagination extends Component
{
    // Pagination metadata
    public $currentPage;
    public $lastPage;
    public $total;
    public $perPage;
    public $from;
    public $to;
    public $path;

    // Component identifier for targeting specific tables
    public $targetComponent = "applicant-table";

    public $pages = [];
    public $onEachSide = 2;

    public $filters = [
        "filter_type" => null,
        "search" => null,
        "status" => null,
        "applicant_types" => null,
        "sort_by" => null,
    ];

    #[On("pagination-updated")]
    public function updatePagination(
        $currentPage,
        $lastPage,
        $total,
        $perPage,
        $from,
        $to,
        $path,
        $targetComponent = "applicant-table",
    ) {
        $this->currentPage = $currentPage;
        $this->lastPage = $lastPage;
        $this->total = $total;
        $this->perPage = $perPage;
        $this->from = $from;
        $this->to = $to;
        $this->path = $path;
        $this->targetComponent = $targetComponent;
    }

    public function buildPages()
    {
        $this->pages = [];

        if ($this->lastPage <= 7) {
            for ($i = 1; $i <= $this->lastPage; $i++) {
                $this->pages[] = $i;
            }
        } else {
            $this->pages[] = 1;

            $rangeStart = max(2, $this->currentPage - $this->onEachSide);
            $rangeEnd = min(
                $this->lastPage - 1,
                $this->currentPage + $this->onEachSide,
            );

            if ($rangeStart > 2) {
                $this->pages[] = "...";
            }

            for ($i = $rangeStart; $i <= $rangeEnd; $i++) {
                $this->pages[] = $i;
            }

            if ($rangeEnd < $this->lastPage - 1) {
                $this->pages[] = "...";
            }

            $this->pages[] = $this->lastPage;
        }
    }

    #[On("table-filter")]
    public function updateFilters($filters)
    {
        $this->filters = array_merge($this->filters, $filters);
        $this->currentPage = 1; // Reset to page 1 when filters change
    }

    public function goToPage($page)
    {
        if ($page === "..." || $page < 1 || $page > $this->lastPage) {
            return;
        }

        // $newFilters = array_merge(["page" => $page], $this->filters);

        $this->filters['page'] = $page;

        $this->dispatch("log-action", $this->filters);

        // Dispatch event to parent/table component
        $this->dispatch("page-changed", filters: $this->filters);
    }

    public function previousPage()
    {
        if ($this->currentPage > 1) {
            $this->goToPage($this->currentPage - 1);
        }
    }

    public function nextPage()
    {
        if ($this->currentPage < $this->lastPage) {
            $this->goToPage($this->currentPage + 1);
        }
    }

    public function jumpToPage($page)
    {
        $page = (int) $page;
        if ($page >= 1 && $page <= $this->lastPage) {
            $this->goToPage($page);
        }
    }

    public function render()
    {
        $this->buildPages();
        return view("livewire.table.partials.pagination");
    }
}
