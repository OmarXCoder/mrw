<?php
namespace App\Actions;

use App\Contracts\Action;
use App\Models\Attendee;
use Illuminate\Support\Facades\Validator;

class CreateAttendee extends Action
{
    public function create(array $input)
    {
        Validator::make($input, [
            'badge_id' => ['required'],
            'first_name' => ['required'],
            'last_name' => ['required'],
            'email' => ['required', 'email', 'unique:attendees'],
            'show_id' => [
                'required',
                fn ($attribute, $value, $fail) => is_int($value) ?: $fail('The ' . $attribute . ' must be an integer.'),
                'exists:shows,id',
            ],
        ])->validate();

        $this->input = $input;

        $attendee = Attendee::create([
            'badge_id' => $this->getInput('badge_id'),
            'first_name' => $this->getInput('first_name'),
            'last_name' => $this->getInput('last_name'),
            'email' => $this->getInput('email'),
            'job_title' => $this->getInput('job_title'),
            'company' => $this->getInput('company'),
            'profession' => $this->getInput('profession'),
            'phone' => $this->getInput('phone'),
            'address_line_1' => $this->getInput('address_line_1'),
            'address_line_2' => $this->getInput('address_line_2'),
            'city' => $this->getInput('city'),
            'state' => $this->getInput('state'),
            'country' => $this->getInput('country'),
            'postal_code' => $this->getInput('postal_code'),
            'meta' => $this->getInput('meta'),
            'notes' => $this->getInput('notes'),
            'show_id' => $this->getInput('show_id'),
        ]);

        return $attendee;
    }
}
