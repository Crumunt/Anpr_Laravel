<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
	/**
	 * Show settings page for current user.
	 */
	public function index(Request $request)
	{
		$user = $request->user(); // may be null; view will show placeholders
		return view('admin.settings.index', compact('user'));
	}

	/**
	 * Update the current user's basic profile information.
	 */
	public function update(Request $request)
	{
		$user = $request->user();
		if (!$user) {
			return redirect('/')->with('error', 'Please login to update settings.');
		}

		$validated = $request->validate([
			'first_name' => ['required', 'string', 'max:255'],
			'middle_name' => ['nullable', 'string', 'max:255'],
			'last_name' => ['required', 'string', 'max:255'],
			'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id . ',id'],
			'password' => ['nullable', 'string', 'min:8', 'confirmed'],
		]);

		$user->first_name = $validated['first_name'];
		$user->middle_name = $validated['middle_name'] ?? null;
		$user->last_name = $validated['last_name'];
		$user->email = $validated['email'];
		if (!empty($validated['password'])) {
			$user->password = Hash::make($validated['password']);
		}
		$user->save();

		return redirect()->route('admin.settings')->with('success', 'Profile updated successfully.');
	}
}


