<?php
namespace App\Actions;

use App\Contracts\Action;
use App\Models\Attendee;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UpdateAttendee extends Action
{
    public function update(Attendee $attendee, array $input)
    {
        Validator::make($input, [
            'email' => ['nullable', 'email', Rule::unique('attendees', 'email')->ignore($attendee->id)],
        ])->validate();

        $this->input = $input;

        $attendee->update([
            'first_name' => $this->getInput('first_name', $attendee->first_name),
            'last_name' => $this->getInput('last_name', $attendee->last_name),
            'email' => $this->getInput('email', $attendee->email),
            'job_title' => $this->getInput('job_title', $attendee->job_title),
            'company' => $this->getInput('company', $attendee->company),
            'profession' => $this->getInput('profession', $attendee->profession),
            'phone' => $this->getInput('phone', $attendee->phone),
            'address_line_1' => $this->getInput('address_line_1', $attendee->address_line_1),
            'address_line_2' => $this->getInput('address_line_2', $attendee->address_line_2),
            'city' => $this->getInput('city', $attendee->city),
            'state' => $this->getInput('state', $attendee->state),
            'country' => $this->getInput('country', $attendee->country),
            'postal_code' => $this->getInput('postal_code', $attendee->postal_code),
            'meta' => $this->getInput('meta', $attendee->meta),
            'notes' => $this->getInput('notes', $attendee->notes),
        ]);

        return $attendee;
    }
}
