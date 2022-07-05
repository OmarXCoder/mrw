<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AttendeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'object_type' => 'Attendee',
            'id' => $this->id,
            'badge_id' => $this->badge_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'job_title' => $this->job_title,
            'company' => $this->company,
            'profession' => $this->profession,
            'phone' => $this->phone,
            'address_line_1' => $this->address_line_1,
            'address_line_2' => $this->address_line_2,
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->country,
            'postal_code' => $this->postal_code,
            'meta' => $this->meta,
            'notes' => $this->notes,
            'show_id' => (int) $this->show_id,
            'client_id' => (int) $this->client_id,
        ];
    }
}
