<?php

namespace App\Livewire\Admin\Settings;

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

/**
 * System Settings page for administrators.
 * Provides system configuration and management features.
 */
#[Layout('layouts.app-layout')]
class SystemSettings extends Component
{
    // Active tab
    public string $activeTab = 'general';

    // General Settings
    public string $appName = '';
    public string $appTimezone = 'Asia/Manila';
    public bool $maintenanceMode = false;

    // Email Settings
    public string $mailDriver = 'smtp';
    public string $mailHost = '';
    public string $mailPort = '';
    public string $mailFromAddress = '';
    public string $mailFromName = '';

    // Application Statistics
    public array $stats = [];

    // User Management
    public array $roles = [];
    public array $pendingUsers = [];

    // System Health
    public array $systemHealth = [];

    // Processing states
    public bool $processing = false;

    public function mount(): void
    {
        // Check if user has permission - super_admin only
        if (!Auth::user()?->hasRole('super_admin')) {
            abort(403, 'Unauthorized access to system settings.');
        }

        $this->loadSettings();
        $this->loadStats();
        $this->loadRoles();
        $this->loadSystemHealth();
    }

    /**
     * Load application settings
     */
    protected function loadSettings(): void
    {
        $this->appName = config('app.name', 'KeenPass');
        $this->appTimezone = config('app.timezone', 'Asia/Manila');
        $this->maintenanceMode = app()->isDownForMaintenance();

        // Email settings
        $this->mailDriver = config('mail.default', 'smtp');
        $this->mailHost = config('mail.mailers.smtp.host', '');
        $this->mailPort = config('mail.mailers.smtp.port', '');
        $this->mailFromAddress = config('mail.from.address', '');
        $this->mailFromName = config('mail.from.name', '');
    }

    /**
     * Load application statistics
     */
    protected function loadStats(): void
    {
        $this->stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'pending_users' => User::where('must_change_password', true)->count(),
            'total_admins' => User::role(['super_admin', 'admin_editor', 'admin_viewer', 'encoder', 'security', 'maintenance'])->count(),
            'total_applicants' => User::role('applicant')->count(),
            'users_last_7_days' => User::where('created_at', '>=', now()->subDays(7))->count(),
            'users_last_30_days' => User::where('created_at', '>=', now()->subDays(30))->count(),
        ];

