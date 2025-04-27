<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProjectChatMessageRequest;
use App\Models\Organization;
use App\Models\Project;
use App\Repositories\ProjectChatMessageRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProjectChatMessageController extends Controller
{
    protected ProjectChatMessageRepository $projectChatMessageRepository;

    public function __construct(ProjectChatMessageRepository $projectChatMessageRepository)
    {
        $this->projectChatMessageRepository = $projectChatMessageRepository;
    }

    /**
     * Create a new project chat message.
     */
    public function create(Project $project, CreateProjectChatMessageRequest $request): JsonResponse
    {
        if (
            ! Gate::allows(
                'my-organization-and-admin-or-moderator-on-project-or-user-team-member-project-only', 
                [Organization::find($project->organization_id), $project],
            )
        ) {
            abort(403, 'Unauthorized.');
        }

        $this->projectChatMessageRepository->create($project, $request);

        return response()->json(['message' => 'Message created successfully'], 201);
    }

    /**
     * Get chat messages for the project.
     */
    public function get(Project $project, Request $request): JsonResponse
    {
        if (
            ! Gate::allows(
                'my-organization-and-admin-or-moderator-on-project-or-user-team-member-project-only', 
                [Organization::find($project->organization_id), $project],
            )
        ) {
            abort(403, 'Unauthorized.');
        }

        $projects = $this->projectChatMessageRepository->get($project, $request);

        return response()->json($projects, 201);
    }
}