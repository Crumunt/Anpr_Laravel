<?php

namespace App\Livewire\Admin\Applicant\Details\Documents;

use App\Models\Application;
use App\Models\Documents;
use App\Models\Status;
use App\Services\ActivityLogService;
use Dom\Document;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Js;
use Livewire\Attributes\On;
use Livewire\Component;

class DocumentViewer extends Component
{

    public ?array $currentDocument = null;
    public bool $show = false;

    #[On('open-document-viewer')]
    public function openViewer(array $document)
    {
        $this->currentDocument = $document;
        $this->show = true;
    }

    public function getIsImageProperty(): bool
    {
        if (!$this->currentDocument || !isset($this->currentDocument['mime_type'])) return false;

        $mime = strtolower($this->currentDocument['mime_type']);
        return str_contains($mime, 'jpg') || str_contains($mime, 'jpeg') ||
            str_contains($mime, 'png') || str_contains($mime, 'gif') ||
            str_contains($mime, 'webp') || str_contains($mime, 'bmp');
    }

    public function getIsPDFProperty(): bool
    {
        if (!$this->currentDocument || !isset($this->currentDocument['mime_type'])) return false;
        return str_contains(strtolower($this->currentDocument['mime_type']), 'pdf');
    }

    public function approveDocument()
    {
        $approved_status = Status::select('id')->where('code', 'approved')->first();

        // Your server-side logic here (database update, etc.)
        $docId = $this->currentDocument['document_id'];

        if (!$docId) {
            $this->dispatch('documentUpdated', id: $docId, message: 'Something went wrong', type: 'error');
            return;
        }

        try {
            $document = Documents::with('application.user')->findOrFail($docId);

            $document->status_id = $approved_status->id;

            $application_id = $document->application_id;

            $document->save();

            // Log the document approval activity
            $documentType = $this->currentDocument['name'] ?? $document->type ?? 'Document';
            $applicant = $document->application->user;
            ActivityLogService::logDocumentApproved($applicant, $documentType, Auth::user());

            // Check if all documents for this application are approved, then auto-approve the application
            $this->checkAndAutoApproveApplication($application_id, $approved_status->id);

            // Dispatch an event to update the parent list
            $this->dispatch('documentUpdated', applicationId: $application_id);
            $this->dispatch('refreshActivityLog');
            $this->dispatch('refreshVehicleTable');
            $this->dispatch('document-verified');
        } catch (\Throwable $th) {
            throw $th;
        }


        // Close and reset the component state
        $this->closeAndClear();
    }

    /**
     * Check if all documents for an application are approved and auto-approve the application
     */
    protected function checkAndAutoApproveApplication($applicationId, $approvedStatusId)
    {
        $application = Application::with(['documents', 'user'])->find($applicationId);

        if (!$application) {
            return;
        }

        // Check if all documents are approved
        $allDocumentsApproved = $application->documents->every(function ($doc) use ($approvedStatusId) {
            return $doc->status_id === $approvedStatusId;
        });

        // If all documents are approved, approve the application
        if ($allDocumentsApproved && $application->documents->count() > 0) {
            $application->status_id = $approvedStatusId;
            $application->approved_by = Auth::id();
            $application->save();

            // Handle vehicle approval/renewal
            $this->processVehicleApproval($application, $approvedStatusId);

            // Log the application auto-approval
            ActivityLogService::logApplicationAutoApproved($application->user, Auth::user());
        }
    }

