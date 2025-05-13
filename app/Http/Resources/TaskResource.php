<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'status' => $this->status,
            'description' => $this->description,
            'complexity' => $this->complexity,
            'project' => $this->project,
            'team' => $this->team,
            'creator' => new UserResource($this->whenLoaded('creator')),
            'performer' => new UserResource($this->whenLoaded('performer')),
            'contributor' => new UserResource($this->whenLoaded('contributor')),
            'files' => TaskFileResource::collection($this->whenLoaded('files')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user_voted' => $this->votes()->get()->pluck('user_id')->contains(auth()->user()->id),
        ];
    }
}
