@extends('layouts.app-layout')

@section('title', 'Login')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&display=swap');

    body {
        font-family: 'Manrope', sans-serif;
        background-color: #f9fafb;
    }

    .form-container {
        box-shadow: 0 10px 25px -5px rgba(0, 168, 107, 0.2);
    }

    .btn-primary {
        background-image: linear-gradient(to right, #006400, #00A86B);
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background-image: linear-gradient(to right, #005000, #008551);
        transform: translateY(-2px);
    }

    .input-focus:focus {
        border-color: #00A86B;
        box-shadow: 0 0 0 2px rgba(0, 168, 107, 0.2);
    }
</style>

@section('hide-navbar', true)

@section('body-class', 'bg-white')

@section('main-content')
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 form-container bg-white p-10 rounded-2xl">
            <div class="text-center">
                <div class="flex justify-center items-center mb-6">
                    <svg class="h-12 w-12" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="#FFD700">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4" />
                    </svg>
                </div>
                <h2 class="mt-2 text-3xl font-extrabold text-gray-900">
                    Welcome back
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Please sign in to your account
                </p>
            </div>

            <form class="mt-8 space-y-6" action="#" method="POST">
                <input type="hidden" name="remember" value="true">
                <div class="rounded-md space-y-4">
                    <div>
                        <label for="email-address" class="block text-sm font-medium text-gray-700">Email address</label>
                        <input id="email-address" name="email" type="email" autocomplete="email" required
                            class="mt-1 input-focus appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none sm:text-sm">
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <div class="mt-1 relative">
                            <input id="password" name="password" type="password" autocomplete="current-password" required
                                class="input-focus appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none sm:text-sm">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
                                <button type="button" class="text-gray-500 hover:text-secondary-600 focus:outline-none"
                                    id="togglePassword">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember-me" name="remember-me" type="checkbox"
                            class="h-4 w-4 border-gray-300 rounded focus:ring-secondary-500 text-secondary-600">
                        <label for="remember-me" class="ml-2 block text-sm text-gray-600">
                            Remember me
                        </label>
                    </div>

                    <div class="text-sm">
                        <a href="#" class="font-medium text-secondary-600 hover:text-secondary-500">
                            Forgot your password?
                        </a>
                    </div>
                </div>

                <div>
                    <button type="submit"
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white btn-primary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Sign in
                        <span class="absolute right-0 inset-y-0 flex items-center pr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#FFD700]" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </span>
                    </button>
                </div>
            </form>

            
        </div>
    </div>

@endsection