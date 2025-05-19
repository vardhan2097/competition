@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Generate Spending Report</h3>
    <form action="{{ route('organization.generate.spendReport') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="time_range" class="form-label">Select Time Range</label>
            <select name="time_range" id="time_range" class="form-control" required>
                <option value="week">Last Week</option>
                <option value="month">Last Month</option>
                <option value="quarter">Last Quarter</option>
                <option value="6months">Last 6 Months</option>
                <option value="year">Last Year</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Generate Report</button>
    </form>
</div>
@endsection
