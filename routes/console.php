<?php

use App\Jobs\DailyAchievementSystemRefreshJob;
use App\Jobs\WeeklyAchievementSystemRefreshJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::job(new WeeklyAchievementSystemRefreshJob)->weeklyOn(1, '8:00');
Schedule::job(new DailyAchievementSystemRefreshJob)->dailyAt('08:00');
