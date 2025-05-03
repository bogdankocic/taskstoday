<?php

namespace App\Repositories;

use App\Enums\ProjectStatusesEnum;
use App\Enums\TeamRolesEnum;
use App\Http\Requests\ProjectCreateRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\TeamResource;
use App\Http\Resources\UserResource;
use App\Models\Project;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProjectRepository extends BaseRepository
{
    public function __construct(Project $model)
    {
        parent::__construct($model);
    }

    public function create(ProjectCreateRequest $request): ProjectResource
    {
        $project = Project::create([
            'title' => $request->title,
            'status' => ProjectStatusesEnum::INPROGRESS->value,
            'description' => $request->description,
            'organization_id' => $request->user()->organization_id,
        ]);

        $team = Team::create([
            'title' => $request->team_title ? $request->team_title : $request->title . ' Team',
            'project_id' => $project->id,
        ]);

        TeamMember::create([
            'team_id' => $team->id,
            'user_id' => $request->user()->id,
            'joined_at' => now(),
        ]);

        return new ProjectResource($project);
    }

    public function delete(Project $project, Request $request): void
    {
        $project->delete();
    }

    public function update(Project $project, ProjectUpdateRequest $request): ProjectResource
    {
        $project->title = $request->input('title');
        $project->description = $request->input('description');

        $project->save();

        return new ProjectResource($project);
    }

    public function get(Request $request): ResourceCollection
    {
        $projects = collect();
        $user = $request->user();

        if($user->teamrole === TeamRolesEnum::ADMIN->value) {
            $projects = Project::with('teams.members')->where('organization_id', $user->organization_id)->get();
        } else {
            $projects = Project::with('teams.members')->whereIn('id', function ($query) use ($user) {
                $query->select('teams.project_id')
                    ->from('teams')
                    ->join('team_member', 'teams.id', '=', 'team_member.team_id')
                    ->where('team_member.user_id', $user->id);
            })->get();
        }

        return ProjectResource::collection($projects);
    }

    public function getMembers(Project $project): ResourceCollection
    {
        $members = User::where('organization_id', $project->organization_id)->get();
        return UserResource::collection($members);
    }

    public function getTeams(Project $project): ResourceCollection
    {
        return TeamResource::collection($project->teams);
    }

    public function finish(Project $project, Request $request): ProjectResource
    {
        $project->status = ProjectStatusesEnum::FINISHED->value;
        $project->save();

        return new ProjectResource($project);
    }
}
