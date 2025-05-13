<?php

namespace App\Repositories;

use App\Http\Requests\TeamCreateRequest;
use App\Http\Requests\TeamUpdateNameRequest;
use App\Http\Resources\TeamResource;
use App\Http\Resources\UserResource;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TeamRepository extends BaseRepository
{
    public function __construct(Team $model)
    {
        parent::__construct($model);
    }
    
    public function create(TeamCreateRequest $request): TeamResource
    {
        $team = Team::create([
            'title' => $request->title,
            'project_id' => $request->project_id,
        ]);

        TeamMember::create([
            'team_id' => $team->id,
            'user_id' => $request->user()->id,
            'joined_at' => now(),
        ]);

        return new TeamResource($team);
    }

    public function updateName(Team $team, TeamUpdateNameRequest $request): void
    {
        $team->title = $request->title;
        $team->save();
    }

    public function delete(Team $team, Request $request): void
    {
        $team->delete();
    }

    public function getMembers(Team $team, Request $request): ResourceCollection
    {
        $members = $team->members()->with('tags')->get();
        $members->each(function ($user) {
            $user->setRelation('tags', $user->tags->unique(function ($tag) {
                return $tag->pivot->tag_id . '-' . $tag->pivot->project_id;
            })->values());
        });
        return UserResource::collection($members);
    }

    public function getOne(Team $team): TeamResource
    {
        return new TeamResource($team);
    }

    public function addMember(Team $team, User $user, Request $request): void
    {
        $currentUser = $request->user();

        if ($currentUser->organization_id !== $user->organization_id) {
            throw new HttpResponseException(response()->json([
                'message' => 'Cannot add member.',
            ], 400));
        }

        TeamMember::create([
            'team_id' => $team->id,
            'user_id' => $user->id,
            'joined_at' => now(),
        ]);
    }

    public function removeMember(Team $team, User $user, Request $request): void
    {
        TeamMember::where('team_id', $team->id)
            ->where('user_id', $user->id)
            ->delete();
    }

    public function getOneModel(Request $request, int $id): Team
    {
        return Team::find($id);
    }
}