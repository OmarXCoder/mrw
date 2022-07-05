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
            'action_code' => (int) $this->action_code,
            'event_code' => (int) $this->event_code,
            'app_id' => (int) $this->app_id,
            'show_id' => (int) $this->show_id,
            'attendee_id' => (int) $this->attendee_id,
            'timestamp' => $this->timestamp,
            'meta' => $this->meta,
        ];
    }
}
