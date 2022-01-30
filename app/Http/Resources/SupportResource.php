<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class SupportResource extends JsonResource
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
            'id' => $this->id,
            'status' => $this->status,
            'status_lable' => $this->statusOptions[$this->status] ?? 'Status Not Found',
            'description' => $this->description,
            'user' => new UserResource($this->user),
            'lesson' => new LessonResource($this->whenLoaded('lesson')),
            'replies' => ReplySupportResource::collection($this->whenLoaded('replies')),
            'dt_updated' => Carbon::make($this->updated_at)->format('Y-m-d H:i:s')
        ];
    }
}
