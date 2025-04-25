<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeamCreateRequest;
use App\Http\Requests\TeamUpdateNameRequest;
use App\Repositories\TeamRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    protected TeamRepository $teamRepository;

    public function __construct(TeamRepository $teamRepository)
    {
        $this->teamRepository = $teamRepository;
    }

    /**
     * Create a new team.
     */
    public function create(TeamCreateRequest $request): JsonResponse
    {
        $team = $this->teamRepository->create($request);
        return response()->json($team, 201);
    }

    /**
     * Update the name of a team.
     */
    public function updateName(TeamUpdateNameRequest $request, int $id): JsonResponse
    {
        $this->teamRepository->updateName($id, $request);
        return response()->json(['message' => 'Team name updated successfully'], 200);
    }

    /**
     * Delete a team by ID.
     */
    public function delete(int $id, Request $request): JsonResponse
    {
        $this->teamRepository->delete($id, $request);
        return response()->json(['message' => 'Team deleted successfully'], 200);
    }

    /**
     * Add a member to a team.
     */
    public function addMember(int $teamId, int $userId, Request $request): JsonResponse
    {
        $this->teamRepository->addMember($teamId, $userId, $request);
        return response()->json(['message' => 'Member added successfully'], 200);
    }

    /**
     * Remove a member from a team.
     */
    public function removeMember(int $teamId, int $userId, Request $request): JsonResponse
    {
        $this->teamRepository->removeMember($teamId, $userId, $request);
        return response()->json(['message' => 'Member removed successfully'], 200);
    }
}