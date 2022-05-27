<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
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
            'object_type' => 'Event',
            'id' => $this->id,
            'action_code' => $this->action_code,
            'event_code' => $this->event_code,
            'timestamp' => $this->timestamp,
            'app_id' => $this->app_id,
            'show_id' => $this->show_id,
            'attendee_id' => $this->attendee_id,
            'data' => json_decode($this->data),
        ];
    }
}
