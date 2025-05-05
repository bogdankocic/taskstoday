<?php

namespace App\Http\Resources;

use App\Enums\KarmaCategoriesEnum;
use App\Enums\RolesEnum;
use App\Enums\TeamRolesEnum;
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
                'currentLevel' => KarmaCategoriesEnum::fromScore($this->karma)->value,
                'currentLevelNumber' => KarmaCategoriesEnum::fromScore($this->karma)->currentLevelNumber(),
                'required' => KarmaCategoriesEnum::tillNext($this->karma),
            ],
            'tasks_completed_count' => $this->tasks_completed_count,
            'login_strike' => $this->login_strike,
            'login_after_hours_count' => $this->login_after_hours_count,
            'is_verified' => $this->is_verified,
            'role_id' => $this->role_id,
            'teamrole' => $this->teamrole,
            'profile_photo' => $this->profile_photo ? asset($this->profile_photo) : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'organization' => new OrganizationResource($this->whenLoaded('organization')),
            'projects' => ProjectResource::collection($this->allProjects()),
            'teams' => TeamResource::collection($this->whenLoaded('teams')),
            'tasks' => TaskResource::collection($this->whenLoaded('tasks')),
            'achievements' => AchievementResource::collection($this->whenLoaded('achievements')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'cached_filter' => Cache::get("task-filter-{$this->id}", ''),
            'permissions' => [
                'can_invite_user' => in_array($this->teamrole, [TeamRolesEnum::ADMIN->value, TeamRolesEnum::MODERATOR->value]) || $this->role->name === RolesEnum::ADMIN->value,
                'can_delete_user' => in_array($this->teamrole, [TeamRolesEnum::ADMIN->value, TeamRolesEnum::MODERATOR->value]),
                'can_create_organization' => $this->role->name === RolesEnum::ADMIN->value,
                'can_delete_organization' => $this->role->name === RolesEnum::ADMIN->value,
                'can_update_organization' => in_array($this->teamrole, [TeamRolesEnum::ADMIN->value, TeamRolesEnum::MODERATOR->value]),
                'can_create_project' => in_array($this->teamrole, [TeamRolesEnum::ADMIN->value, TeamRolesEnum::MODERATOR->value]),
                'can_update_project' => in_array($this->teamrole, [TeamRolesEnum::ADMIN->value, TeamRolesEnum::MODERATOR->value]),
                'can_delete_project' => in_array($this->teamrole, [TeamRolesEnum::ADMIN->value, TeamRolesEnum::MODERATOR->value]),
                'can_finish_project' => in_array($this->teamrole, [TeamRolesEnum::ADMIN->value, TeamRolesEnum::MODERATOR->value]),
                'can_create_team' => in_array($this->teamrole, [TeamRolesEnum::ADMIN->value, TeamRolesEnum::MODERATOR->value]),
                'can_update_team' => in_array($this->teamrole, [TeamRolesEnum::ADMIN->value, TeamRolesEnum::MODERATOR->value]),
                'can_delete_team' => in_array($this->teamrole, [TeamRolesEnum::ADMIN->value, TeamRolesEnum::MODERATOR->value]),
                'can_add_member' => in_array($this->teamrole, [TeamRolesEnum::ADMIN->value, TeamRolesEnum::MODERATOR->value]),
                'can_remove_member' => in_array($this->teamrole, [TeamRolesEnum::ADMIN->value, TeamRolesEnum::MODERATOR->value]),
                'can_create_task' => in_array($this->teamrole, [TeamRolesEnum::ADMIN->value, TeamRolesEnum::MODERATOR->value]),
                'can_update_task' => in_array($this->teamrole, [TeamRolesEnum::ADMIN->value, TeamRolesEnum::MODERATOR->value]),
                'can_delete_tasl' => in_array($this->teamrole, [TeamRolesEnum::ADMIN->value, TeamRolesEnum::MODERATOR->value]),
                'can_assign_performer' => in_array($this->teamrole, [TeamRolesEnum::ADMIN->value, TeamRolesEnum::MODERATOR->value]),
                'can_assign_contributor' => in_array($this->teamrole, [TeamRolesEnum::ADMIN->value, TeamRolesEnum::MODERATOR->value]),
                'can_activate_task' => in_array($this->teamrole, [TeamRolesEnum::ADMIN->value, TeamRolesEnum::MODERATOR->value]),
                'can_complete_task' => in_array($this->teamrole, [TeamRolesEnum::ADMIN->value, TeamRolesEnum::MODERATOR->value]),
                'can_vote_complexity' => in_array($this->teamrole, [TeamRolesEnum::ADMIN->value, TeamRolesEnum::MODERATOR->value]),
            ]
        ];
    }
}