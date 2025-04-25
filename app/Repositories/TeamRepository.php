<?php

namespace App\Repositories;

use App\Http\Requests\TeamCreateRequest;
use App\Http\Requests\TeamUpdateNameRequest;
use App\Http\Resources\TeamResource;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

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

    public function updateName(int $id, TeamUpdateNameRequest $request): void
    {
        $team = Team::find($id);
        $team->title = $request->title;
        $team->save();
    }

    public function delete(int $id, Request $request): void
    {
        Team::find($id)->delete();
    }

    public function addMember(int $teamId, int $userId, Request $request): void
    {
        $currentUser = $request->user();
        $user = User::find($userId);

        if ($currentUser->organization_id !== $user->organization_id) {
            throw new HttpResponseException(response()->json([
                'message' => 'Cannot add member.',
            ], 400));
        }

        TeamMember::create([
            'team_id' => $teamId,
            'user_id' => $userId,
            'joined_at' => now(),
        ]);
    }

    public function removeMember(int $teamId, int $userId, Request $request): void
    {
        TeamMember::where('team_id', $teamId)
            ->where('user_id', $userId)
            ->delete();
    }

    public function getOneModel(Request $request, int $id): Team
    {
        return Team::find($id);
    }
}