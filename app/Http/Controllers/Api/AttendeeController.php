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
        ]);

        if (!$attendee = Attendee::where('badge_id', $request->get('badge_id'))->first()) {
            $attendee = Attendee::create([
                'badge_id' => $request->get('badge_id'),
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
                'job_title' => $request->get('job_title'),
                'email' => $request->get('email'),
                'phone' => $request->get('phone'),
                'address_1' => $request->get('address_1'),
                'address_2' => $request->get('address_2'),
                'city' => $request->get('city'),
                'state' => $request->get('state'),
                'country' => $request->get('country'),
                'zip_code' => $request->get('zip_code'),
                'meta' => $request->get('meta'),
                'notes' => $request->get('notes'),
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
