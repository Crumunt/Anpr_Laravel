<?php

namespace App\Livewire\Table;

use App\Exceptions\ApplicantNotFoundException;
use App\Services\Admin\Applicants\ApplicantReadService;
use App\Services\Admin\Applicants\ApplicantWriteService;
use App\Services\Admin\Admins\AdminReadService;
use App\Services\Admin\Admins\AdminWriteService;
use App\Helpers\ApplicationDisplayHelper;
use App\Traits\HasRoleBasedAccess;
use Exception;
use Illuminate\Support\Collection;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class DataTable extends Component
{
    use WithPagination;
    use HasRoleBasedAccess;

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

    // Bulk action confirmation state
    public bool $showBulkConfirmation = false;
    public string $pendingBulkAction = '';
    public string $bulkActionLabel = '';
    public string $bulkActionDescription = '';
    public string $bulkActionType = 'warning'; // warning, danger, success

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

        // Get bulk actions filtered by user permissions
        $allBulkActions = $this->getAllBulkActionsForType();
        $this->bulkActionBtns = $this->getPermittedBulkActions($allBulkActions, $this->type);

        // Hide checkboxes and actions if user has no bulk actions available
        if (empty($this->bulkActionBtns)) {
            $this->showCheckboxes = false;
        }

        $this->fetchTableHeaders();
        $this->fetchTableRows();
    }

    /**
     * Get all bulk actions specific to the table type (before permission filtering)
     */
    private function getAllBulkActionsForType(): array
    {
        return match($this->type) {
            'admin' => [
                'activate' => [
                    'label' => 'Activate Selected',
                    'icon' => 'check-circle',
                    'color' => 'success',
                    'description' => 'This will activate the selected admin accounts, allowing them to log in.',
                ],
                'deactivate' => [
                    'label' => 'Deactivate Selected',
                    'icon' => 'x-circle',
                    'color' => 'warning',
                    'description' => 'This will deactivate the selected admin accounts, preventing them from logging in.',
                ],
                'reset-password' => [
                    'label' => 'Reset Passwords',
                    'icon' => 'key',
                    'color' => 'warning',
                    'description' => 'This will reset passwords for the selected admins and require them to change it on next login.',
                ],
                'delete' => [
                    'label' => 'Delete Selected',
                    'icon' => 'trash',
                    'color' => 'danger',
                    'description' => 'This will permanently delete the selected admin accounts. This action cannot be undone.',
                ],
            ],
            'applicant' => [
                'approve' => [
                    'label' => 'Approve Selected',
                    'icon' => 'check-circle',
                    'color' => 'success',
                    'description' => 'This will approve the selected applications and activate their accounts.',
                ],
                'reject' => [
                    'label' => 'Reject Selected',
                    'icon' => 'x-circle',
                    'color' => 'danger',
                    'description' => 'This will reject the selected applications and deactivate their accounts.',
                ],
                'under-review' => [
                    'label' => 'Set Under Review',
                    'icon' => 'clock',
                    'color' => 'warning',
                    'description' => 'This will set the selected applications back to under review status.',
                ],
                'activate' => [
                    'label' => 'Activate Accounts',
                    'icon' => 'user-check',
                    'color' => 'success',
                    'description' => 'This will activate the selected applicant accounts.',
                ],
                'deactivate' => [
                    'label' => 'Deactivate Accounts',
                    'icon' => 'user-x',
                    'color' => 'warning',
                    'description' => 'This will deactivate the selected applicant accounts.',
                ],
                'delete' => [
                    'label' => 'Delete Selected',
                    'icon' => 'trash',
                    'color' => 'danger',
                    'description' => 'This will permanently delete the selected applicants and all their data. This action cannot be undone.',
                ],
            ],
            default => [
                'delete' => [
                    'label' => 'Delete Selected',
                    'icon' => 'trash',
                    'color' => 'danger',
                    'description' => 'This will permanently delete the selected items.',
                ],
            ],
        };
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

    /**
     * Show confirmation modal for bulk action
     */
    public function executeBulkAction(string $action): void
    {
        if (empty($this->selectedRows)) {
            $this->dispatch('toast', type: 'warning', message: 'No items selected');
            return;
        }

        $actionConfig = $this->bulkActionBtns[$action] ?? null;

        if (!$actionConfig) {
            $this->dispatch('toast', type: 'error', message: 'Invalid action');
            return;
        }

        $this->pendingBulkAction = $action;
        $this->bulkActionLabel = $actionConfig['label'];
        $this->bulkActionDescription = $actionConfig['description'];
        $this->bulkActionType = $actionConfig['color'];
        $this->showBulkConfirmation = true;
    }

    /**
     * Cancel bulk action confirmation
     */
    public function cancelBulkAction(): void
    {
        $this->showBulkConfirmation = false;
        $this->pendingBulkAction = '';
        $this->bulkActionLabel = '';
        $this->bulkActionDescription = '';
        $this->bulkActionType = 'warning';
    }

    /**
     * Confirm and execute the bulk action
     */
    public function confirmBulkAction(): void
    {
        if (empty($this->pendingBulkAction) || empty($this->selectedRows)) {
            $this->cancelBulkAction();
            return;
        }

        $selectedIds = array_keys($this->selectedRows);
        $action = $this->pendingBulkAction;

        // Close modal first
        $this->showBulkConfirmation = false;

        try {
            $result = $this->processBulkAction($action, $selectedIds);

            if ($result['success_count'] > 0) {
                $message = $this->getBulkActionSuccessMessage($action, $result['success_count']);
                $this->dispatch('toast', type: 'success', message: $message);
            }

            if (!empty($result['failed_ids'])) {
                $failedCount = count($result['failed_ids']);
                $this->dispatch('toast', type: 'warning', message: "{$failedCount} item(s) could not be processed");
            }

            // Clear selection and refresh data
            $this->clearSelection();
            $this->dispatch('fetchCardData');
            $this->fetchTableRows();

        } catch (Exception $e) {
            Log::error('Bulk action failed', [
                'action' => $action,
                'type' => $this->type,
                'error' => $e->getMessage()
            ]);
            $this->dispatch('toast', type: 'error', message: 'An error occurred while processing the bulk action');
        }

        // Reset pending action
        $this->pendingBulkAction = '';
        $this->bulkActionLabel = '';
        $this->bulkActionDescription = '';
        $this->bulkActionType = 'warning';
    }

    /**
     * Process the bulk action based on type and action
     */
    private function processBulkAction(string $action, array $ids): array
    {
        $service = $this->fetchWriteService();

        if (!$service) {
            throw new Exception('Service not available');
        }

        return match($this->type) {
            'admin' => $this->processAdminBulkAction($service, $action, $ids),
            'applicant' => $this->processApplicantBulkAction($service, $action, $ids),
            default => throw new Exception('Unknown table type'),
        };
    }

    /**
     * Process admin-specific bulk actions
     */
    private function processAdminBulkAction(AdminWriteService $service, string $action, array $ids): array
    {
        // Filter out current user from destructive actions
        $currentUserId = Auth::id();
        $idsToProcess = array_filter($ids, fn($id) => $id !== $currentUserId);

        if (count($idsToProcess) < count($ids)) {
            $this->dispatch('toast', type: 'warning', message: 'You cannot perform this action on your own account');
        }

        if (empty($idsToProcess)) {
            return ['success_count' => 0, 'failed_ids' => [], 'total' => 0];
        }

        return match($action) {
            'activate' => $service->bulkActivate($idsToProcess),
            'deactivate' => $service->bulkDeactivate($idsToProcess),
            'reset-password' => $service->bulkResetPassword($idsToProcess),
            'delete' => $service->bulkDelete($idsToProcess),
            default => throw new Exception('Unknown admin bulk action: ' . $action),
        };
    }

    /**
     * Process applicant-specific bulk actions
     */
    private function processApplicantBulkAction(ApplicantWriteService $service, string $action, array $ids): array
    {
        return match($action) {
            'approve' => $service->bulkApprove($ids),
            'reject' => $service->bulkReject($ids),
            'under-review' => $service->bulkSetUnderReview($ids),
            'activate' => $service->bulkActivate($ids),
            'deactivate' => $service->bulkDeactivate($ids),
            'delete' => $service->bulkDelete($ids),
            default => throw new Exception('Unknown applicant bulk action: ' . $action),
        };
    }

    /**
     * Get success message for bulk action
     */
    private function getBulkActionSuccessMessage(string $action, int $count): string
    {
        $item = $count === 1 ? 'item' : 'items';

        return match($action) {
            'activate' => "{$count} {$item} activated successfully",
            'deactivate' => "{$count} {$item} deactivated successfully",
            'delete' => "{$count} {$item} deleted successfully",
            'approve' => "{$count} application(s) approved successfully",
            'reject' => "{$count} application(s) rejected successfully",
            'under-review' => "{$count} application(s) set to under review",
            'reset-password' => "{$count} password(s) reset successfully",
            default => "{$count} {$item} processed successfully",
        };
    }

    private function defaultBulkActions(): array
    {
        return $this->getBulkActionsForType();
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
