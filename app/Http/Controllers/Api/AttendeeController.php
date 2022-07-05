<?php
namespace App\Http\Controllers\Api;

use App\Actions\CreateAttendee;
use App\Actions\UpdateAttendee;
use App\Http\Controllers\Controller;
use App\Http\Resources\AttendeeResource;
use App\Models\Attendee;
use Illuminate\Http\Request;

class AttendeeController extends Controller
{
    public function store(Request $request)
    {
        $attendee = Attendee::where('badge_id', $request->input('badge_id'))
            ->where('show_id', $request->input('show_id'))
            ->first();

        if (!$attendee) {
            $attendee = (new CreateAttendee)->create($request->all());
        } else {
            $attendee = (new UpdateAttendee)->update($attendee, $request->all());
        }

        return AttendeeResource::make($attendee);
    }

    public function show(Attendee $attendee)
    {
        return AttendeeResource::make($attendee);
    }

    public function update(Request $request, Attendee $attendee, UpdateAttendee $updater)
    {
        $attendee = $updater->update($attendee, $request->all());

        return AttendeeResource::make($attendee);
    }
}
