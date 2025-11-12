@extends('layouts.app-layout')
@section('main-content')
	<div class="flex-1 md:ml-64 p-6 pt-24">
		<div class="w-full bg-white rounded-xl shadow-sm border border-gray-100 transition-all duration-300 hover:shadow-md">
			<div class="p-6 space-y-6">
				<div class="flex items-center justify-between">
					<h2 class="text-2xl font-bold text-gray-800">Settings</h2>
					<!-- Light/Dark Mode button (no functionality yet) -->
					<button type="button" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 text-gray-700">
						<i class="fas fa-adjust"></i>
						<span>Light / Dark Mode</span>
					</button>
				</div>

				<p class="text-sm text-gray-500">This page shows placeholder fields. Editing and saving are disabled.</p>

				<form class="space-y-6 max-w-3xl">
					<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
						<div>
							<label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
							<input type="text" name="first_name" value="{{ $user->first_name ?? '' }}" placeholder="First name" disabled class="w-full px-3 py-2 border border-gray-200 rounded-md bg-gray-50 text-gray-600">
						</div>
						<div>
							<label class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
							<input type="text" name="middle_name" value="{{ $user->middle_name ?? '' }}" placeholder="Middle name" disabled class="w-full px-3 py-2 border border-gray-200 rounded-md bg-gray-50 text-gray-600">
						</div>
						<div>
							<label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
							<input type="text" name="last_name" value="{{ $user->last_name ?? '' }}" placeholder="Last name" disabled class="w-full px-3 py-2 border border-gray-200 rounded-md bg-gray-50 text-gray-600">
						</div>
						<div class="md:col-span-3">
							<label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
							<input type="email" name="email" value="{{ $user->email ?? '' }}" placeholder="email@example.com" disabled class="w-full px-3 py-2 border border-gray-200 rounded-md bg-gray-50 text-gray-600">
						</div>
					</div>

					<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
						<div>
							<label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
							<input type="password" name="password" placeholder="••••••••" disabled class="w-full px-3 py-2 border border-gray-200 rounded-md bg-gray-50 text-gray-600">
						</div>
						<div>
							<label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
							<input type="password" name="password_confirmation" placeholder="••••••••" disabled class="w-full px-3 py-2 border border-gray-200 rounded-md bg-gray-50 text-gray-600">
						</div>
					</div>

					<!-- No submit button; placeholders only -->
				</form>
			</div>
		</div>
	</div>
	<x-footer.footer></x-footer.footer>
@endsection


