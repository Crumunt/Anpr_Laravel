<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserInvitationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class SetupPasswordController extends Controller
{
    public function __construct(
        protected UserInvitationService $invitationService
    ) {}

    /**
     * Display the password setup form.
     */
    public function create(Request $request): View|RedirectResponse
    {
        $token = $request->query('token');
        $email = $request->query('email');

        // Validate required parameters
        if (!$token || !$email) {
            return redirect()->route('login')
                ->with('error', 'Invalid password setup link.');
        }

        // Find the user
        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'User not found.');
        }

        // Check if user already set their password
        if (!$user->must_change_password) {
            return redirect()->route('login')
                ->with('info', 'You have already set up your password. Please log in.');
        }

        // Verify the token
        if (!$this->invitationService->verifyToken($email, $token)) {
            return redirect()->route('login')
                ->with('error', 'This password setup link has expired or is invalid. Please contact an administrator to resend the invitation.');
        }

        // Load user details for display
        $user->load('details');

        return view('auth.setup-password', [
            'token' => $token,
            'email' => $email,
            'user' => $user,
        ]);
    }

    /**
     * Handle the password setup request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $token = $request->input('token');
        $email = $request->input('email');

        // Find the user
        $user = User::where('email', $email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'User not found.',
            ]);
        }

        // Check if user already set their password
        if (!$user->must_change_password) {
            return redirect()->route('login')
                ->with('info', 'You have already set up your password. Please log in.');
        }

        // Verify the token again
        if (!$this->invitationService->verifyToken($email, $token)) {
            return back()->withErrors([
                'token' => 'This password setup link has expired or is invalid.',
            ]);
        }

        // Set the password
        $success = $this->invitationService->setUserPassword($user, $request->input('password'));

        if (!$success) {
            return back()->withErrors([
                'password' => 'Failed to set password. Please try again.',
            ]);
        }

        Log::info('User completed password setup', [
            'user_id' => $user->id,
            'email' => $user->email,
        ]);

        // Log the user in
        Auth::login($user);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Your password has been set successfully. Welcome to ' . config('app.name') . '!');
    }

    /**
     * Request a new invitation email.
     */
    public function resend(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->input('email'))
            ->where('must_change_password', true)
            ->first();

        if (!$user) {
            // Don't reveal if user exists or not
            return back()->with('status', 'If your email is registered and pending setup, you will receive a new invitation link.');
        }

        $this->invitationService->resendWelcomeEmail($user);

        return back()->with('status', 'A new password setup link has been sent to your email.');
    }
}
