<?php

namespace App\Notifications;

use App\Models\Documents;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentRejectedNotification extends Notification
{
    use Queueable;

    protected Documents $document;
    protected ?User $rejectedBy;
    protected ?string $rejectionReason;

    /**
     * Create a new notification instance.
     */
    public function __construct(Documents $document, ?User $rejectedBy = null, ?string $rejectionReason = null)
    {
        $this->document = $document;
        $this->rejectedBy = $rejectedBy;
        $this->rejectionReason = $rejectionReason;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Document Rejected - Action Required')
            ->line('Your document "' . $this->getDocumentTypeName() . '" has been rejected.')
            ->line('Reason: ' . ($this->rejectionReason ?? 'No reason provided'))
            ->line('Please review the feedback and resubmit your document.')
            ->action('View Document', url('/applicant/notifications'))
            ->line('Thank you for your cooperation.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'document_rejected',
            'document_id' => $this->document->id,
            'document_type' => $this->document->type,
            'document_type_name' => $this->getDocumentTypeName(),
            'application_id' => $this->document->application_id,
            'vehicle_id' => $this->document->vehicle_id,
            'rejection_reason' => $this->rejectionReason ?? $this->document->rejection_reason,
            'rejected_by' => $this->rejectedBy?->id,
            'rejected_by_name' => $this->rejectedBy?->details?->full_name ?? 'Administrator',
            'rejected_at' => now()->toISOString(),
            'message' => 'Your document "' . $this->getDocumentTypeName() . '" has been rejected.',
            'action_required' => true,
            'can_resubmit' => true,
        ];
    }

    /**
     * Get human-readable document type name.
     */
    protected function getDocumentTypeName(): string
    {
        $typeMap = [
            'vehicle_registration' => 'Vehicle Registration',
            'license' => 'Driver\'s License',
            'proof_of_identification' => 'Proof of Identification',
        ];

        return $typeMap[$this->document->type] ?? ucwords(str_replace('_', ' ', $this->document->type));
    }
}
