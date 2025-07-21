<?php

namespace App\View\Components\Table;

use App\Helpers\ApplicationTableHelper;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Data extends Component
{
    /**
     * Create a new component instance.
     */
    public array $headers;
    public string $context;
    public string $tab;
    public $rows;
    public string $type;
    public string $caption;
    public bool $showCheckboxes;
    public bool $showStatus;
    public bool $showActions;
    public array $actionOptions;
    public array $bulkActions;
    public array $bulkActionBtns;

    public function __construct(
        string $context = '',
        string $tab = 'default',
        $rows = [],
        string $type = 'applicant',
        string $caption = 'List of applicants and their status',
        bool $showCheckboxes = true,
        bool $showStatus = true,
        bool $showActions = true
    ) {
        $this->context = $context;
        $this->tab = $tab;
        $this->rows = $rows;
        $this->type = $type;
        $this->caption = $caption;
        $this->showCheckboxes = $showCheckboxes;
        $this->showStatus = $showStatus;
        $this->showActions = $showActions;

        $this->setup();
    }

    protected function setup(): void
    {
        $this->headers = ApplicationTableHelper::headerHelper($this->context, $this->tab);
        $this->bulkActionBtns = ApplicationTableHelper::getBulkActions($this->type);
        $this->actionOptions = $this->defaultActionOptions();
        $this->bulkActions = $this->defaultBulkActions();
    }

    protected function defaultActionOptions(): array
    {
        return [
            'view' => ['label' => 'View Details'],
            'edit' => ['label' => 'Edit'],
            'approve' => ['label' => 'Approve'],
            'delete' => ['label' => 'Delete'],
            'reset-password' => ['label' => 'Reset Password'],
            'deactivate' => ['label' => 'Deactivate'],
        ];
    }

    protected function defaultBulkActions(): array
    {
        return [
            'approve' => 'Approve Selected',
            'delete' => 'Delete Selected',
            'export' => 'Export Selected',
            'deactivate' => 'Deactivate Selected',
        ];
    }

    public function getHighlightClass(string $key): string
    {
        $highlightKeys = ['id', 'owner', 'vehicle', 'gate_pass', 'vehicles'];
        return in_array($key, $highlightKeys)
            ? 'font-medium group-hover:text-green-700'
            : 'text-gray-600';
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.table.data');
    }
}
