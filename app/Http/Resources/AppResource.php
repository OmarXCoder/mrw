<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AppResource extends JsonResource
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
            'object_type' => 'App',
            'id' => $this->id,
            'name' => $this->name,
            'client_id' => (int) $this->client_id,
            'show_id' => (int) $this->show_id,
            'kiosk_id' => $this->kiosk_id,
            'machine_id' => $this->machine_id,
        ];
    }
}
