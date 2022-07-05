<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::paginate();

        return EventResource::collection($events);
    }

    public function store(Request $request)
    {
        $request->validate([
            'app_id' => ['required', 'exists:apps,id'],
            'action_code' => [
                'required',
                fn ($attribute, $value, $fail) => is_int($value) ?: $fail('The ' . $attribute . ' must be an integer.'),
                'exists:action_types,code',
            ],
            'event_code' => [
                'required',
                fn ($attribute, $value, $fail) => is_int($value) ?: $fail('The ' . $attribute . ' must be an integer.'),
                'exists:event_types,code',
            ],
            'timestamp' => ['required', 'date'],
        ]);

        $event = Event::create([
            'action_code' => $request->get('action_code'),
            'event_code' => $request->get('event_code'),
            'app_id' => $request->get('app_id'),
            'attendee_id' => $request->get('attendee_id'),
            'timestamp' => $request->get('timestamp'),
            'meta' => $request->get('meta'),
        ]);

        return EventResource::make($event);
    }
}
