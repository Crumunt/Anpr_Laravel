<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

/**
 * Provides role-based access control methods for Livewire components and views.
 */
trait HasRoleBasedAccess
{
    /**
     * Check if user has any of the specified roles
     */
    public function hasAnyRole(array $roles): bool
    {
        $user = Auth::user();
        return $user && $user->hasAnyRole($roles);
    }

    /**
     * Check if user can perform CRUD operations (create, update, delete)
     */
    public function canPerformCrud(): bool
    {
        return $this->hasAnyRole(['super_admin', 'admin_editor', 'encoder']);
    }

    /**
     * Check if user can only view (no edit/delete)
     */
    public function isViewerOnly(): bool
    {
        $user = Auth::user();
        if (!$user) return true;

        // If user is viewer only and not a higher role
        return $user->hasRole('admin_viewer') &&
               !$user->hasAnyRole(['super_admin', 'admin_editor', 'encoder']);
    }

    /**
     * Check if user can manage admins (super_admin and admin_editor only)
     */
    public function canManageAdmins(): bool
    {
        return $this->hasAnyRole(['super_admin', 'admin_editor']);
    }

    /**
     * Check if user can manage applicants
     */
    public function canManageApplicants(): bool
    {
        return $this->hasAnyRole(['super_admin', 'admin_editor', 'encoder']);
    }

    /**
     * Check if user can approve/reject applications
     */
    public function canApproveApplications(): bool
    {
        return $this->hasAnyRole(['super_admin', 'admin_editor']);
    }

    /**
     * Check if user can manage vehicles
     */
    public function canManageVehicles(): bool
    {
        return $this->hasAnyRole(['super_admin', 'admin_editor', 'encoder']);
    }

    /**
     * Check if user can view vehicles
     */
    public function canViewVehicles(): bool
    {
        return $this->hasAnyRole(['super_admin', 'admin_editor', 'admin_viewer', 'encoder', 'security']);
    }

    /**
     * Check if user can manage gate passes
     */
    public function canManageGatePasses(): bool
    {
        return $this->hasAnyRole(['super_admin', 'admin_editor', 'encoder']);
    }

    /**
     * Check if user can view gate passes
     */
    public function canViewGatePasses(): bool
    {
        return $this->hasAnyRole(['super_admin', 'admin_editor', 'admin_viewer', 'encoder', 'security']);
    }

    /**
     * Check if user can delete records
     */
    public function canDelete(): bool
    {
        return $this->hasAnyRole(['super_admin', 'admin_editor']);
    }

    /**
     * Check if user can access security features
     */
    public function canAccessSecurityFeatures(): bool
    {
        return $this->hasAnyRole(['super_admin', 'admin_editor', 'security']);
    }

    /**
     * Check if user can access maintenance features
     */
    public function canAccessMaintenance(): bool
    {
        return $this->hasAnyRole(['super_admin', 'admin_editor', 'maintenance']);
    }

    /**
     * Check if user can access reports
     */
    public function canViewReports(): bool
    {
        return $this->hasAnyRole(['super_admin', 'admin_editor', 'admin_viewer', 'maintenance']);
    }

    /**
     * Check if user is super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasAnyRole(['super_admin']);
    }

    /**
     * Check if user is security admin
     */
    public function isSecurityAdmin(): bool
    {
        return $this->hasAnyRole(['security_admin']);
    }

    /**
     * Check if user can manage security accounts
     */
    public function canManageSecurityAccounts(): bool
    {
        return $this->hasAnyRole(['security_admin']);
    }

    /**
     * Get the current user's primary role for display
     */
    public function getUserRole(): string
    {
        $user = Auth::user();
        if (!$user) return 'guest';

        return $user->roles->first()?->name ?? 'user';
    }

    /**
     * Get bulk actions filtered by user permissions
     */
    protected function getPermittedBulkActions(array $actions, string $type): array
    {
        $user = Auth::user();
        if (!$user) return [];

        $permitted = [];

        foreach ($actions as $action => $config) {
            $allowed = match($action) {
                // Delete requires super_admin or admin_editor
                'delete' => $user->hasAnyRole(['super_admin', 'admin_editor']),

                // Status changes (activate, deactivate) require editor or higher
                'activate', 'deactivate' => $user->hasAnyRole(['super_admin', 'admin_editor', 'encoder']),

                // Archive/restore for applicants
                'archive', 'restore' => $user->hasAnyRole(['super_admin', 'admin_editor']),

                // Password reset for admins requires admin management permission
                'reset-password' => $type === 'admin' && $user->hasAnyRole(['super_admin', 'admin_editor']),

                // Default: allow for editors and above
                default => $user->hasAnyRole(['super_admin', 'admin_editor']),
            };

            if ($allowed) {
                $permitted[$action] = $config;
            }
        }

        return $permitted;
    }
}
