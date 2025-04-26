<?php

namespace App\Observers;

use App\Models\TaskComplexityVote;
use App\Models\Task;
use App\Models\TeamMember;

class TaskComplexityVoteObserver
{
    /**
     * Handle the TaskComplexityVote "created" event.
     */
    public function created(TaskComplexityVote $vote): void
    {
        $task = Task::find($vote->task_id);
        $teamMembers = TeamMember::where('team_id', $task->team_id)->pluck('user_id');
        $voters = TaskComplexityVote::where('task_id', $task->id)->get();

        if ($teamMembers->diff($voters->pluck('user_id'))->isEmpty()) {
            $task->complexity = $voters->pluck('complexity')->avg();
            $task->save();
        }
    }
}
