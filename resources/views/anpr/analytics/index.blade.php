@extends('layouts.anpr-layout')

@section('title', 'Analytics')
@section('page-title', "Analytics & Reports")
@section('page-subtitle', 'Vehicle traffic insights and statistics')

@section('content')
<div class="space-y-6 lg:space-y-8">
    <livewire:a-n-p-r.analytics />
</div>
@endsection
