<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeamCreateRequest;
use App\Http\Requests\TeamUpdateNameRequest;
use App\Models\Team;
use App\Models\User;
use App\Repositories\OrganizationRepository;
use App\Repositories\TeamRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TeamController extends Controller
{
    protected TeamRepository $teamRepository;
    protected OrganizationRepository $organizationRepository;

    public function __construct(TeamRepository $teamRepository, OrganizationRepository $organizationRepository)
    {
        $this->teamRepository = $teamRepository;
        $this->organizationRepository = $organizationRepository;
    }

    /**
     * Create a new team.
     */
    public function create(TeamCreateRequest $request): JsonResponse
    {
        if (
            ! Gate::allows(
                'my-organization-and-admin-or-moderator', 
                $this->organizationRepository->getOneModel($request->user()->organization_id)
            )
        ) {
            abort(403, 'Unauthorized.');
        }

        $team = $this->teamRepository->create($request);
        return response()->json($team, 201);
    }

    /**
     * Update the name of a team.
     */
    public function updateName(Team $team, TeamUpdateNameRequest $request): JsonResponse
    {
        if (
            ! Gate::allows(
                'my-organization-and-admin-or-moderator', 
                $this->organizationRepository->getOneModel($request->user()->organization_id)
            )
        ) {
            abort(403, 'Unauthorized.');
        }

        $this->teamRepository->updateName($team, $request);
        return response()->json(['message' => 'Team name updated successfully'], 200);
    }

    /**
     * Delete a team by ID.
     */
    public function delete(Team $team, Request $request): JsonResponse
    {
        if (
            ! Gate::allows(
                'my-organization-and-admin-or-moderator', 
                $this->organizationRepository->getOneModel($request->user()->organization_id)
            )
        ) {
            abort(403, 'Unauthorized.');
        }

        $this->teamRepository->delete($team, $request);
        return response()->json(['message' => 'Team deleted successfully'], 200);
    }

    /**
     * Add a member to a team.
     */
    public function addMember(Team $team, User $user, Request $request): JsonResponse
    {
        if (
            ! Gate::allows(
                'my-organization-and-admin-or-moderator', 
                $this->organizationRepository->getOneModel($request->user()->organization_id)
            )
        ) {
            abort(403, 'Unauthorized.');
        }

        $this->teamRepository->addMember($team, $user, $request);
        return response()->json(['message' => 'Member added successfully'], 200);
    }

    /**
     * Remove a member from a team.
     */
    public function removeMember(Team $team, User $user, Request $request): JsonResponse
    {
        if (
            ! Gate::allows(
                'my-organization-and-admin-or-moderator', 
                $this->organizationRepository->getOneModel($request->user()->organization_id)
            )
        ) {
            abort(403, 'Unauthorized.');
        }

        $this->teamRepository->removeMember($team, $user, $request);
        return response()->json(['message' => 'Member removed successfully'], 200);
    }
}