        // Get pending invitation users
        $this->pendingUsers = User::where('must_change_password', true)
            ->with('details', 'roles')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(fn ($user) => [
                'id' => $user->id,
                'name' => $user->details?->full_name ?? 'Unnamed',
                'email' => $user->email,
                'role' => $user->roles->first()?->name ?? 'N/A',
                'created_at' => $user->created_at->diffForHumans(),
            ])
            ->toArray();
    }

    /**
     * Load roles and their user counts
     */
    protected function loadRoles(): void
    {
        $this->roles = Role::withCount('users')
            ->orderBy('name')
            ->get()
            ->map(fn ($role) => [
                'id' => $role->id,
                'name' => $role->name,
                'display_name' => ucwords(str_replace('_', ' ', $role->name)),
                'users_count' => $role->users_count,
                'permissions_count' => $role->permissions->count(),
            ])
            ->toArray();
    }

    /**
     * Load system health checks
     */
    protected function loadSystemHealth(): void
    {
        $this->systemHealth = [
            'database' => $this->checkDatabase(),
            'cache' => $this->checkCache(),
            'storage' => $this->checkStorage(),
            'queue' => $this->checkQueue(),
        ];
    }

    protected function checkDatabase(): array
    {
        try {
            DB::connection()->getPdo();
            return ['status' => 'ok', 'message' => 'Connected'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Connection failed'];
        }
    }

    protected function checkCache(): array
    {
        try {
            Cache::put('health_check', true, 10);
            $value = Cache::get('health_check');
            Cache::forget('health_check');
            return $value ? ['status' => 'ok', 'message' => 'Working'] : ['status' => 'warning', 'message' => 'Read failed'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Not available'];
        }
    }

    protected function checkStorage(): array
    {
        try {
            $testFile = 'health_check_' . time() . '.txt';
            Storage::put($testFile, 'test');
            Storage::delete($testFile);
            return ['status' => 'ok', 'message' => 'Writable'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Not writable'];
        }
    }

    protected function checkQueue(): array
    {
        try {
            $pendingJobs = DB::table('jobs')->count();
            $failedJobs = DB::table('failed_jobs')->count();

            if ($failedJobs > 0) {
                return ['status' => 'warning', 'message' => "{$failedJobs} failed jobs"];
            }
            return ['status' => 'ok', 'message' => "{$pendingJobs} pending"];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Not available'];
        }
    }

    /**
     * Set the active tab
     */
    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;

        // Refresh data when switching tabs
        if ($tab === 'users') {
            $this->loadStats();
            $this->loadRoles();
        } elseif ($tab === 'health') {
            $this->loadSystemHealth();
        }
    }

    /**
     * Clear application cache
     */
    public function clearCache(): void
    {
        $this->processing = true;

        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');

            $this->dispatch('toast', type: 'success', message: 'Application cache cleared successfully.');
            Log::info('Application cache cleared by admin', ['user_id' => Auth::id()]);

            $this->loadSystemHealth();
        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'Failed to clear cache: ' . $e->getMessage());
            Log::error('Cache clear failed', ['error' => $e->getMessage()]);
        } finally {
            $this->processing = false;
        }
    }

    /**
     * Toggle maintenance mode
     */
    public function toggleMaintenanceMode(): void
    {
        $this->processing = true;

        try {
            if ($this->maintenanceMode) {
                Artisan::call('up');
                $this->maintenanceMode = false;
                $this->dispatch('toast', type: 'success', message: 'Application is now live.');
            } else {
                Artisan::call('down', ['--secret' => 'admin-access-' . Auth::id()]);
                $this->maintenanceMode = true;
                $this->dispatch('toast', type: 'warning', message: 'Application is now in maintenance mode. Access with secret: admin-access-' . Auth::id());
            }

            Log::info('Maintenance mode toggled', [
                'user_id' => Auth::id(),
                'maintenance_mode' => $this->maintenanceMode,
            ]);
        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'Failed to toggle maintenance mode.');
            Log::error('Maintenance mode toggle failed', ['error' => $e->getMessage()]);
        } finally {
            $this->processing = false;
        }
    }

    /**
     * Retry failed jobs
     */
    public function retryFailedJobs(): void
    {
        $this->processing = true;

        try {
            Artisan::call('queue:retry', ['id' => 'all']);
            $this->dispatch('toast', type: 'success', message: 'Failed jobs have been requeued.');
            Log::info('Failed jobs retried', ['user_id' => Auth::id()]);

            $this->loadSystemHealth();
        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'Failed to retry jobs.');
            Log::error('Retry failed jobs failed', ['error' => $e->getMessage()]);
        } finally {
            $this->processing = false;
        }
    }

    /**
     * Flush failed jobs
     */
    public function flushFailedJobs(): void
    {
        $this->processing = true;

        try {
            Artisan::call('queue:flush');
            $this->dispatch('toast', type: 'success', message: 'Failed jobs cleared.');
            Log::info('Failed jobs flushed', ['user_id' => Auth::id()]);

            $this->loadSystemHealth();
        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'Failed to flush jobs.');
            Log::error('Flush failed jobs failed', ['error' => $e->getMessage()]);
        } finally {
            $this->processing = false;
        }
    }

    /**
     * Resend invitation to a pending user
     */
    public function resendInvitation(string $userId): void
    {
        $this->processing = true;

        try {
            $user = User::findOrFail($userId);

            $invitationService = app(\App\Services\UserInvitationService::class);
            $invitationService->resendWelcomeEmail($user);

            $this->dispatch('toast', type: 'success', message: 'Invitation resent to ' . $user->email);
            Log::info('Invitation resent', ['user_id' => $userId, 'by' => Auth::id()]);
        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'Failed to resend invitation.');
            Log::error('Resend invitation failed', ['error' => $e->getMessage()]);
        } finally {
            $this->processing = false;
        }
    }

    /**
     * Optimize the application
     */
    public function optimizeApplication(): void
    {
        $this->processing = true;

        try {
            Artisan::call('optimize');
            $this->dispatch('toast', type: 'success', message: 'Application optimized for production.');
            Log::info('Application optimized', ['user_id' => Auth::id()]);
        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'Optimization failed.');
            Log::error('Optimization failed', ['error' => $e->getMessage()]);
        } finally {
            $this->processing = false;
        }
    }

    public function render()
    {
        return view('livewire.admin.settings.system-settings');
    }
}
