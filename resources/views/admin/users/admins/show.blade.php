@extends('layouts.app-layout')
@section('main-content')

@php
    $canEdit = auth()->user()->hasAnyRole(['super_admin', 'admin_editor']);
@endphp

<div class="flex-1 md:ml-64 p-6 pt-20">
    <div class="container mx-auto py-6 px-4 max-w-8xl">

        <!-- Breadcrumb -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.admins') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-green-600">
                        <i class="fas fa-home mr-2"></i>
                        Administrators
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
                        <span class="text-sm font-medium text-gray-500">Admin Details</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Reactive Admin Details -->
        @livewire('admin.admins.admin-show-details', ['adminId' => $id])

        {{-- Edit Admin Modal --}}
        @if($canEdit)
            @livewire('admin.admins.edit-admin-modal')
        @endif
    </div>
</div>

<x-footer.footer></x-footer.footer>
@endsection
