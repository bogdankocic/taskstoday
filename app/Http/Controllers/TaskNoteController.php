<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTaskNoteRequest;
use App\Models\Organization;
use App\Models\Project;
use App\Models\Task;
use App\Models\Team;
use App\Repositories\TaskNoteRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TaskNoteController extends Controller
{
    protected TaskNoteRepository $taskNoteRepository;

    public function __construct(TaskNoteRepository $taskNoteRepository)
    {
        $this->taskNoteRepository = $taskNoteRepository;
    }

    /**
     * Create a new task note.
     */
    public function create(Task $task, CreateTaskNoteRequest $request): JsonResponse
    {
        if (
            ! Gate::allows(
                'my-organization-and-admin-or-moderator-on-project-or-user-team-member', 
                [Organization::find(Project::find($task->project_id)->organization_id),
                Team::find($task->team_id),
                $task->project_id],
            )
        ) {
            abort(403, 'Unauthorized.');
        }

        $this->taskNoteRepository->create($task, $request);

        return response()->json(['message' => 'Task note created successfully'], 201);
    }

    /**
     * Get task notes for the task.
     */
    public function get(Task $task, Request $request): JsonResponse
    {
        if (
            ! Gate::allows(
                'my-organization-and-admin-or-moderator-on-project-or-user-team-member', 
                [Organization::find(Project::find($task->project_id)->organization_id),
                Team::find($task->team_id),
                $task->project_id],
            )
        ) {
            abort(403, 'Unauthorized.');
        }

        $tasks = $this->taskNoteRepository->get($task, $request);

        return response()->json($tasks, 201);
    }
}