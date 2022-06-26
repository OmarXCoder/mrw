<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReportPageResource extends JsonResource
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
            'object_type' => 'ReportPage',
            'id' => $this->id,
            'type' => $this->type,
            'heading' => $this->heading,
            'content' => $this->content,
            'meta' => $this->meta,
            'report_id' => $this->report_id,
            'include_header' => $this->include_header,
            'include_footer' => $this->include_footer,
        ];
    }
}
