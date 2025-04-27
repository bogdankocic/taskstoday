<?php

namespace App\Observers;

use App\Models\ProjectChatMessage;
use App\Models\ProjectFile;

class ProjectFileObserver
{
    /**
     * Handle the ProjectFile "created" event.
     */
    public function created(ProjectFile $projectFile): void
    {
        ProjectChatMessage::create([
            'text' => "Project file created",
            'project_id' => $projectFile->project_id,
        ]);
    }
}
