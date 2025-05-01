<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileUploadRequest;
use App\Http\Requests\FileDeleteRequest;
use App\Models\Project;
use App\Models\ProjectFile;
use App\Models\Task;
use App\Models\TaskFile;
use App\Repositories\FileRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    protected FileRepository $fileRepository;

    public function __construct(FileRepository $fileRepository)
    {
        $this->fileRepository = $fileRepository;
    }

    /**
     * Upload a file.
     */
    public function upload(FileUploadRequest $request): JsonResponse
    {
        $projectId = $request->id;

        if($request->type === 'task') {
            $projectId = Task::find($projectId)->project_id;
        }

        $project = Project::find($projectId);

        if (! Gate::allows('my-organization-and-admin-or-moderator-and-user-on-project', [$project->organization, $project])) {
            abort(403, 'Unauthorized.');
        }

        $filePath = request()->file('file')->store("uploads/{$request->type}s", config('filesystems.default'));

        $this->fileRepository->create($request, $filePath);

        return response()->json(['message' => 'File uploaded successfully', 'file_path' => asset('storage/' . $filePath)], 201);
    }

    /**
     * Delete a file.
     */
    public function delete(FileDeleteRequest $request): JsonResponse
    {
        $file = null;
        $project = null;

        if($request->type === 'task') {
            $file = TaskFile::find($request->id);
            $project = $file->task->project;
        } else {
            $file = ProjectFile::find($request->id);
            $project = $file->project;
        }
        
        if (! Gate::allows('my-organization-and-admin-or-moderator-and-user-on-project', [$project->organization, $project])) {
            abort(403, 'Unauthorized.');
        }

        if($filePath = $this->fileRepository->delete($request)) {
            Storage::disk(config('filesystems.default'))->delete($filePath);
        }

        return response()->json(['message' => 'File deleted successfully'], 200);
    }

    /**
     * Download project file.
     */
    public function downloadProjectFile(ProjectFile $projectFile, Request $request)
    {
        $project = Project::find($projectFile->project_id);
        
        if (! Gate::allows('my-organization-and-admin-or-moderator-and-user-on-project', [$project->organization, $project])) {
            abort(403, 'Unauthorized.');
        }

        return Storage::disk(config('filesystems.default'))->download($projectFile->path);
    }

    /**
     * Download task file.
     */
    public function downloadTaskFile(TaskFile $taskFile, Request $request)
    {
        $project = Project::find(Task::find($taskFile->task_id)->project_id);
        
        if (! Gate::allows('my-organization-and-admin-or-moderator-and-user-on-project', [$project->organization, $project])) {
            abort(403, 'Unauthorized.');
        }

        return Storage::disk(config('filesystems.default'))->download($taskFile->path);
    }

    /**
     * Get all files for a specific project.
     */
    public function getByProject(Project $project, Request $request): JsonResponse
    {
        $files = $this->fileRepository->getByProject($project, $request);
        return response()->json($files);
    }

    /**
     * Get all files for a specific task.
     */
    public function getByTask(Task $task, Request $request): JsonResponse
    {
        $files = $this->fileRepository->getByTask($task, $request);
        return response()->json($files);
    }
}