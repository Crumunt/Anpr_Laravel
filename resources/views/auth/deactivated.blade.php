@extends('layouts.app-layout')

@section('title', 'Account Deactivated')

@section('hide-navbar', true)

@section('body-class', 'bg-gray-50')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

    :root {
        --clsu-green: #006300;
        --clsu-light-green: #068406;
        --clsu-dark-green: #004d00;
    }

    body {
        font-family: 'Poppins', sans-serif;
    }
</style>
@endpush

@section('main-content')
<div class="min-h-screen flex items-center justify-center px-4 py-12">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-[var(--clsu-dark-green)] to-[var(--clsu-green)] px-8 py-6 text-center">
            <div class="mx-auto w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
            </div>
            <h1 class="text-xl font-bold text-white">Account Deactivated</h1>
        </div>

        <!-- Body -->
        <div class="px-8 py-8 text-center">
            <p class="text-gray-700 text-sm leading-relaxed mb-6">
                Sorry, your account has been deactivated.
                Please contact the <span class="font-semibold text-gray-900">UBAP personnel</span>
                to settle and reactivate your account.
            </p>

            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm text-amber-800 text-left">
                        If you believe this was done in error, please reach out to the University administration for assistance.
                    </p>
                </div>
            </div>

            <a href="{{ route('login') }}"
               class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-[var(--clsu-green)] to-[var(--clsu-light-green)] text-white text-sm font-semibold rounded-xl hover:opacity-90 transition-opacity shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Login
            </a>
        </div>
    </div>
</div>
@endsection
