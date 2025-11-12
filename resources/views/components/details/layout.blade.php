@props([
    'title' => 'Detail View',
    'type' => 'applicant',
    'id' => '1'
])

@extends('layouts.app-layout')

@section('main-content')

<div class="flex-1 md:ml-64 p-6 pt-20">

  <div class="container mx-auto py-6 px-4 max-w-8xl">
      {{ $breadcrumb ?? '' }}
      {{ $header ?? '' }}
      
      <!-- Main Content -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Main Information -->
        <div class="lg:col-span-2 space-y-6">
          {{ $mainContent ?? '' }}
        </div>
        
        <!-- Right Column - Status, Documents, Activity -->
        <div class="space-y-6">
          {{ $sideContent ?? '' }}
        </div>
      </div>
  </div>
</div>
  
  {{ $modals ?? '' }}
@endsection