<?php

namespace App\Livewire\Applicant;

use App\Models\Documents;
use App\Models\Status;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class NotificationsPanel extends Component
{
    use WithFileUploads;

    public $notifications = [];
    public $rejectedDocuments = [];
    public $unreadCount = 0;

    // Resubmission modal state
    public bool $showResubmitModal = false;
    public ?string $selectedDocumentId = null;
    public ?array $selectedDocument = null;
    public $newDocument = null;

    protected $listeners = [
        'refreshNotifications' => 'loadNotifications',
    ];

    public function mount()
    {
        $this->loadNotifications();
        $this->loadRejectedDocuments();
    }

    public function loadNotifications()
    {
        $user = Auth::user();
        if (!$user) return;

        $this->notifications = $user->notifications()
            ->latest()
            ->take(20)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->data['type'] ?? 'unknown',
                    'message' => $notification->data['message'] ?? 'You have a new notification',
                    'document_id' => $notification->data['document_id'] ?? null,
                    'document_type_name' => $notification->data['document_type_name'] ?? null,
                    'application_id' => $notification->data['application_id'] ?? null,
                    'rejection_reason' => $notification->data['rejection_reason'] ?? null,
                    'can_resubmit' => $notification->data['can_resubmit'] ?? false,
                    'action_required' => $notification->data['action_required'] ?? false,
                    'created_at' => $notification->created_at->diffForHumans(),
                    'read_at' => $notification->read_at,
                    'is_unread' => is_null($notification->read_at),
                ];
            })
            ->toArray();

        $this->unreadCount = $user->unreadNotifications()->count();
    }

    public function loadRejectedDocuments()
    {
        $user = Auth::user();
        if (!$user) return;

        $rejectedStatus = Status::where('code', 'rejected')->first();
        if (!$rejectedStatus) return;

        // Get all rejected documents for the user through their applications
        $this->rejectedDocuments = Documents::whereHas('application', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->where('status_id', $rejectedStatus->id)
            ->where('is_current', true)
            ->with(['application', 'vehicle', 'status'])
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function ($document) {
                return [
                    'id' => $document->id,
                    'type' => $document->type,
                    'type_name' => $this->getDocumentTypeName($document->type),
                    'file_path' => $document->file_path,
                    'rejection_reason' => $document->rejection_reason,
                    'reviewed_at' => $document->reviewed_at?->format('M d, Y H:i'),
                    'vehicle_id' => $document->vehicle_id,
                    'vehicle_plate' => $document->vehicle?->plate_number ?? 'N/A',
                    'application_id' => $document->application_id,
                    'version' => $document->version,
                    'can_resubmit' => true,
                ];
            })
            ->toArray();
    }

    protected function getDocumentTypeName(string $type): string
    {
        $typeMap = [
            'vehicle_registration' => 'Vehicle Registration',
            'license' => 'Driver\'s License',
            'proof_of_identification' => 'Proof of Identification',
        ];

        return $typeMap[$type] ?? ucwords(str_replace('_', ' ', $type));
    }

    public function markAsRead($notificationId)
    {
        $user = Auth::user();
        if (!$user) return;

        $notification = $user->notifications()->find($notificationId);
        if ($notification) {
            $notification->markAsRead();
            $this->loadNotifications();
        }
    }

    public function markAllAsRead()
    {
        $user = Auth::user();
        if (!$user) return;

        $user->unreadNotifications->markAsRead();
        $this->loadNotifications();
    }

    public function openResubmitModal($documentId)
    {
        $this->selectedDocumentId = $documentId;

        // Find the document in rejected documents array
        foreach ($this->rejectedDocuments as $doc) {
            if ($doc['id'] === $documentId) {
                $this->selectedDocument = $doc;
                break;
            }
        }

        $this->showResubmitModal = true;
        $this->newDocument = null;
    }

    public function closeResubmitModal()
    {
        $this->showResubmitModal = false;
        $this->selectedDocumentId = null;
        $this->selectedDocument = null;
        $this->newDocument = null;
        $this->resetErrorBag();
    }

    /**
     * Clear the selected document without closing the modal.
     */
    public function clearNewDocument()
    {
        $this->newDocument = null;
        $this->resetErrorBag('newDocument');
    }

    public function resubmitDocument()
    {
        $this->validate([
            'newDocument' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240', // 10MB max
        ], [
            'newDocument.required' => 'Please select a file to upload.',
            'newDocument.mimes' => 'The file must be a PDF, JPG, JPEG, or PNG.',
            'newDocument.max' => 'The file size must not exceed 10MB.',
        ]);

        $user = Auth::user();
        if (!$user || !$this->selectedDocumentId) {
            $this->dispatch('toast', message: 'Something went wrong. Please try again.', type: 'error');
            return;
        }

        $oldDocument = Documents::find($this->selectedDocumentId);
        if (!$oldDocument) {
            $this->dispatch('toast', message: 'Document not found.', type: 'error');
            return;
        }

        // Get the pending/under_review status for the new document
        $underReviewStatus = Status::where('code', 'under_review')->first();
        if (!$underReviewStatus) {
            $this->dispatch('toast', message: 'Unable to process. Status not found.', type: 'error');
            return;
        }

        try {
            // Store the new file using the same path structure as other documents
            $userId = $user->id;
            $applicationId = $oldDocument->application_id;
            $filename = Str::uuid() . '.' . $this->newDocument->getClientOriginalExtension();
            $storePath = "application/{$userId}/{$applicationId}";
            $filePath = "{$storePath}/{$filename}";

            $this->newDocument->storeAs($storePath, $filename, 'local');

            // Mark the old document as not current
            $oldDocument->update([
                'is_current' => false,
            ]);

            // Create new document as a replacement
            $newDoc = Documents::create([
                'application_id' => $oldDocument->application_id,
                'vehicle_id' => $oldDocument->vehicle_id,
                'type' => $oldDocument->type,
                'file_path' => $filePath,
                'mime_type' => $this->newDocument->getMimeType(),
                'file_size' => $this->newDocument->getSize(),
                'status_id' => $underReviewStatus->id,
                'rejection_reason' => null,
                'reviewed_by' => null,
                'reviewed_at' => null,
                'version' => $oldDocument->version + 1,
                'replaced_by' => null,
                'is_current' => true,
                'is_renewal_document' => $oldDocument->is_renewal_document,
            ]);

            // Update old document to point to new one
            $oldDocument->update([
                'replaced_by' => $newDoc->id,
            ]);

            // Update the application status to under_review if it was rejected
            $application = $oldDocument->application;
            if ($application) {
                $rejectedStatus = Status::where('code', 'rejected')->first();
                if ($application->status_id === $rejectedStatus->id) {
                    $application->update(['status_id' => $underReviewStatus->id]);
                }
            }

            // Close modal and refresh data
            $this->closeResubmitModal();
            $this->loadRejectedDocuments();
            $this->loadNotifications();

            $this->dispatch('toast', message: 'Document resubmitted successfully! It will be reviewed shortly.', type: 'success');
            $this->dispatch('refreshVehicleTable');
        } catch (\Exception $e) {
            $this->dispatch('toast', message: 'Failed to resubmit document: ' . $e->getMessage(), type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.applicant.notifications-panel');
    }
}
