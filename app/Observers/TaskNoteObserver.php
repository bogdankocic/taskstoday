<?php

namespace App\Observers;

use App\Models\ProjectChatMessage;
use App\Models\TaskNote;
use Illuminate\Support\Facades\Auth;

class TaskNoteObserver
{
    /**
     * Handle the TaskNote "created" event.
     */
    public function created(TaskNote $taskNote): void
    {
        $user = $taskNote->user;
        ProjectChatMessage::create([
            'text' => "Task note created by {$user->first_name} {$user->last_name}",
            'project_id' => $taskNote->task->project_id,
        ]);
    }
}
