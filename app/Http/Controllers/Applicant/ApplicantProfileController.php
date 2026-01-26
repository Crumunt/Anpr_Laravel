<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ApplicantProfileController extends Controller
{
    /**
     * Show applicant profile page.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return redirect('/')->with('error', 'Please login to view profile.');
        }

        // Load relationships
        $user->load(['details', 'vehicles.status', 'applicantSettings', 'activityLogs']);

        // Calculate gate pass statistics
        $vehicles = $user->vehicles;
        $gatePassStats = [
            'total' => $vehicles->count(),
            'active' => $vehicles->filter(function($vehicle) {
                return $vehicle->status && strtolower($vehicle->status->code ?? '') === 'approved';
            })->count(),
            'pending' => $vehicles->filter(function($vehicle) {
                return $vehicle->status && strtolower($vehicle->status->code ?? '') === 'pending';
            })->count(),
            'expired' => 0, // Placeholder - would need expiry dates in database
        ];

        // Get activity logs
        $activityLogs = $user->activityLogs()
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        // Get account status information
        $details = $user->details;
        $accountStatus = [
            'status' => ($details && ($details->is_active ?? true)) ? 'Active' : (($details && $details->is_active === false) ? 'Inactive' : 'Suspended'),
            'status_class' => ($details && ($details->is_active ?? true)) ? 'bg-emerald-100 text-emerald-700' : (($details && $details->is_active === false) ? 'bg-gray-100 text-gray-700' : 'bg-red-100 text-red-700'),
            'created_at' => $user->created_at,
            'last_activity' => $user->activityLogs()->latest()->first()?->created_at ?? $user->created_at,
            'email_verified' => $user->email_verified_at !== null,
            'last_login' => $details ? ($details->last_login_at ?? null) : null,
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

        return view('applicant.profile.index', compact('user', 'settings', 'gatePassStats', 'activityLogs', 'accountStatus', 'loginHistory', 'profileUpdates', 'usageStats'));
    }

    /**
     * Update applicant profile settings.
     */
    public function updateSettings(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return redirect('/')->with('error', 'Please login to update settings.');
        }

        $validated = $request->validate([
            'two_factor_enabled' => ['nullable', 'boolean'],
            'account_privacy' => ['required', 'string', 'in:public,private,restricted'],
            'gate_pass_renewal_reminders' => ['nullable', 'boolean'],
            'entry_exit_notifications' => ['nullable', 'boolean'],
            'maintenance_alerts' => ['nullable', 'boolean'],
            'emergency_notifications' => ['nullable', 'boolean'],
            'sms_notifications' => ['nullable', 'boolean'],
            'email_notifications' => ['nullable', 'boolean'],
            'preferred_contact_method' => ['required', 'string', 'in:email,sms,both'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:20'],
            'emergency_contact_email' => ['nullable', 'email', 'max:255'],
        ]);

        try {
            // Get or create settings
            $settings = $user->applicantSettings;
            if (!$settings) {
                $settings = \App\Models\ApplicantUserSetting::create([
                    'id' => \Illuminate\Support\Str::uuid(),
                    'user_id' => $user->id,
                ]);
            }

            // Update settings
            $settings->two_factor_enabled = $request->has('two_factor_enabled');
            $settings->account_privacy = $validated['account_privacy'];
            $settings->gate_pass_renewal_reminders = $request->has('gate_pass_renewal_reminders');
            $settings->entry_exit_notifications = $request->has('entry_exit_notifications');
            $settings->maintenance_alerts = $request->has('maintenance_alerts');
            $settings->emergency_notifications = $request->has('emergency_notifications');
            $settings->sms_notifications = $request->has('sms_notifications');
            $settings->email_notifications = $request->has('email_notifications');
            $settings->preferred_contact_method = $validated['preferred_contact_method'];
            $settings->emergency_contact_name = $validated['emergency_contact_name'] ?? null;
            $settings->emergency_contact_phone = $validated['emergency_contact_phone'] ?? null;
            $settings->emergency_contact_email = $validated['emergency_contact_email'] ?? null;
            $settings->save();

            // Regenerate CSRF token after successful save
            $request->session()->regenerateToken();

            return redirect()->route('applicant.profile')->with('success', 'Settings updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('applicant.profile')
                ->with('error', 'Failed to update settings: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update password.
     */
    public function updatePassword(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return redirect('/')->with('error', 'Please login to update password.');
        }

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Verify current password
        if (!Hash::check($validated['current_password'], $user->password)) {
            return redirect()->route('applicant.profile')
                ->with('error', 'Current password is incorrect.')
                ->withInput();
        }

        try {
            $user->password = Hash::make($validated['password']);
            $user->save();

            $request->session()->regenerateToken();

            return redirect()->route('applicant.profile')->with('success', 'Password updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('applicant.profile')
                ->with('error', 'Failed to update password.')
                ->withInput();
        }
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
}
