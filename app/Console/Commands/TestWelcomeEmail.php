<?php

namespace App\Console\Commands;

use App\Mail\WelcomeNewUser;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class TestWelcomeEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test-welcome
                            {--email= : Send to specific email instead of user email}
                            {--simple : Send a simple text email instead of the welcome template}
                            {--debug : Show debug information}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test welcome email to verify Mailtrap configuration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('📧 Testing Email for Mailtrap');
        $this->info('=====================================');

        // Show mail config if debug mode
        if ($this->option('debug')) {
            $this->line('');
            $this->info('📋 Mail Configuration:');
            $config = config('mail.mailers.smtp');
            $this->table(
                ['Setting', 'Value'],
                [
                    ['Host', $config['host'] ?? 'not set'],
                    ['Port', $config['port'] ?? 'not set'],
                    ['Username', $config['username'] ?? 'not set'],
                    ['Password', $config['password'] ? '***hidden***' : 'not set'],
                    ['From Address', config('mail.from.address')],
                    ['From Name', config('mail.from.name')],
                ]
            );
        }

        // Get an existing admin user
        $user = User::whereHas('roles', fn($q) => $q->whereNotIn('name', ['applicant']))
            ->with(['details', 'roles'])
            ->first();

        if (!$user) {
            $this->error('❌ No admin user found in database.');
            return 1;
        }

        $this->table(
            ['Field', 'Value'],
            [
                ['User ID', $user->id],
                ['Email', $user->email],
                ['Name', ($user->details->first_name ?? 'N/A') . ' ' . ($user->details->last_name ?? '')],
                ['Role', $user->roles->first()->name ?? 'N/A'],
            ]
        );

        // Determine recipient
        $recipient = $this->option('email') ?: $user->email;

        $this->line('');
        $this->info('📤 Sending email to: ' . $recipient);

        try {
            if ($this->option('simple')) {
                // Send simple text email
                $this->line('Sending simple text email...');
                Mail::raw(
                    'This is a test email from ' . config('app.name') . ' sent at ' . now()->format('Y-m-d H:i:s'),
                    function ($message) use ($recipient) {
                        $message->to($recipient)
                            ->subject('Test Email from ' . config('app.name'));
                    }
                );
            } else {
                // Generate a test token
                $token = Str::random(64);
                $this->info('🔑 Generated test token: ' . substr($token, 0, 20) . '...');

                // Create the mailable
                $mailable = new WelcomeNewUser($user, $token, 48);

                // Send the email directly (not queued)
                Mail::to($recipient)->send($mailable);
            }

            $this->line('');
            $this->info('✅ Email sent successfully!');
            $this->info('📬 Check your Mailtrap inbox at: https://mailtrap.io/inboxes');
            $this->line('');
            $this->warn('⚠️  Note: Make sure you are checking the correct Mailtrap inbox.');
            $this->warn('    Your sandbox inbox should match the credentials in your .env file.');

            return 0;
        } catch (\Exception $e) {
            $this->line('');
            $this->error('❌ Failed to send email!');
            $this->error('Error: ' . $e->getMessage());

            if ($this->option('debug')) {
                $this->line('');
                $this->error('Stack trace:');
                $this->line($e->getTraceAsString());
            }

            return 1;
        }
    }
}
