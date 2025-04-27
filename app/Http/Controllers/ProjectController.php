<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectCreateRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Models\Project;
use App\Repositories\OrganizationRepository;
use App\Repositories\ProjectRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProjectController extends Controller
{
    protected ProjectRepository $projectRepository;
    protected OrganizationRepository $organizationRepository;

    public function __construct(ProjectRepository $projectRepository, OrganizationRepository $organizationRepository)
    {
        $this->projectRepository = $projectRepository;
        $this->organizationRepository = $organizationRepository;
    }

    /**
     * Create a new project.
     */
    public function create(ProjectCreateRequest $request): JsonResponse
    {
        if (
            ! Gate::allows(
                'my-organization-and-admin-or-moderator', 
                $this->organizationRepository->getOneModel($request->user()->organization_id)
            )
        ) {
            abort(403, 'Unauthorized.');
        }

        $project = $this->projectRepository->create($request);
        return response()->json($project, 201);
    }

    /**
     * Update an existing project.
     */
    public function update(Project $project, ProjectUpdateRequest $request): JsonResponse
    {
        if (
            ! Gate::allows(
                'my-organization-and-admin-or-moderator', 
                $this->organizationRepository->getOneModel($request->user()->organization_id)
            )
        ) {
            abort(403, 'Unauthorized.');
        }

        $project = $this->projectRepository->update($project, $request);
        return response()->json($project);
    }

    /**
     * Delete a project by ID.
     */
    public function delete(Project $project, Request $request): JsonResponse
    {
        if (
            ! Gate::allows(
                'my-organization-and-admin-or-moderator', 
                $this->organizationRepository->getOneModel($request->user()->organization_id)
            )
        ) {
            abort(403, 'Unauthorized.');
        }

        $this->projectRepository->delete($project, $request);
        return response()->json(['message' => 'Project deleted successfully'], 200);
    }

    /**
     * Get all projects.
     */
    public function get(Request $request): JsonResponse
    {
        $projects = $this->projectRepository->get($request);
        return response()->json($projects);
    }

    /**
     * Get all projects.
     */
    public function finish(Project $project, Request $request): JsonResponse
    {
        if (
            ! Gate::allows(
                'my-organization-and-admin-or-moderator', 
                $this->organizationRepository->getOneModel($request->user()->organization_id)
            )
        ) {
            abort(403, 'Unauthorized.');
        }
        
        $projects = $this->projectRepository->finish($project, $request);
        return response()->json($projects);
    }
}