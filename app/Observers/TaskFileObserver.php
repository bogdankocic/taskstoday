<?php

namespace App\Observers;

use App\Models\ProjectChatMessage;
use App\Models\TaskFile;

class TaskFileObserver
{
    /**
     * Handle the TaskFile "created" event.
     */
    public function created(TaskFile $taskFile): void
    {
        ProjectChatMessage::create([
            'text' => "Task file created",
            'project_id' => $taskFile->task->project_id,
        ]);
    }
}
