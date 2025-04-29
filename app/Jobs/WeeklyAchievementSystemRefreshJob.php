<?php

namespace App\Jobs;

use App\Enums\TagsIdsEnum;
use App\Models\User;
use App\Models\UserProjectTag;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class WeeklyAchievementSystemRefreshJob implements ShouldQueue
{
    use Queueable;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        User::update(['task_completed_strikes' => 0]);
        UserProjectTag::whereIn([TagsIdsEnum::LateWorker->value, TagsIdsEnum::Mentor->value, TagsIdsEnum::Focused->value])->delete();
    }
}
