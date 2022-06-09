<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendeeResource;
use App\Models\Attendee;
use Illuminate\Http\Request;

class AttendeeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'badge_id' => ['required'],
            'first_name' => ['required'],
            'last_name' => ['required'],
            'email' => ['required', 'email'],
            'client_id' => ['required', 'exists:clients,id'],
            'show_id' => ['required', 'exists:shows,id'],
        ]);

        if (!$attendee = Attendee::where('badge_id', $request->get('badge_id'))->first()) {
            $attendee = Attendee::create([
                'badge_id' => $request->get('badge_id'),
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
                'job_title' => $request->get('job_title'),
                'email' => $request->get('email'),
                'phone' => $request->get('phone'),
                'address_line_1' => $request->get('address_line_1'),
                'address_line_2' => $request->get('address_line_2'),
                'city' => $request->get('city'),
                'state' => $request->get('state'),
                'country' => $request->get('country'),
                'postal_code' => $request->get('postal_code'),
                'meta' => $request->get('meta'),
                'notes' => $request->get('notes'),
                'client_id' => $request->get('client_id'),
                'show_id' => $request->get('show_id'),
            ]);
        }

        return AttendeeResource::make($attendee);
    }

    public function show(Attendee $attendee)
    {
        return AttendeeResource::make($attendee);
    }
}
