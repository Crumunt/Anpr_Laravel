<?php

namespace App\Livewire\Admin\Applicant\Details;

use App\Mail\ContactApplicant;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\On;
use Livewire\Component;

class Email extends Component
{
    // Applicant information (passed from parent)
    public string $applicantEmail = '';
    public string $applicantName = '';

    // Form fields
    public string $emailRecipient = '';
    public string $emailSubject = '';
    public string $emailMessage = '';
    public bool $sending = false;

    protected $rules = [
        'emailRecipient' => 'required|email|max:255',
        'emailSubject' => 'required|string|max:255',
        'emailMessage' => 'required|string|max:5000',
    ];

    protected $messages = [
        'emailRecipient.required' => 'Please enter a recipient email address.',
        'emailRecipient.email' => 'Please enter a valid email address.',
        'emailSubject.required' => 'Please enter a subject.',
        'emailMessage.required' => 'Please enter a message.',
    ];

    /**
     * Mount the component with optional applicant data
     */
    public function mount(string $applicantEmail = '', string $applicantName = ''): void
    {
        $this->applicantEmail = $applicantEmail;
        $this->applicantName = $applicantName;
        $this->emailRecipient = $applicantEmail;
    }

    /**
     * Keep emailRecipient in sync with applicantEmail
     */
    public function hydrate(): void
    {
        $this->emailRecipient = $this->applicantEmail;
    }

    /**
     * Listen for applicant data updates from parent components
     */
    #[On('set-email-recipient')]
    public function setEmailRecipient(string $email, string $name = ''): void
    {
        $this->applicantEmail = $email;
        $this->applicantName = $name;
        $this->emailRecipient = $email;
    }

    /**
     * Ensure emailRecipient is always synced with applicantEmail
     */
    public function updatedApplicantEmail(): void
    {
        $this->emailRecipient = $this->applicantEmail;
    }

    /**
     * Send the email to the recipient
     */
    public function sendEmail(): void
    {
        $this->sending = true;

        // Always use applicantEmail as the recipient (cannot be changed)
        $this->emailRecipient = $this->applicantEmail;

        // Validate the form
        $validated = $this->validate();

        try {
            // Get the recipient name (use provided name or extract from email)
            $recipientName = $this->applicantName ?: $this->extractNameFromEmail($this->applicantEmail);

            // Send the email using the ContactApplicant mailable
            Mail::to($this->applicantEmail)->send(
                new ContactApplicant(
                    userName: $recipientName,
                    messageSubject: $this->emailSubject,
                    messageBody: $this->emailMessage
                )
            );

            // Success notification
            $this->dispatch(
                'toast',
                type: 'success',
                message: 'Email sent successfully to ' . $this->applicantEmail
            );

            // Close modal
            $this->dispatch('close-modal', 'send-email');

            // Reset form
            $this->resetEmailForm();

            // Log successful email
            Log::info('Email sent successfully', [
                'recipient' => $this->applicantEmail,
                'subject' => $this->emailSubject,
            ]);
        } catch (\Exception $e) {
            // Error notification
            $this->dispatch(
                'toast',
                type: 'error',
                message: 'Failed to send email. Please try again.'
            );

            Log::error('Email send error', [
                'recipient' => $this->applicantEmail,
                'subject' => $this->emailSubject,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            // Don't close modal on error - let user retry or cancel manually
        } finally {
            $this->sending = false;
        }
    }

    /**
     * Extract a name from an email address
     */
    private function extractNameFromEmail(string $email): string
    {
        $localPart = explode('@', $email)[0] ?? 'User';
        // Convert dots and underscores to spaces, then capitalize
        $name = str_replace(['.', '_', '-'], ' ', $localPart);
        return ucwords($name);
    }

    /**
     * Reset the email form to initial state
     */
    public function resetEmailForm(): void
    {
        $this->emailSubject = '';
        $this->emailMessage = '';
        $this->emailRecipient = $this->applicantEmail;
        $this->sending = false;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.applicant.details.email');
    }
}
