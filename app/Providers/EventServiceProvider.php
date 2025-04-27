<?php

namespace App\Providers;

use App\Models\Achievement;
use App\Models\ProjectFile;
use App\Models\Task;
use App\Models\TaskComplexityVote;
use App\Models\TaskFile;
use App\Models\TaskNote;
use App\Models\UserAchievement;
use App\Observers\ProjectFileObserver;
use App\Observers\TaskComplexityVoteObserver;
use App\Observers\TaskFileObserver;
use App\Observers\TaskNoteObserver;
use App\Observers\TaskObserver;
use App\Observers\UserAchievementObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     */
    protected $listen = [
        // Add other events here if needed
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        Task::observe(TaskObserver::class);
        TaskComplexityVote::observe(TaskComplexityVoteObserver::class);
        TaskFile::observe(TaskFileObserver::class);
        TaskNote::observe(TaskNoteObserver::class);
        ProjectFile::observe(ProjectFileObserver::class);
        UserAchievement::observe(UserAchievementObserver::class);
    }
}