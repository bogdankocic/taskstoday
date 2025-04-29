<?php

namespace App\Repositories;

use App\Http\Requests\FileDeleteRequest;
use App\Http\Requests\FileUploadRequest;
use App\Http\Resources\ProjectFileResource;
use App\Http\Resources\TaskFileResource;
use App\Models\Project;
use App\Models\ProjectFile;
use App\Models\Task;
use App\Models\TaskFile;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FileRepository extends BaseRepository
{
    public function __construct(ProjectFile $file)
    {
        parent::__construct($file);
    }


    public function create(FileUploadRequest $request, string $path): void
    {
        if($request->type === 'project') {
            ProjectFile::create([
                'project_id' => $request->id,
                'title' => $request->title,
                'path' => $path,
            ]);
        } else {
            TaskFile::create([
                'task_id' => $request->id,
                'title' => $request->title,
                'path' => $path,
            ]);
        }
    }

    public function delete(FileDeleteRequest $request): string|null
    {
        $filePath = null;

        if($request->type === 'project') {
            $file = ProjectFile::find($request->id);
            $filePath = $file->path;
            $file->delete();
        } else {
            $file = TaskFile::find($request->id);
            $filePath = $file->path;
            $file->delete();
        }

        return $filePath;
    }

    public function getByProject(Project $project, Request $request): ResourceCollection
    {
        return ProjectFileResource::collection(ProjectFile::where('project_id', $project->id)->get());
    }

    public function getByTask(Task $task, Request $request): ResourceCollection
    {
        return TaskFileResource::collection(TaskFile::where('task_id', $task->id)->get());
    }
}