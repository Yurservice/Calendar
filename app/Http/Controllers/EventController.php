<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    public function index() 
	{	
        $events = Event::select('eventtype_id', 'day')
        ->groupBy('eventtype_id', 'day')
        ->get();
        
        return $events;
    }

    public function getAllInDay(Request $request) 
	{	
        $events = Event::where('day', $request->day)
        ->join('event_types', 'event_types.id', '=', 'eventtype_id')
        ->select('events.*', 'event_types.title')
        ->get();

        return $events;
    }

    public function store(Request $request)
	{	
        $request->validate([
            'type' => ['required','numeric'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
            'location' => ['required', 'string', 'max:255'],
            'date' => ['required', 'string', 'max:20'],
			'time' => ['required', 'string', 'max:20'],
        ]);

        $event = new Event();
        $event->eventtype_id = $request->type;
        $event->name = $request->name;
        $event->description = $request->description;
        $event->location = $request->location;
        $event->day = $request->date;
		$event->time = $request->time;
        if($event->save()) return true;
        else return false;
    }

    public function show(Request $request)
	{	
        $event = Event::find($request->id);
        return $event;
    }

    public function update(Request $request)
	{	
        $request->validate([
            'type' => ['required','numeric'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
            'location' => ['required', 'string', 'max:255'],
            'date' => ['string', 'max:20'],
			'time' => ['required', 'string', 'max:20'],
            'event_id' => ['required', 'numeric'],
        ]);

        $event = Event::find($request->event_id);
        if($event) {
            $event->eventtype_id = $request->type;
            $event->name = $request->name;
            $event->description = $request->description;
            $event->location = $request->location;
            $event->time = $request->time;
            if($event->save()) return true;
            else return false;
        }
        else return false;
    }

    public function destroy(Request $request)
	{	
        $request->validate([
            'type' => ['required','numeric'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
            'location' => ['required', 'string', 'max:255'],
            'date' => ['string', 'max:20'],
			'time' => ['required', 'string', 'max:20'],
            'event_id' => ['required', 'numeric'],
        ]);

        $event = Event::find($request->event_id);
        if($event) {
            if($event->delete()) {
                return Event::where('day', $event->day)->where('day', $event->type)->count();
            }
            else return false;
        }
        else return false;
    }
}
