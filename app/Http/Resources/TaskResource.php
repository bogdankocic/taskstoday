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
            'creator' => $this->creator,
            'performer' => $this->performer,
            'contributor' => $this->contributor,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}