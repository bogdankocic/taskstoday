<?php

namespace App\Repositories;

use App\Enums\ProjectStatusesEnum;
use App\Enums\TeamRolesEnum;
use App\Http\Requests\ProjectCreateRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Models\Team;
use App\Models\TeamMember;
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

    public function delete(int $id, Request $request): void
    {
        Project::find($id)->delete();
    }

    public function update(int $id, ProjectUpdateRequest $request): ProjectResource
    {
        $project = Project::find($id);

        $project->title = $request->title;
        $project->description = $request->description;

        $project->save();

        return new ProjectResource($project);
    }

    public function get(Request $request): ResourceCollection
    {
        $projects = collect();
        $user = $request->user();

        if($user->teamrole === TeamRolesEnum::ADMIN->value) {
            $projects = Project::where('organization_id', $user->organization_id)->get();
        } else {
            $projects = Project::whereIn('id', function ($query) use ($user) {
                $query->select('teams.project_id')
                    ->from('teams')
                    ->join('team_member', 'teams.id', '=', 'team_member.team_id')
                    ->where('team_member.user_id', $user->id);
            })->get();
        }

        return ProjectResource::collection($projects);
    }

    public function finish(int $id, Request $request): ProjectResource
    {
        $project = Project::find($id);

        $project->status = ProjectStatusesEnum::FINISHED->value;
        $project->save();

        return new ProjectResource($project);
    }
}