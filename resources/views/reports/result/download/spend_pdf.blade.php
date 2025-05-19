<!DOCTYPE html>
<html>
<head>
    <title>Spending Report</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 5px; text-align: left; }
    </style>
</head>
<body>
    <h2>{{ Auth::user()->organization->name }}</h2>
    <h3>Spending Report ({{ ucfirst($range) }})</h3>
    <p>From {{ $startDate->toDateString() }} to {{ $endDate->toDateString() }}</p>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Title</th>
                <th>Advance</th>
                <th>Return</th>
                <th>Misc</th>
            </tr>
        </thead>
        <tbody>
            @foreach($events as $e)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($e->date_of_event)->format('d/m/Y') }}</td>
                    <td>{{ $e->title }}</td>
                    <td>{{ $e->adv_amt }}</td>
                    <td>{{ $e->ret_amt }}</td>
                    <td>{{ $e->misc_spend }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <br>
    <h4>Totals</h4>
    <p>Advance: ₹{{ $totals['advance'] }}</p>
    <p>Return: ₹{{ $totals['return'] }}</p>
    <p>Misc: ₹{{ $totals['misc'] }}</p>
    <p><strong>Total Spent: ₹{{ $totals['totalSpent'] }}</strong></p>
</body>
</html>
