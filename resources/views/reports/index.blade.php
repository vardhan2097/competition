@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Select a Report</h2>
    <div class="row">
        <div class="col-md-4">
            <a href="{{ route('organization.reports.events') }}" class="card text-center p-4 shadow-sm">
                <h4>Events Report</h4>
                <p>View number of events per time period</p>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('organization.reports.spend') }}" class="card text-center p-4 shadow-sm">
                <h4>Spending Report</h4>
                <p>View money spent on events</p>
            </a>
        </div>
    </div>
</div>
@endsection
