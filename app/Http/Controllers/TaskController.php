<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatusesEnum;
use App\Http\Requests\ComplexityVoteRequest;
use App\Http\Requests\TaskCreateRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Models\Organization;
use App\Models\Project;
use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use App\Repositories\TaskRepository;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    protected TaskRepository $taskRepository;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * Get all tasks.
     */
    public function get(Request $request): JsonResponse
    {
        $filters = [
            'project_id' => $request->query('project_id'),
            'team_id' => $request->query('team_id'),
            'complexity' => $request->query('complexity'),
            'sort_by_time' => $request->query('sort_by_time'),
            'sort_by_complexity' => $request->query('sort_by_complexity'),
            'save_filter' => (boolean)$request->query('save_filter') ?? false,
        ];

        $tasks = $this->taskRepository->get($filters, $request);
        return response()->json($tasks);
    }

    /**
     * Create a new task.
     */
    public function create(TaskCreateRequest $request): JsonResponse
    {
        if (
            ! Gate::allows(
                'my-organization-and-admin-or-moderator-on-project-or-user-team-member', 
                [Organization::find(Project::find($request->project_id)->organization_id),
                Team::find($request->team_id),
                $request->project_id],
            )
        ) {
            abort(403, 'Unauthorized.');
        }

        $task = $this->taskRepository->create($request);
        return response()->json($task, 201);
    }

    /**
     * Update an existing task.
     */
    public function update(Task $task, TaskUpdateRequest $request): JsonResponse
    {
        if (
            ! Gate::allows(
                'my-organization-and-admin-or-moderator-on-project-or-creator', 
                [Organization::find(Project::find($task->project_id)->organization_id),
                $task,
                $task->project_id],
            )
        ) {
            abort(403, 'Unauthorized.');
        }

        $task = $this->taskRepository->update($task, $request);
        return response()->json($task);
    }

    /**
     * Delete a task by ID.
     */
    public function delete(Task $task, Request $request): JsonResponse
    {
        if (
            ! Gate::allows(
                'my-organization-and-admin-or-moderator-on-project-or-creator', 
                [Organization::find(Project::find($task->project_id)->organization_id),
                $task,
                $task->project_id],
            )
        ) {
            abort(403, 'Unauthorized.');
        }

        $this->taskRepository->delete($task, $request);
        return response()->json(['message' => 'Task deleted successfully'], 200);
    }

    /**
     * Assignes performer for the task.
     */
    public function assignPerformer(Task $task, User $user, Request $request): JsonResponse
    {
        if(!$task->team->members->contains('id', $user->id)) {
            throw new HttpResponseException(response()->json([
                'message' => 'Cannot assign user.',
            ], 400));
        }

        if (
            ! Gate::allows(
                'my-organization-and-admin-or-moderator-team-member-or-self',
                [Organization::find(Project::find($task->project_id)->organization_id),
                Team::find($task->team_id),
                $user],
            )
        ) {
            abort(403, 'Unauthorized.');
        }

        $this->taskRepository->assignPerformer($task, $user, $request);
        return response()->json(['message' => 'Performer assigned successfully'], 200);
    }

    /**
     * Assignes contributor for the task.
     */
    public function assignContributor(Task $task, User $user, Request $request): JsonResponse
    {
        if (
            ! Gate::allows(
                'performer-only',
                $task,
            )
        ) {
            abort(403, 'Unauthorized.');
        }

        if(
            !$task->team->members->contains('id', $user->id) || 
            $task->performer_id === $user->id
        ) {
            throw new HttpResponseException(response()->json([
                'message' => 'Cannot assign user.',
            ], 400));
        }

        $this->taskRepository->assignContributor($task, $user, $request);
        return response()->json(['message' => 'Contributor assigned successfully'], 200);
    }

    /**
     * Activate a task.
     */
    public function activate(Task $task, Request $request): JsonResponse
    {
        if(
            !$task->complexity || $task->status !== TaskStatusesEnum::TODO->value
        ) {
            throw new HttpResponseException(response()->json([
                'message' => 'Cannot activate task.',
            ], 400));
        }

        if (
            ! Gate::allows(
                'performer-only',
                $task,
            )
        ) {
            abort(403, 'Unauthorized.');
        }

        $this->taskRepository->activate($task, $request);
        return response()->json(['message' => 'Task activated successfully'], 200);
    }

    /**
     * Complete a task.
     */
    public function complete(Task $task, Request $request): JsonResponse
    {
        if(
            $task->status !== TaskStatusesEnum::INPROGRESS->value
        ) {
            throw new HttpResponseException(response()->json([
                'message' => 'Cannot complete task.',
            ], 400));
        }

        if (
            ! Gate::allows(
                'performer-only',
                $task,
            )
        ) {
            abort(403, 'Unauthorized.');
        }

        $this->taskRepository->complete($task, $request);
        return response()->json(['message' => 'Task completed successfully'], 200);
    }

    /**
     * Vote on task complexity.
     */
    public function complexityVote(Task $task, ComplexityVoteRequest $request): JsonResponse
    {
        if (
            ! Gate::allows(
                'team-member-only',
                Team::find($task->team_id),
            )
        ) {
            abort(403, 'Unauthorized.');
        }

        $this->taskRepository->complexityVote($task, $request);
        return response()->json(['message' => 'Vote recorded successfully'], 200);
    }
}