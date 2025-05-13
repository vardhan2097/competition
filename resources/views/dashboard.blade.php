@extends('layouts.app')

@section('content')
<div class="container">
    <h2>{{ Auth::user()->organization->name }}</h2>
    <p>Welcome, {{ Auth::user()->name ?? Auth::user()->email }}!</p>

    <div class="row">

        {{-- Manage Organization Users --}}
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h5 class="card-title">Manage Organization Users</h5>
                    <p class="card-text">View and manage users from your organization.</p>
                    <a href="{{ route('organization.users.index') }}" class="btn btn-primary">Manage</a>
                </div>
            </div>
        </div>

        {{-- Manage Organization Details --}}
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h5 class="card-title">Organization Details</h5>
                    <p class="card-text">Update Organizatio's Profile & Settings</p>
                    {{-- <a href="{{ route('organization.details.edit')}}"></a> --}}
                </div>
            </div>
        </div>

        {{-- Generate Reports --}}
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h5 class="card-title">Generate Reports</h5>
                    <p class="card-text">Generate and view Performance and Activity Report</p>
                    {{-- <a href="{{ route('organization.reports.index') }}" class="btn btn-primary">Generate</a> --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
