@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Events Report ({{ ucfirst($range) }})</h3>
    <p>From <strong>{{ $startDate->toFormattedDateString() }}</strong> to <strong>{{ $endDate->toFormattedDateString() }}</strong></p>

    @if($events->isEmpty())
        <p>No events found for this range.</p>
    @else
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Event Name</th>
                    <th>Address</th>
                    <th>Advance</th>
                    <th>Return</th>
                    <th>Misc Spend</th>
                </tr>
            </thead>
            <tbody>
                @foreach($events as $event)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($event->date_of_event)->format('d/m/Y') }}</td>
                    <td>{{ $event->event_name }}</td>
                    <td>{{ $event->address }}</td>
                    <td>{{ $event->adv_amt }}</td>
                    <td>{{ $event->ret_amt }}</td>
                    @php
                        $miscTotal = 0;
                        if (is_string($event->misc_spend))
                        {
                            $miscItems = json_decode($event->misc_spend, true);
                            if (is_array($miscItems))
                                $miscTotal = array_sum($miscItems);
                        }
                    @endphp
                    <td>{{ $miscTotal }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <form action="{{ route('organization.download.eventsReport') }}" method="POST" class="mt-3 d-flex gap-2">
            @csrf
            <input type="hidden" name="time_range" value="{{ $range }}">
            <button type="submit" name="format" value="pdf" class="btn btn-danger">Download PDF</button>
            <button type="submit" name="format" value="csv" class="btn btn-success">Download CSV</button>
        </form>
    @endif
</div>
@endsection
