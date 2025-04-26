<?php

namespace App\Providers;

use App\Models\TaskComplexityVote;
use App\Observers\TaskComplexityVoteObserver;
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
        TaskComplexityVote::observe(TaskComplexityVoteObserver::class);
    }
}