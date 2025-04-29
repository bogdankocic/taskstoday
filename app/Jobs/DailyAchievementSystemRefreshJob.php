<?php

namespace App\Jobs;

use App\Enums\TagsIdsEnum;
use App\Models\UserProjectTag;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class DailyAchievementSystemRefreshJob implements ShouldQueue
{
    use Queueable;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        UserProjectTag::whereIn([
            TagsIdsEnum::Creator->value, 
            TagsIdsEnum::Initiator->value, 
            TagsIdsEnum::UploadedFile->value, 
            TagsIdsEnum::HelpingHand->value
            ])->delete();
    }
}
