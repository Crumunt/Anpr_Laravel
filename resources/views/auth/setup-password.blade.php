@extends('layouts.app-layout')

@section('title', 'Set Up Your Password')

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

    .setup-container {
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

    .btn-primary:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
    }

    .input-field {
        transition: all 0.3s ease;
    }

    .input-field:focus {
        border-color: var(--clsu-green);
        box-shadow: 0 0 0 3px rgba(0, 99, 0, 0.1);
    }

    .green-gradient-bg {
        background: linear-gradient(135deg, var(--clsu-green) 0%, var(--clsu-light-green) 50%, var(--clsu-dark-green) 100%);
    }

    .accent-border {
        border-bottom: 4px solid var(--clsu-accent);
    }

    .password-strength {
        height: 4px;
        border-radius: 2px;
        transition: all 0.3s ease;
    }

    .requirement {
        transition: all 0.2s ease;
    }

    .requirement.met {
        color: #16a34a;
    }

    .requirement.unmet {
        color: #9ca3af;
    }
</style>
@endpush

@section('main-content')
<div class="min-h-screen flex">
    <!-- Left Side - Branding Panel -->
    <div class="hidden lg:flex lg:w-1/2 green-gradient-bg relative overflow-hidden">
        <!-- Decorative Elements -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-20 left-20 w-64 h-64 bg-white rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-20 w-96 h-96 bg-white rounded-full blur-3xl"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 flex flex-col items-center justify-center w-full px-12 text-white">
            <div class="mb-8">
                <img src="{{ asset('images/CLSU.png') }}" alt="CLSU Logo" class="w-32 h-32 object-contain drop-shadow-2xl">
            </div>
            <h1 class="text-4xl font-bold mb-4 text-center">Welcome to {{ config('app.name') }}</h1>
            <p class="text-xl opacity-90 text-center mb-8">Set up your account to get started</p>

            <!-- Welcome Info -->
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 max-w-sm">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold">{{ $user->details?->first_name ?? 'User' }} {{ $user->details?->last_name ?? '' }}</p>
                        <p class="text-sm opacity-75">{{ $email }}</p>
                    </div>
                </div>
                <p class="text-sm opacity-90">
                    Your account has been created. Please set a secure password to activate your account.
                </p>
            </div>
        </div>
    </div>

    <!-- Right Side - Password Setup Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center px-6 py-12 bg-gray-50">
        <div class="w-full max-w-md">
            <!-- Mobile Logo -->
            <div class="lg:hidden flex flex-col items-center mb-8">
                <img src="{{ asset('images/CLSU.png') }}" alt="CLSU Logo" class="w-20 h-20 object-contain mb-4">
                <h2 class="text-xl font-bold text-[#006300]">{{ config('app.name') }}</h2>
            </div>

            <!-- Setup Card -->
            <div class="setup-container bg-white rounded-2xl p-8 accent-border">
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Set Up Your Password</h2>
                    <p class="mt-2 text-sm text-gray-600">Create a secure password to activate your account</p>
                </div>

                <!-- Mobile User Info -->
                <div class="lg:hidden mb-6 p-4 bg-green-50 border border-green-100 rounded-lg">
                    <p class="text-sm text-green-800">
                        <span class="font-medium">Welcome, {{ $user->details?->first_name ?? 'User' }}!</span><br>
                        <span class="text-green-600">{{ $email }}</span>
                    </p>
                </div>

                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <ul class="text-sm text-red-700 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $error }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.setup.store') }}" class="space-y-6" id="password-form">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ $email }}">

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            New Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input
                                id="password"
                                name="password"
                                type="password"
                                required
                                autocomplete="new-password"
                                class="input-field block w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg focus:outline-none text-sm"
                                placeholder="Enter your new password"
                            >
                            <button
                                type="button"
                                onclick="togglePasswordVisibility('password')"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center"
                            >
                                <svg id="password-eye-open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="password-eye-closed" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 hover:text-gray-600 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>

                        <!-- Password Strength Indicator -->
                        <div class="mt-2">
                            <div class="flex gap-1">
                                <div id="strength-1" class="password-strength flex-1 bg-gray-200"></div>
                                <div id="strength-2" class="password-strength flex-1 bg-gray-200"></div>
                                <div id="strength-3" class="password-strength flex-1 bg-gray-200"></div>
                                <div id="strength-4" class="password-strength flex-1 bg-gray-200"></div>
                            </div>
                            <p id="strength-text" class="text-xs mt-1 text-gray-500">Enter a password</p>
                        </div>
                    </div>

                    <!-- Confirm Password Field -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirm Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <input
                                id="password_confirmation"
                                name="password_confirmation"
                                type="password"
                                required
                                autocomplete="new-password"
                                class="input-field block w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg focus:outline-none text-sm"
                                placeholder="Confirm your password"
                            >
                            <button
                                type="button"
                                onclick="togglePasswordVisibility('password_confirmation')"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center"
                            >
                                <svg id="password_confirmation-eye-open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="password_confirmation-eye-closed" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 hover:text-gray-600 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                        <p id="match-status" class="text-xs mt-1 text-gray-500"></p>
                    </div>

                    <!-- Password Requirements -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-xs font-medium text-gray-700 mb-2">Password Requirements:</p>
                        <ul class="space-y-1 text-xs">
                            <li id="req-length" class="requirement unmet flex items-center gap-2">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                At least 8 characters
                            </li>
                            <li id="req-uppercase" class="requirement unmet flex items-center gap-2">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                One uppercase letter
                            </li>
                            <li id="req-lowercase" class="requirement unmet flex items-center gap-2">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                One lowercase letter
                            </li>
                            <li id="req-number" class="requirement unmet flex items-center gap-2">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                One number
                            </li>
                            <li id="req-special" class="requirement unmet flex items-center gap-2">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                One special character (!@#$%^&*)
                            </li>
                        </ul>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        id="submit-btn"
                        class="btn-primary w-full py-3 px-4 rounded-lg text-white font-semibold text-sm disabled:opacity-50"
                    >
                        <span class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Activate My Account
                        </span>
                    </button>
                </form>

                <!-- Back to Login Link -->
                <div class="mt-6 text-center">
                    <a href="{{ route('login') }}" class="text-sm text-[#006300] hover:text-[#004d00] font-medium">
                        ← Back to Login
                    </a>
                </div>
            </div>

            <!-- Footer -->
            <p class="mt-8 text-center text-xs text-gray-500">
                © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function togglePasswordVisibility(fieldId) {
        const input = document.getElementById(fieldId);
        const eyeOpen = document.getElementById(fieldId + '-eye-open');
        const eyeClosed = document.getElementById(fieldId + '-eye-closed');

        if (input.type === 'password') {
            input.type = 'text';
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
        } else {
            input.type = 'password';
            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
        }
    }

    // Password validation
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    const submitBtn = document.getElementById('submit-btn');

    const requirements = {
        length: { el: document.getElementById('req-length'), test: (p) => p.length >= 8 },
        uppercase: { el: document.getElementById('req-uppercase'), test: (p) => /[A-Z]/.test(p) },
        lowercase: { el: document.getElementById('req-lowercase'), test: (p) => /[a-z]/.test(p) },
        number: { el: document.getElementById('req-number'), test: (p) => /[0-9]/.test(p) },
        special: { el: document.getElementById('req-special'), test: (p) => /[!@#$%^&*(),.?":{}|<>]/.test(p) }
    };

    const strengthBars = [
        document.getElementById('strength-1'),
        document.getElementById('strength-2'),
        document.getElementById('strength-3'),
        document.getElementById('strength-4')
    ];
    const strengthText = document.getElementById('strength-text');
    const matchStatus = document.getElementById('match-status');

    function checkPassword() {
        const password = passwordInput.value;
        const confirm = confirmInput.value;

        // Check requirements
        let metCount = 0;
        for (const [key, req] of Object.entries(requirements)) {
            const met = req.test(password);
            req.el.classList.toggle('met', met);
            req.el.classList.toggle('unmet', !met);
            if (met) metCount++;
        }

        // Update strength indicator
        const colors = ['bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-green-500'];
        const texts = ['Weak', 'Fair', 'Good', 'Strong'];

        strengthBars.forEach((bar, index) => {
            bar.className = 'password-strength flex-1';
            if (password.length === 0) {
                bar.classList.add('bg-gray-200');
            } else if (index < metCount) {
                const colorIndex = Math.min(metCount - 1, 3);
                bar.classList.add(colors[colorIndex]);
            } else {
                bar.classList.add('bg-gray-200');
            }
        });

        if (password.length === 0) {
            strengthText.textContent = 'Enter a password';
            strengthText.className = 'text-xs mt-1 text-gray-500';
        } else {
            const strengthIndex = Math.min(metCount - 1, 3);
            strengthText.textContent = texts[strengthIndex] || 'Very Weak';
            const textColors = ['text-red-600', 'text-orange-600', 'text-yellow-600', 'text-green-600'];
            strengthText.className = 'text-xs mt-1 ' + (textColors[strengthIndex] || 'text-red-600');
        }

        // Check password match
        if (confirm.length > 0) {
            if (password === confirm) {
                matchStatus.textContent = '✓ Passwords match';
                matchStatus.className = 'text-xs mt-1 text-green-600';
            } else {
                matchStatus.textContent = '✗ Passwords do not match';
                matchStatus.className = 'text-xs mt-1 text-red-600';
            }
        } else {
            matchStatus.textContent = '';
        }

        // Enable/disable submit button
        const allMet = metCount === Object.keys(requirements).length;
        const passwordsMatch = password === confirm && confirm.length > 0;
        submitBtn.disabled = !(allMet && passwordsMatch);
    }

    passwordInput.addEventListener('input', checkPassword);
    confirmInput.addEventListener('input', checkPassword);

    // Initial check
    checkPassword();
</script>
@endpush
