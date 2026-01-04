@extends('layouts.app-layout')

@section('title', 'Forgot Password')

@section('hide-navbar', true)

@section('body-class', 'bg-gray-50')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

    :root {
        --clsu-green: #006300;
        --clsu-light-green: #068406;
        --clsu-dark-green: #004d00;
        --clsu-accent: #f3c423;
    }

    body {
        font-family: 'Poppins', sans-serif;
    }

    .form-container {
        box-shadow: 0 25px 50px -12px rgba(0, 99, 0, 0.25);
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--clsu-green) 0%, var(--clsu-light-green) 100%);
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, var(--clsu-dark-green) 0%, var(--clsu-green) 100%);
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0, 99, 0, 0.3);
    }

    .input-field {
        transition: all 0.3s ease;
    }

    .input-field:focus {
        border-color: var(--clsu-green);
        box-shadow: 0 0 0 3px rgba(0, 99, 0, 0.1);
    }

    .accent-border {
        border-bottom: 4px solid var(--clsu-accent);
    }
</style>
@endpush

@section('main-content')
<div class="min-h-screen flex items-center justify-center px-6 py-12 bg-gray-50">
    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="flex flex-col items-center mb-8">
            <img src="{{ asset('images/CLSU.png') }}" alt="CLSU Logo" class="w-20 h-20 object-contain mb-4">
            <h2 class="text-xl font-bold text-[#006300]">CLSU ANPR System</h2>
        </div>

        <!-- Form Card -->
        <div class="form-container bg-white rounded-2xl p-8 accent-border">
            <div class="text-center mb-6">
                <div class="mx-auto w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#006300]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900">Forgot Password?</h2>
                <p class="mt-2 text-sm text-gray-600">
                    No worries! Enter your email and we'll send you a reset link.
                </p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <p class="text-sm text-green-700">{{ session('status') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email Address
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            autocomplete="email"
                            required
                            autofocus
                            class="input-field block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none text-sm @error('email') border-red-500 @enderror"
                            placeholder="Enter your email"
                        >
                    </div>
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="btn-primary w-full flex items-center justify-center py-3 px-4 border border-transparent rounded-lg text-white font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#006300]"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span>Send Reset Link</span>
                </button>
            </form>

            <!-- Back to Login -->
            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="inline-flex items-center text-sm font-medium text-[#006300] hover:text-[#004d00] transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Login
                </a>
            </div>

            <!-- Footer -->
            <div class="mt-8 text-center">
                <p class="text-xs text-gray-500">
                    © {{ date('Y') }} Central Luzon State University. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
