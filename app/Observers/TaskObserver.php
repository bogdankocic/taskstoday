<?php

namespace App\Observers;

use App\Enums\TaskStatusesEnum;
use App\Models\ProjectChatMessage;
use App\Models\Task;

class TaskObserver
{
    /**
     * Handle the Task "created" event.
     */
    public function created(Task $task): void
    {
        $user = $task->creator;
        ProjectChatMessage::create([
            'text' => "Task created by {$user->first_name} {$user->last_name}",
            'project_id' => $task->project_id,
        ]);
    }

    /**
     * Handle the Task "updated" event.
     */
    public function updated(Task $task): void
    {
        $user = $task->creator;
        if ($task->wasChanged('status')) {
            if ($task->status === TaskStatusesEnum::INPROGRESS->value) {
                ProjectChatMessage::create([
                    'text' => "{$user->first_name} {$user->last_name} has activated task {$task->name}.",
                    'project_id' => $task->project_id,
                ]);
            }
    
            if ($task->status === TaskStatusesEnum::COMPLETED->value) {
                ProjectChatMessage::create([
                    'text' => "{$user->first_name} {$user->last_name} has completed task {$task->name}.",
                    'project_id' => $task->project_id,
                ]);
            }
        } else {
            ProjectChatMessage::create([
                'text' => "Task updated by {$user->first_name} {$user->last_name}",
                'project_id' => $task->project_id,
            ]);
        }
    }
}
