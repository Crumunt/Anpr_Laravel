<?php

namespace App\Http\Controllers\ANPR;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDetails;
use App\Models\ApplicantUserSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class AnprProfileController extends Controller
{
    /**
     * Show ANPR user profile page.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login to view profile.');
        }

        // Load relationships
        $user->load(['details', 'roles', 'permissions', 'settings', 'activityLogs']);

        // Get or create user details
        $details = $user->details;
        if (!$details) {
            $details = UserDetails::create([
                'user_id' => $user->id,
                'clsu_id' => '',
                'status_id' => \App\Models\Status::where('code', 'approved')->first()?->id ?? 1,
            ]);
        }

        // Get or create user settings
        $settings = $user->settings;
        if (!$settings) {
            $settings = ApplicantUserSetting::create([
                'user_id' => $user->id,
            ]);
        }

        // Get authorization levels based on permissions
        $allPermissions = \Spatie\Permission\Models\Permission::all()->groupBy(function ($permission) {
            $parts = explode(' ', $permission->name);
            return ucfirst($parts[0] ?? 'other');
        });

        $userPermissions = $user->getAllPermissions()->pluck('name')->toArray();

        // Format authorization levels for display
        $authorizationLevels = $this->formatAuthorizationLevels($userPermissions);

        // Get activity logs
        $activityLogs = $user->activityLogs()
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        // Get account status information
        $accountStatus = [
            'status' => $details->is_active ?? true ? 'Active' : ($details->is_active === false ? 'Inactive' : 'Suspended'),
            'status_class' => $details->is_active ?? true ? 'bg-emerald-100 text-emerald-700' : ($details->is_active === false ? 'bg-gray-100 text-gray-700' : 'bg-red-100 text-red-700'),
            'created_at' => $user->created_at,
            'last_activity' => $user->activityLogs()->latest()->first()?->created_at ?? $user->created_at,
            'email_verified' => $user->email_verified_at !== null,
            'last_login' => $details->last_login_at,
        ];

        // Get login history
        $loginHistory = $user->activityLogs()
            ->where('action_type', 'login')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get profile update history
        $profileUpdates = $user->activityLogs()
            ->where('action_type', 'profile_update')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Calculate usage statistics
        $usageStats = [
            'total_logins' => $user->activityLogs()->where('action_type', 'login')->count(),
            'total_actions' => $user->activityLogs()->count(),
            'last_30_days_actions' => $user->activityLogs()
                ->where('created_at', '>=', now()->subDays(30))
                ->count(),
            'most_used_feature' => $this->getMostUsedFeature($user),
        ];

        return view('components.anpr.user-management.profile', compact(
            'user',
            'details',
            'settings',
            'authorizationLevels',
            'activityLogs',
            'accountStatus',
            'loginHistory',
            'profileUpdates',
            'usageStats'
        ));
    }

    /**
     * Update ANPR user profile information.
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login to update profile.');
        }

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id . ',id'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'extension' => ['nullable', 'string', 'max:10'],
            'employee_id' => ['nullable', 'string', 'max:50'],
            'department' => ['nullable', 'string', 'max:255'],
            'designation' => ['nullable', 'string', 'max:255'],
            'badge_id' => ['nullable', 'string', 'max:50'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'assigned_gates' => ['nullable', 'array'],
            'work_schedule' => ['nullable', 'array'],
            'shifts' => ['nullable', 'array'],
            'authorization_level' => ['nullable', 'string', 'max:50'],
        ]);

        try {
            // Update user basic info
            $user->first_name = $validated['first_name'];
            $user->middle_name = $validated['middle_name'] ?? null;
            $user->last_name = $validated['last_name'];
            $user->email = $validated['email'];
            $user->save();

            // Get or create user details
            $details = $user->details;
            if (!$details) {
                $details = UserDetails::create([
                    'user_id' => $user->id,
                    'clsu_id' => $validated['employee_id'] ?? '',
                    'status_id' => \App\Models\Status::where('code', 'approved')->first()?->id ?? 1,
                ]);
            }

            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                // Delete old photo if exists
                if ($details->profile_photo) {
                    Storage::disk('public')->delete($details->profile_photo);
                }
                $path = $request->file('profile_photo')->store('profile-photos', 'public');
                $validated['profile_photo'] = $path;
            }

            // Update user details
            $details->phone_number = $validated['phone_number'] ?? $details->phone_number;
            $details->extension = $validated['extension'] ?? $details->extension;
            $details->employee_id = $validated['employee_id'] ?? $details->employee_id;
            $details->department = $validated['department'] ?? $details->department;
            $details->designation = $validated['designation'] ?? $details->designation;
            $details->badge_id = $validated['badge_id'] ?? $details->badge_id;
            if (isset($validated['profile_photo'])) {
                $details->profile_photo = $validated['profile_photo'];
            }
            $details->assigned_gates = $validated['assigned_gates'] ?? $details->assigned_gates;
            $details->work_schedule = $validated['work_schedule'] ?? $details->work_schedule;
            $details->shifts = $validated['shifts'] ?? $details->shifts;
            $details->authorization_level = $validated['authorization_level'] ?? $details->authorization_level;
            $details->save();

            return redirect()->route('anpr.user-management.profile')
                ->with('success', 'Profile updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('anpr.user-management.profile')
                ->with('error', 'Failed to update profile: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update ANPR user settings (notifications and preferences).
     */
    public function updateSettings(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login to update settings.');
        }

        $validated = $request->validate([
            'timezone' => ['required', 'string'],
            'language' => ['required', 'string'],
            'default_gate_view' => ['nullable', 'string', 'max:255'],
            'dashboard_layout' => ['nullable', 'string', 'max:50'],
            'entry_exit_alerts' => ['nullable', 'boolean'],
            'violation_reports' => ['nullable', 'boolean'],
            'system_maintenance' => ['nullable', 'boolean'],
            'emergency_alerts' => ['nullable', 'boolean'],
        ]);

        try {
            // Get or create settings
            $settings = $user->settings;
            if (!$settings) {
                $settings = ApplicantUserSetting::create([
                    'user_id' => $user->id,
                ]);
            }

            // Update settings
            $settings->timezone = $validated['timezone'];
            $settings->language = $validated['language'];
            $settings->default_gate_view = $validated['default_gate_view'] ?? null;
            $settings->dashboard_layout = $validated['dashboard_layout'] ?? 'default';
            $settings->entry_exit_alerts = $request->has('entry_exit_alerts');
            $settings->violation_reports = $request->has('violation_reports');
            $settings->maintenance_notifications = $request->has('system_maintenance');
            $settings->emergency_alerts = $request->has('emergency_alerts');
            $settings->save();

            return redirect()->route('anpr.user-management.profile')
                ->with('success', 'Settings updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('anpr.user-management.profile')
                ->with('error', 'Failed to update settings: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Format user permissions into authorization levels display format.
     */
    private function formatAuthorizationLevels(array $userPermissions): array
    {
        $modules = [
            'Gate Monitoring' => ['view' => false, 'edit' => false, 'delete' => false],
            'Violation Reports' => ['view' => false, 'edit' => false, 'delete' => false],
            'Personnel Directory' => ['view' => false, 'edit' => false, 'delete' => false],
            'System Configuration' => ['view' => false, 'edit' => false, 'delete' => false],
        ];

        foreach ($userPermissions as $permission) {
            $permissionLower = strtolower($permission);

            // Gate Monitoring
            if (str_contains($permissionLower, 'gate') || str_contains($permissionLower, 'monitor')) {
                $modules['Gate Monitoring']['view'] = true;
                if (str_contains($permissionLower, 'edit') || str_contains($permissionLower, 'update')) {
                    $modules['Gate Monitoring']['edit'] = true;
                }
                if (str_contains($permissionLower, 'delete') || str_contains($permissionLower, 'remove')) {
                    $modules['Gate Monitoring']['delete'] = true;
                }
            }

            // Violation Reports
            if (str_contains($permissionLower, 'violation') || str_contains($permissionLower, 'report')) {
                $modules['Violation Reports']['view'] = true;
                if (str_contains($permissionLower, 'edit') || str_contains($permissionLower, 'update')) {
                    $modules['Violation Reports']['edit'] = true;
                }
                if (str_contains($permissionLower, 'delete') || str_contains($permissionLower, 'remove')) {
                    $modules['Violation Reports']['delete'] = true;
                }
            }

            // Personnel Directory
            if (str_contains($permissionLower, 'personnel') || str_contains($permissionLower, 'user')) {
                $modules['Personnel Directory']['view'] = true;
                if (str_contains($permissionLower, 'edit') || str_contains($permissionLower, 'update')) {
                    $modules['Personnel Directory']['edit'] = true;
                }
            }

            // System Configuration
            if (str_contains($permissionLower, 'system') || str_contains($permissionLower, 'config')) {
                $modules['System Configuration']['view'] = true;
                if (str_contains($permissionLower, 'edit') || str_contains($permissionLower, 'update')) {
                    $modules['System Configuration']['edit'] = true;
                }
            }
        }

            return array_map(function ($module, $permissions) {
                return [
                    'module' => $module,
                    'view' => $permissions['view'],
                    'edit' => $permissions['edit'],
                    'delete' => $permissions['delete'],
                ];
            }, array_keys($modules), $modules);
    }

    /**
     * Get most used feature from activity logs
     */
    private function getMostUsedFeature($user): string
    {
        $features = $user->activityLogs()
            ->whereNotNull('action_type')
            ->selectRaw('action_type, COUNT(*) as count')
            ->groupBy('action_type')
            ->orderBy('count', 'desc')
            ->first();

        if ($features) {
            return ucwords(str_replace('_', ' ', $features->action_type));
        }

        return 'N/A';
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login to update password.');
        }

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return redirect()->route('anpr.user-management.profile')
                ->with('error', 'Current password is incorrect.')
                ->withInput();
        }

        try {
            $user->password = Hash::make($validated['password']);
            $user->save();

            // Log the activity
            \App\Models\ActivityLog::log(
                $user->id,
                'password_change',
                'Password Changed',
                'User changed their password',
                ['ip' => $request->ip()]
            );

            return redirect()->route('anpr.user-management.profile')
                ->with('success', 'Password updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('anpr.user-management.profile')
                ->with('error', 'Failed to update password: ' . $e->getMessage())
                ->withInput();
        }
    }
}
