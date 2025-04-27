<?php

namespace App\Repositories;

use App\Http\Requests\CreateProjectChatMessageRequest;
use App\Http\Resources\ProjectChatMessageResource;
use App\Models\Project;
use App\Models\ProjectChatMessage;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProjectChatMessageRepository extends BaseRepository
{
    public function __construct(ProjectChatMessage $model)
    {
        parent::__construct($model);
    }

    public function create(Project $project, CreateProjectChatMessageRequest $request): void
    {
        ProjectChatMessage::create([
            'text' => $request->text,
            'user_id' => request()->user()->id,
            'project_id' => $project->id,
        ]);
    }

    public function get(Project $project, Request $request): ResourceCollection
    {
        return ProjectChatMessageResource::collection(ProjectChatMessage::where('project_id', $project->id)
            ->orderBy('created_at', 'desc')
            ->get());
    }
}