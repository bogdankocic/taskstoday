<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'karma' => $this->karma,
            'tasks_completed_count' => $this->tasks_completed_count,
            'login_strike' => $this->login_strike,
            'login_after_hours_count' => $this->login_after_hours_count,
            'is_verified' => $this->is_verified,
            'role_id' => $this->role_id,
            'teamrole' => $this->teamrole,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}