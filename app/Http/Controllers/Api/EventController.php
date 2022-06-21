<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'app_id' => ['required', 'exists:apps,id'],
            'show_id' => ['required', 'exists:shows,id'],
            'client_id' => ['required', 'exists:clients,id'],
            'action_code' => ['required', 'exists:action_types,code'],
            'event_code' => ['required', 'exists:event_types,code'],
            'timestamp' => ['required', 'date'],
        ]);

        $event = Event::create([
            'action_code' => $request->get('action_code'),
            'event_code' => $request->get('event_code'),
            'app_id' => $request->get('app_id'),
            'attendee_id' => $request->get('attendee_id'),
            'show_id' => $request->get('show_id'),
            'client_id' => $request->get('client_id'),
            'timestamp' => $request->get('timestamp'),
            'meta' => $request->get('meta'),
        ]);

        return EventResource::make($event);
    }
}
