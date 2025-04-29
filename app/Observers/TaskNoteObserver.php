<?php

namespace App\Observers;

use App\Enums\TagsIdsEnum;
use App\Models\ProjectChatMessage;
use App\Models\TaskNote;
use Carbon\Carbon;

class TaskNoteObserver
{
    /**
     * Handle the TaskNote "created" event.
     */
    public function created(TaskNote $taskNote): void
    {
        $user = $taskNote->user;
        $project = $taskNote->task->project;
        ProjectChatMessage::create([
            'text' => "Task note created by {$user->first_name} {$user->last_name}",
            'project_id' => $taskNote->task->project_id,
        ]);

        $lastMonday8AM = Carbon::now()->startOfWeek()->setTime(8, 0, 0);

        if(
            TaskNote::where('user_id', $user->id)->whereIn('task_id', $project->tasks->pluck('id'))->where('created_at', '>=', $lastMonday8AM)->count() === 4
        ) {
            $user->tags()->attach(TagsIdsEnum::Mentor->value, ['project_id' => $project->id]);
        }
    }
}
