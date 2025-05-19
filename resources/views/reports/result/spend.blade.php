@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <h3>Spending Report  ({{ ucfirst($range) }})</h3>
    <p>From <strong>{{ $startDate->toFormattedDateString() }}</strong> to <strong>{{ $endDate->toFormattedDateString() }}</strong></p>

    @if($events->isEmpty())
        <p>No events found for this range.</p>
    @else
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Title</th>
                    <th>Advance</th>
                    <th>Return</th>
                    <th>Misc Spend</th>
                </tr>
            </thead>
            <tbody>
                @foreach($events as $event)
                <tr>
                    <td>{{ $event->date_of_event }}</td>
                    <td>{{ $event->title }}</td>
                    <td>{{ $event->adv_amt }}</td>
                    <td>{{ $event->ret_amt }}</td>
                    <td>{{ $event->misc_spend }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            <h5>Total Advance: ₹{{ number_format($totalAdvance, 2) }}</h5>
            <h5>Total Return: ₹{{ number_format($totalReturn, 2) }}</h5>
            <h5>Total Misc: ₹{{ number_format($totalMisc, 2) }}</h5>
            <h4>Total Spent: ₹{{ number_format($totalSpent, 2) }}</h4>
        </div>

        <form action="{{ route('organization.download.spendReport') }}" method="POST" class="mt-3 d-flex gap-2">
            @csrf
            <input type="hidden" name="time_range" value="{{ $range }}">
            <button type="submit" name="format" value="pdf" class="btn btn-danger">Download PDF</button>
            <button type="submit" name="format" value="csv" class="btn btn-success">Download CSV</button>
        </form>
    @endif
</div>
@endsection
