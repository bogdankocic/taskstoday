<?php

namespace App\Repositories;

use App\Enums\TaskStatusesEnum;
use App\Enums\TeamRolesEnum;
use App\Http\Requests\ComplexityVoteRequest;
use App\Http\Requests\TaskCreateRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Http\Resources\TaskResource;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskComplexityVote;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Cache;

class TaskRepository extends BaseRepository
{
    public function __construct(Project $model)
    {
        parent::__construct($model);
    }

    public function create(TaskCreateRequest $request): TaskResource
    {
        $task = Task::create([
            'name' => $request->name,
            'description' => $request->description,
            'project_id' => $request->project_id,
            'team_id' => $request->team_id,
            'status' => TaskStatusesEnum::TODO->value,
            'creator_id' => $request->user()->id,
        ]);

        return new TaskResource($task);
    }

    public function update(Task $task, TaskUpdateRequest $request): TaskResource
    {
        $task->name = $request->name;
        $task->description = $request->description;
        $task->save();

        return new TaskResource($task);
    }

    public function delete(Task $task, Request $request): void
    {
        $task->delete();
    }

    public function get(array $filters, Request $request): ResourceCollection
    {
        $user = $request->user();
        $query = Task::query();

        if($user->teamrole === TeamRolesEnum::ADMIN->value) {
            $query->join('projects', 'tasks.project_id', '=', 'projects.id')
                ->where('projects.organization_id', $user->organization_id)
                ->select('tasks.*');
        } else if($user->teamrole === TeamRolesEnum::MODERATOR->value) {
            $userProjects = $user->allProjects()->pluck('id')->toArray();

            $query->whereIn('project_id', $userProjects);  
        } else {
            $userTeams = $user->teams->pluck('id')->toArray();

            $query->whereIn('team_id', $userTeams); 
        }

        if($filters['save_filter']) {
            $cacheKey = "task-filter-{$request->user()->id}";
        
            unset($filters['save_filter']);
            Cache::forever($cacheKey, $filters);
        }

        $filterableFields = ['team_id', 'complexity'];

        foreach ($filterableFields as $field) {
            if (!empty($filters[$field])) {
                $query->where($field, $filters[$field]);
            }
        }

        if (!empty($filters['sort_by_time'])) {
            $query->orderBy('created_at', $filters['sort_by_time']);
        }

        if (!empty($filters['sort_by_complexity'])) {
            $query->orderBy('complexity', $filters['sort_by_complexity']);
        }

        $tasks = $query->get();

        return TaskResource::collection($tasks);
    }

    public function assignPerformer(Task $task, User $user, Request $request): void
    {
        $task->performer_id = $user->id;
        $task->save();
    }

    public function assignContributor(Task $task, User $user, Request $request): void
    {
        $task->contributor_id = $user->id;
        $task->save();
    }

    public function activate(Task $task, Request $request): void
    {
        $task->status = TaskStatusesEnum::INPROGRESS->value;
        $task->save();
    }

    public function complete(Task $task, Request $request): void
    {
        $task->status = TaskStatusesEnum::COMPLETED->value;
        $task->save();
    }

    public function complexityVote(Task $task, ComplexityVoteRequest $request): void
    {
        $user = request()->user();
        $vote = TaskComplexityVote::where('task_id', $task->id)->where('user_id', $user->id)->first();

        if($vote === null) {
            TaskComplexityVote::create(
                ['task_id' => $task->id, 'user_id' => $user->id, 'complexity' => $request->complexity]
            );
        }
    }
}