    /**
     * Process vehicle approval - handles both new applications and renewals
     */
    protected function processVehicleApproval(Application $application, $approvedStatusId)
    {
        $vehicleApprovedStatus = Status::where('type', 'vehicle')->where('code', 'active')->first();
        $approvalDate = now();
        $defaultValidityYears = config('anpr.gate_pass.default_validity_years', 4);

        // Check if this is a renewal application (linked to existing vehicle)
        $renewalVehicle = \App\Models\Vehicle\Vehicle::where('pending_renewal_application_id', $application->id)->first();

        if ($renewalVehicle) {
            // This is a RENEWAL - extend the existing vehicle's validity
            $renewalVehicle->setExpirationFromDate($approvalDate, $defaultValidityYears);
            $renewalVehicle->has_pending_renewal = false;
            $renewalVehicle->pending_renewal_application_id = null;
            $renewalVehicle->is_renewal = true;
            $renewalVehicle->save();
        } else {
            // This is a NEW application - activate associated vehicles
            if ($vehicleApprovedStatus) {
                $vehicles = \App\Models\Vehicle\Vehicle::where('application_id', $application->id)->get();

                foreach ($vehicles as $vehicle) {
                    $vehicle->status_id = $vehicleApprovedStatus->id;
                    $vehicle->setExpirationFromDate($approvalDate, $defaultValidityYears);
                    $vehicle->save();
                }
            }
        }
    }

    public function rejectDocument()
    {

        $rejected_status = Status::select('id')->where('code', 'rejected')->first();

        $docId = $this->currentDocument['document_id'];

        if (!$docId) {
            $this->dispatch('documentUpdated', id: $docId, message: 'Something went wrong', type: 'error');
            return;
        }

        try {
            $document = Documents::with('application.user')->findOrFail($docId);

            $document->status_id = $rejected_status->id;

            $application_id = $document->application_id;

            $document->save();

            // Log the document rejection activity
            $documentType = $this->currentDocument['name'] ?? $document->type ?? 'Document';
            $applicant = $document->application->user;
            ActivityLogService::logDocumentRejected($applicant, $documentType, Auth::user());

            // Update application status to rejected when a document is rejected
            $this->updateApplicationStatusOnRejection($application_id, $rejected_status->id);

            // Dispatch an event to update the parent list
            $this->dispatch('documentUpdated', applicationId: $application_id);
            $this->dispatch('refreshActivityLog');
            $this->dispatch('refreshVehicleTable');

            // Close and reset the component state
            $this->closeAndClear();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Update application status when a document is rejected
     */
    protected function updateApplicationStatusOnRejection($applicationId, $rejectedStatusId)
    {
        $application = Application::with('user')->find($applicationId);

        if (!$application) {
            return;
        }

        // Set application to rejected status when any document is rejected
        $application->status_id = $rejectedStatusId;
        $application->save();
    }

    /**
     * Mark document as pending (under review)
     */
    public function markAsPending()
    {
        $pending_status = Status::select('id')->where('code', 'under_review')->first();

        $docId = $this->currentDocument['document_id'];

        if (!$docId) {
            $this->dispatch('documentUpdated', id: $docId, message: 'Something went wrong', type: 'error');
            return;
        }

        try {
            $document = Documents::with('application.user')->findOrFail($docId);

            $document->status_id = $pending_status->id;

            $application_id = $document->application_id;

            $document->save();

            // Update application status to under_review when a document is marked as pending
            $this->updateApplicationStatusToPending($application_id, $pending_status->id);

            // Dispatch an event to update the parent list
            $this->dispatch('documentUpdated', applicationId: $application_id);

            // Close and reset the component state
            $this->closeAndClear();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Update application status when a document is marked as pending
     */
    protected function updateApplicationStatusToPending($applicationId, $pendingStatusId)
    {
        $application = Application::find($applicationId);

        if (!$application) {
            return;
        }

        // Set application to pending/under_review status
        $application->status_id = $pendingStatusId;
        $application->save();
    }

    public function closeAndClear()
    {
        // We close the modal first
        $this->show = false;

        // Give the component time to disappear visually (Alpine transition)
        // Then, clear the data to prevent caching issues.
        // We use $this->skipRender() here to prevent the component from re-rendering
        // with null data before the modal is hidden.
        // $this->skipRender();
        $this->currentDocument = null;
        // $this->dispatch('clear-cached-document');
    }

    public function resetRejectForm()
    { /* ... */
    }
    public function render()
    {
        return view('livewire.admin.applicant.details.documents.document-viewer');
    }
}
