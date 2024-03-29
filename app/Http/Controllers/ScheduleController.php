<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function index()
    {
        return view('schedule');
    }

    public function index_collector()
    {
        return view('collector-schedule');
    }

    public function add_schedule()
    {
        return view('add');
    }

    public function add_schedule_collector()
    {
        return view('add-collector');
    }

    public function create(Request $request)
    {
        $userId = Auth::user()->id;

        $schedule = new Schedule([
            'title' =>$request->title,
            'start' =>$request->start,
            'time' =>$request->time,
        ]);

        $user = User::find($userId);

        $user->schedules()->save($schedule);

        return redirect('schedule');

    }

    public function create_collector(Request $request)
    {
        $userId = Auth::user()->id;

        $schedule = new Schedule([
            'title' =>$request->title,
            'start' =>$request->start,
            'time' =>$request->time,
        ]);

        $user = User::find($userId);

        $user->schedules()->save($schedule);

        return redirect('collector-schedule');
    }


    public function getEvents()
    {
        $schedules = Schedule::all();
        return response()->json($schedules);
    }

    public function deleteEvent($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();

        return response()->json(['message' => 'Event deleted successfully']);
    }

    public function update(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);

        $schedule->update([
            'start' => Carbon::parse($request->input('start_date'))->setTimezone('UTC'),
            // 'end' => Carbon::parse($request->input('end_date'))->setTimezone('UTC'),
        ]);

        return response()->json(['message' => 'Event moved successfully']);
    }

    public function resize(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);

        $newEndDate = Carbon::parse($request->input('end_date'))->setTimezone('UTC');
        $schedule->update(['end' => $newEndDate]);

        return response()->json(['message' => 'Event resized successfully.']);
    }

    public function search(Request $request)
    {
        $searchKeywords = $request->input('title');

        $matchingEvents = Schedule::where('title', 'like', '%' . $searchKeywords . '%')->get();

        return response()->json($matchingEvents);
    }
}
