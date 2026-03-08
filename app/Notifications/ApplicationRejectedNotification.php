<?php

namespace App\Notifications;

use App\Models\Application;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationRejectedNotification extends Notification
{
    use Queueable;

    protected Application $application;
    protected ?User $rejectedBy;
    protected ?string $reason;

    /**
     * Create a new notification instance.
     */
    public function __construct(Application $application, ?User $rejectedBy = null, ?string $reason = null)
    {
        $this->application = $application;
        $this->rejectedBy = $rejectedBy;
        $this->reason = $reason;
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
            ->subject('Application Rejected')
            ->line('Your gate pass application has been rejected.')
            ->line($this->reason ? 'Reason: ' . $this->reason : 'Please review your submitted documents.')
            ->line('You may need to resubmit corrected documents.')
            ->action('View Application', url('/applicant/notifications'))
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
            'type' => 'application_rejected',
            'application_id' => $this->application->id,
            'applicant_type' => $this->application->applicantTypeLabelAttribute ?? 'Unknown',
            'reason' => $this->reason,
            'rejected_by' => $this->rejectedBy?->id,
            'rejected_by_name' => $this->rejectedBy?->details?->full_name ?? 'Administrator',
            'rejected_at' => now()->toISOString(),
            'message' => 'Your gate pass application has been rejected.',
            'action_required' => true,
        ];
    }
}
