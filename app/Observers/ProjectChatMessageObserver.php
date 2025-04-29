<?php

namespace App\Observers;

use App\Enums\TagsIdsEnum;
use App\Models\ProjectChatMessage;

class ProjectChatMessageObserver
{
    /**
     * Handle the ProjectChatMessage "created" event.
     */
    public function created(ProjectChatMessage $projectChatMessage): void
    {
        if($projectChatMessage->user) {
            $projectChatMessage->user->tags()
                ->attach(TagsIdsEnum::Initiator->value, ['project_id' => $projectChatMessage->project_id]);
        }
    }
}
