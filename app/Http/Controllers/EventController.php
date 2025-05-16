<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;
use App\Models\Event;
use App\Models\EventHistory;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function store(Request $request)
    {
        // dd($request->all());
        $validated = request()->validate([
            'date_of_event' => ['required', 'date', ],
            'event_name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            // 'address_location' => ['required'],
            // 'is_adv_paid' => ['required', 'boolean'],
            // 'adv_amt' => ['required'],
            // 'is_ret_paid' => ['required'],
            // 'ret_amt' => ['required'],
            // 'misc_spend' => ['required'],
            'contact_person_name' => ['required', 'string', 'max:255'],
            'contact_person_phone' => ['required', 'string', 'max:10',],
        ]);

        try
        {
            DB::beginTransaction();

            $validated['org_id'] = Auth::guard('web')->user()->org_id;
            $validated['added_by'] = Auth::guard('web')->user()->id;
            $validated['updated_by'] = Auth::guard('web')->user()->id;

            if(isset($request->is_adv_paid) && $request->is_adv_paid == true)
            {
                $validated['is_adv_paid'] = $request->is_adv_paid;
                $validated['adv_amt'] = $request->adv_amt;
            }
            else
            {
                $validated['is_adv_paid'] = false;
                $validated['adv_amt'] = 0;
            }

            if(isset($request->is_ret_paid) && $request->is_ret_paid == true)
            {
                $validated['is_ret_paid'] = $request->is_ret_paid;
                $validated['ret_amt'] = $request->ret_amt;
            }
            else
            {
                $validated['is_ret_paid'] = false;
                $validated['ret_amt'] = 0;
            }

            if ($request->has('misc_spend'))
            {
                $miscSpendArray = json_decode($request->input('misc_spend'), true);
                $validated['misc_spend'] = json_encode($miscSpendArray); // optional: validate keys/values
            }

            $event = Event::create($validated);

            $eventHistory = EventHistory::create([
                'event_id' => $event->id,
                'changes' => $event->toArray(),
                'event_created_at' => now(),
                'updated_by' => Auth::guard('web')->user()->id,
                'action' => 'create',
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Event added successfully']);
        }
        catch(Exception $e)
        {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to add event',
                'error' => $e->getMessage(),
                ], 500);
        }
    }

    public function calendarEvents()
    {
        $events = Event::where('org_id', Auth::guard('web')->user()->org_id)->orderBy('date_of_event', 'desc')->get();

        return $events->map(function ($event) {
            return [
                'id' => $event->id,
                'event_name' => $event->event_name,
                'start' => $event->date_of_event,
                'address' => $event->address,
                'address_location' => $event->address_location,
                'contact_person_name' => $event->contact_person_name,
                'contact_person_phone' => $event->contact_person_phone,
                'is_adv_paid' => $event->is_adv_paid,
                'adv_amt' => $event->adv_amt,
                'is_ret_paid' => $event->is_ret_paid,
                'ret_amt' => $event->ret_amt,
                'misc_spend' => $event->misc_spend
            ];
        });
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        if(!isset($request->is_adv_paid))
            $event->is_adv_paid = false;

        if(!isset($request->is_ret_paid))
            $event->is_ret_paid = false;

        $event->event_name = $request->event_name;
        $event->date_of_event = $request->date_of_event;
        $event->address = $request->address;
        $event->address_location = $request->address_location;
        $event->adv_amt = $request->adv_amt;
        $event->ret_amt = $request->ret_amt;
        $event->contact_person_name = $request->contact_person_name;
        $event->contact_person_phone = $request->contact_person_phone;
        $event->misc_spend = $request->misc_spend;
        $event->updated_by = $request->updated_by;

        $event->save();

        return response()->json(['status' => 'success']);
    }

}
