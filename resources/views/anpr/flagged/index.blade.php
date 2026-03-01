@extends('layouts.anpr-layout')

@section('title', 'Flagged Vehicles')
@section('page-title', 'Flagged Vehicles')
@section('page-subtitle', 'Manage suspicious and flagged vehicles')

@section('content')
<div class="space-y-6 lg:space-y-8">
    <livewire:a-n-p-r.flagged-vehicles />
</div>
@endsection
