<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\Organization;
// use Barryvdh\DomPDF\PDF;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

class ReportsController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function buildReport($type)
    {
        return view('reports.' . $type);
    }

    public function eventsReportForm()
    {
        return view('reports.events'); // or wherever your events form view is
    }

    public function spendReportForm()
    {
        return view('reports.spend'); // or wherever your spend form view is
    }

    public function generateEventsReport(Request $request)
    {
        try
        {
            $orgId = Auth::user()->org_id;

            $organization = Organization::where('id', $orgId)->where('is_active', true)->first();
            if($organization == null)
                throw new \Exception('Organization Either not active or Doesnt exist');

            // Determine date range
            $endDate = now();
            switch ($request->time_range)
            {
                case 'week': $startDate = now()->subWeek(); break;
                case 'month': $startDate = now()->subMonth(); break;
                case 'quarter': $startDate = now()->subMonths(3); break;
                case '6months': $startDate = now()->subMonths(6); break;
                case 'year': $startDate = now()->subYear(); break;
                default: $startDate = now()->subWeek(); break;
            }

            $events = Event::where('org_id', $orgId)
                        ->whereBetween('date_of_event', [$startDate, $endDate])
                        ->get();

            $range = $request->time_range;
            return view('reports.result.events', compact('events', 'startDate', 'endDate', 'range'));
        }
        catch(\Exception $e)
        {
            return back()->with('error', $e->getMessage());
        }

    }

    public function generateSpendReport(Request $request)
    {
        try
        {
            $orgId = Auth::user()->org_id;

            $organization = Organization::where('id', $orgId)->where('is_active', true)->first();

            if($organization == null)
                throw new \Exception('Organization Either not active or Doesnt exist');

            $endDate = now();
            switch ($request->time_range) {
                case 'week': $startDate = now()->subWeek(); break;
                case 'month': $startDate = now()->subMonth(); break;
                case 'quarter': $startDate = now()->subMonths(3); break;
                case '6months': $startDate = now()->subMonths(6); break;
                case 'year': $startDate = now()->subYear(); break;
                default: $startDate = now()->subMonth(); break;
            }

            $events = Event::where('org_id', $orgId)
                        ->whereBetween('date_of_event', [$startDate, $endDate])
                        ->get();

            // Calculate total spending
            $totalAdvance = $events->sum('adv_amt');
            $totalReturn  = $events->sum('ret_amt');
            $totalMisc = 0;

            foreach ($events as $event)
            {
                $miscItems = is_string($event->misc_spend) ? json_decode($event->misc_spend, true) : [];

                if (is_array($miscItems))
                    $totalMisc += array_sum($miscItems);

            }
            $totalSpent = $totalAdvance - $totalReturn + $totalMisc;

            return view('reports.result.spend', compact('events', 'startDate', 'endDate', 'totalAdvance', 'totalReturn', 'totalMisc', 'totalSpent'));
        }
        catch(\Exception $e)
        {
            return back()->with('error', $e->getMessage());
        }
    }


    public function downloadEventsReport(Request $request)
    {
        $range = $request->input('time_range');
        $format = $request->input('format');
        $user = auth()->user();

        [$startDate, $endDate] = $this->getDateRange($range);

        $events = Event::where('org_id', $user->organization_id)
            ->whereBetween('date_of_event', [$startDate, $endDate])
            ->get();


        if ($format === 'pdf') {
            $pdf = PDF::loadView('reports.result.download.events_pdf', compact('events', 'range', 'startDate', 'endDate'));
            return $pdf->download("events_report_{$range}.pdf");
        } elseif ($format === 'csv') {
            $csvContent = $this->buildCsv($events, ['Date', 'Title', 'Advance', 'Return', 'Misc']);
            return Response::streamDownload(function () use ($csvContent) {
                echo $csvContent;
            }, "events_report_{$range}.csv");
        }

        return redirect()->back()->with('error', 'Invalid format selected.');
    }


    public function downloadSpendReport(Request $request)
    {
        $range = $request->input('time_range');
        $format = $request->input('format');
        $user = auth()->user();

        [$startDate, $endDate] = $this->getDateRange($range);

        $events = Event::where('org_id', $user->organization_id)
            ->whereBetween('date_of_event', [$startDate, $endDate])
            ->get();

        $totalMisc = 0;
        foreach ($events as $e)
        {
            $miscItems = is_string($e->misc_spend) ? json_decode($e->misc_spend, true) : [];
            if (is_array($miscItems)) {
                $totalMisc += array_sum($miscItems);
            }
            // Attach misc_total for each event (optional, useful for the view)
            $e->misc_total = is_array($miscItems) ? array_sum($miscItems) : 0;
        }

        $totals = [
            'advance' => $events->sum('adv_amt'),
            'return' => $events->sum('ret_amt'),
            'misc' => $totalMisc,
            'totalSpent' => $totalMisc->sum(function ($e)
            {
                return $e->adv_amt + $e->misc_spend - $e->ret_amt;
            }),
        ];

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('reports.result.download.spend_pdf', compact('events', 'totals', 'range', 'startDate', 'endDate'));
            return $pdf->download("spend_report_{$range}.pdf");
        } elseif ($format === 'csv') {
            $csvContent = $this->buildCsv($events, ['Date', 'Title', 'Advance', 'Return', 'Misc'], $totals);
            return Response::streamDownload(function () use ($csvContent) {
                echo $csvContent;
            }, "spend_report_{$range}.csv");
        }

        return redirect()->back()->with('error', 'Invalid format selected.');
    }


    private function buildCsv($events, $headers, $totals = null)
    {
        $csv = implode(',', $headers) . "\n";

        foreach ($events as $event) {
            $csv .= implode(',', [
                $event->date_of_event,
                $event->title,
                $event->adv_amt,
                $event->ret_amt,
                $event->misc_spend
            ]) . "\n";
        }

        if ($totals) {
            $csv .= "\nTotal Advance,{$totals['advance']}\n";
            $csv .= "Total Return,{$totals['return']}\n";
            $csv .= "Total Misc,{$totals['misc']}\n";
            $csv .= "Total Spent,{$totals['totalSpent']}\n";
        }

        return $csv;
    }

    private function getDateRange($range)
    {
        $today = Carbon::today();

        return match ($range) {
            'week' => [$today->copy()->startOfWeek(), $today],
            'month' => [$today->copy()->startOfMonth(), $today],
            'quarter' => [$today->copy()->subMonths(3)->startOfMonth(), $today],
            '6months' => [$today->copy()->subMonths(6)->startOfMonth(), $today],
            'year' => [$today->copy()->startOfYear(), $today],
            default => [$today->copy()->startOfMonth(), $today],
        };
    }


}
