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
            'show_id' => ['required', 'exists:shows,id'],
        ]);

        $attendee = Attendee::where('badge_id', $request->get('badge_id'))
            ->where('show_id', $request->get('show_id'))
            ->first();

        $input = [
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'email' => $request->get('email'),
            'job_title' => $request->get('job_title'),
            'company' => $request->get('company'),
            'profession' => $request->get('profession'),
            'phone' => $request->get('phone'),
            'address_line_1' => $request->get('address_line_1'),
            'address_line_2' => $request->get('address_line_2'),
            'city' => $request->get('city'),
            'state' => $request->get('state'),
            'country' => $request->get('country'),
            'postal_code' => $request->get('postal_code'),
            'meta' => $request->get('meta'),
            'notes' => $request->get('notes'),
        ];

        if (!$attendee) {
            $attendee = Attendee::create(
                array_merge($input, [
                    'badge_id' => $request->get('badge_id'),
                    'show_id' => $request->get('show_id'),
                ])
            );
        } else {
            $attendee->update($input);
        }

        return AttendeeResource::make($attendee);
    }

    public function show(Attendee $attendee)
    {
        return AttendeeResource::make($attendee);
    }
}
