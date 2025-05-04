<?php

namespace App\Repositories;

use App\Http\Requests\CreateTaskNoteRequest;
use App\Http\Resources\TaskNoteResource;
use App\Models\Task;
use App\Models\TaskNote;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TaskNoteRepository extends BaseRepository
{
    public function __construct(TaskNote $model)
    {
        parent::__construct($model);
    }

    public function create(Task $task, CreateTaskNoteRequest $request): void
    {
        TaskNote::create([
            'text' => $request->text,
            'user_id' => request()->user()->id,
            'task_id' => $task->id,
        ]);
    }

    public function get(Task $task, Request $request): ResourceCollection
    {
        return TaskNoteResource::collection(TaskNote::with('user')
            ->where('task_id', $task->id)
            ->orderBy('created_at', 'desc')
            ->get());
    }
}