<?php

namespace App\Http\Resources;

use App\Enums\KarmaCategoriesEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'karma' => [
                'current' => $this->karma,
                'required' => KarmaCategoriesEnum::tillNext($this->karma),
            ],
            'tasks_completed_count' => $this->tasks_completed_count,
            'login_strike' => $this->login_strike,
            'login_after_hours_count' => $this->login_after_hours_count,
            'is_verified' => $this->is_verified,
            'role_id' => $this->role_id,
            'teamrole' => $this->teamrole,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'projects' => ProjectResource::collection($this->allProjects()),
            'teams' => TeamResource::collection($this->whenLoaded('teams')),
            'tasks' => TaskResource::collection($this->whenLoaded('tasks')),
            'achievements' => AchievementResource::collection($this->whenLoaded('achievements')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'cached_filter' => Cache::get("task-filter-{$this->id}", ''),
        ];
    }
}