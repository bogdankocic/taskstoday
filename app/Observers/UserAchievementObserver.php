<?php

namespace App\Observers;

use App\Models\ProjectChatMessage;
use App\Models\UserAchievement;

class UserAchievementObserver
{
    /**
     * Handle the TaskFile "created" event.
     */
    public function created(UserAchievement $userAchievement): void
    {
        $user = $userAchievement->user;
        ProjectChatMessage::create([
            'text' => "Task file created by {$user->first_name} {$user->last_name}",
            'project_id' => $userAchievement->project_id,
        ]);
    }
}
