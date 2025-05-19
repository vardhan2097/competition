<!DOCTYPE html>
<html>
<head>
    <title>Events Report</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 5px; text-align: left; }
    </style>
</head>
<body>
    <h2>{{ Auth::user()->organization->name }}</h2>
    <h3>Events Report ({{ ucfirst($range) }})</h3>
    <p>From {{ $startDate->toDateString() }} to {{ $endDate->toDateString() }}</p>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Event Name</th>
                <th>Address</th>
                <th>Advance</th>
                <th>Return</th>
                <th>Misc</th>
            </tr>
        </thead>
        <tbody>
            @foreach($events as $e)
                @php
                    $miscTotal = 0;
                    try {
                        $misc = is_string($e->misc_spend) ? json_decode($e->misc_spend, true) : [];
                        $miscTotal = is_array($misc) ? array_sum($misc) : 0;
                    } catch (\Exception $ex) {
                        $miscTotal = 0;
                    }
                @endphp
                <tr>
                    <td>{{ \Carbon\Carbon::parse($e->date_of_event)->format('d/m/Y') }}</td>
                    <td>{{ $e->event_name }}</td>
                    <td>{{ $e->address }}</td>
                    <td>{{ $e->adv_amt }}</td>
                    <td>{{ $e->ret_amt }}</td>
                    <td>{{ $miscTotal }}</td>
                </tr>
            @endforeach

        </tbody>
    </table>
</body>
</html>
