<?php

namespace App\Observers;

use App\Enums\AchievementsIdsEnum;
use App\Enums\KarmaCategoriesEnum;
use App\Enums\TagsIdsEnum;
use App\Enums\TaskStatusesEnum;
use App\Models\Notification;
use App\Models\ProjectChatMessage;
use App\Models\Task;
use App\Models\UserProjectTag;
use Carbon\Carbon;

class TaskObserver
{
    /**
     * Handle the Task "created" event.
     */
    public function created(Task $task): void
    {
        $user = $task->creator;
        $user->tags()->attach(TagsIdsEnum::Creator->value, ['project_id' => $task->project_id]);
        if($user->createdTasks()->where('created_at', '>', Carbon::today()->setTime(8, 0, 0))->count() > 10) {
            !$user->achievements->pluck('id')->contains(AchievementsIdsEnum::Organizer->value) ? 
                $user->achievements()->attach(AchievementsIdsEnum::Organizer->value) : null;
        }

        $user->save();

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
        $performer = $task->performer;

        $projectTasksForUser = $task->project->tasks()
            ->where('performer_id', $performer->id)
            ->get();

        $allUserNotCompletedTasks = $user->tasks()->whereNot('status', TaskStatusesEnum::COMPLETED->value)->get();

        if ($task->wasChanged('performer_id')) {
            Notification::create([
                'title' => "Task assigned", 
                'content' => "You have been assigned to a task {$task->name}", 
                'is_seen' => false, 
                'user_id' => $task->performer_id,
            ]);
        }

        if ($task->wasChanged('contributor_id')) {
            Notification::create([
                'title' => "Help requested", 
                'content' => "You have been assigned to help with a task {$task->name}", 
                'is_seen' => false, 
                'user_id' => $task->contributor_id,
            ]);
        }

        if ($task->wasChanged('status')) {
            if ($task->status === TaskStatusesEnum::INPROGRESS->value) {
                ProjectChatMessage::create([
                    'text' => "{$user->first_name} {$user->last_name} has activated task {$task->name}.",
                    'project_id' => $task->project_id,
                ]);
                $performer->tags()->attach(TagsIdsEnum::TaskInProgress->value, ['project_id' => $task->project_id]);
            }
    
            if ($task->status === TaskStatusesEnum::COMPLETED->value) {
                $user->tasks_completed_count++;
                $achievementsToAttach = [];
                $contributorAchievementsToAttach = [];
                $contributor = null;

                $lastCompletedTask = $task->project->tasks()
                    ->where('performer_id', $performer->id)
                    ->where('status', TaskStatusesEnum::COMPLETED->value)
                    ->where('id', '<', $task->id)
                    ->orderBy('updated_at', 'desc')
                    ->first();

                $yesterday8AM = Carbon::yesterday()->setTime(8, 0, 0);

                if ($lastCompletedTask && $lastCompletedTask->updated_at->greaterThanOrEqualTo($yesterday8AM)) {
                    $user->tasks_completed_strike++;
                } else {
                    $user->tasks_completed_strike = 1;
                }

                if($user->tasks_completed_count === 0) {
                    $achievementsToAttach[] = AchievementsIdsEnum::FirstTask->value;
                }

                if($user->tasks_completed_count === 50) {
                    $achievementsToAttach[] = AchievementsIdsEnum::Commited->value;
                }

                if(
                    Task::where('contributor_id', $task->contributor_id)
                        ->where('status', TaskStatusesEnum::COMPLETED->value)
                        ->count() === 20
                ) {
                    $contributorAchievementsToAttach[] = AchievementsIdsEnum::TeamPlayer->value;
                }
                
                ProjectChatMessage::create([
                    'text' => "{$user->first_name} {$user->last_name} has completed task {$task->name}.",
                    'project_id' => $task->project_id,
                ]);

                if($projectTasksForUser->where('status', TaskStatusesEnum::INPROGRESS->value)->count() < 1) {
                    UserProjectTag::where('user_id', $performer->id)
                        ->where('tag_id', TagsIdsEnum::TaskInProgress->value)
                        ->where('project_id', $task->project_id)
                        ->delete();
                }

                if($allUserNotCompletedTasks->count() < 1) {
                    UserProjectTag::where('user_id', $performer->id)
                        ->where('tag_id', TagsIdsEnum::FinishedSprint->value)
                        ->where('project_id', $task->project_id)
                        ->delete();
                }

                if($user->tasks_completed_strike === 7) {
                    UserProjectTag::where('user_id', $performer->id)
                        ->where('tag_id', TagsIdsEnum::Focused->value)
                        ->where('project_id', $task->project_id)
                        ->delete();
                }

                if($task->contributor_id) {
                    UserProjectTag::where('user_id', $task->contributor_id)
                        ->where('tag_id', TagsIdsEnum::HelpingHand->value)
                        ->where('project_id', $task->project_id)
                        ->delete();
                    $contributor = $task->contributor;
                    $contributor->karma += $task->complexity * 5;

                    if(
                        in_array(
                            KarmaCategoriesEnum::fromScore($contributor->karma), 
                            [KarmaCategoriesEnum::LEGENDA_RARE, KarmaCategoriesEnum::LEGENDA_PERMANENT]
                        )
                    ) {
                        $achievementId = KarmaCategoriesEnum::fromScore($contributor->karma) === KarmaCategoriesEnum::LEGENDA_RARE ? AchievementsIdsEnum::Visionary->value: AchievementsIdsEnum::Legend->value;

                        $contributorAchievementsToAttach[] = $achievementId;
                    }

                    $contributor->save();
                }

                if (! empty($achievementsToAttach)) {
                    $existingAchievements = $user->achievements()->pluck('achievement_id')->toArray();
                    $newAchievements = array_diff($achievementsToAttach, $existingAchievements);
                
                    if (!empty($newAchievements)) {
                        $user->achievements()->attach($newAchievements);
                    }
                }

                if (! empty($contributorAchievementsToAttach)) {
                    $existingAchievements = $contributor->achievements()->pluck('achievement_id')->toArray();
                    $newAchievements = array_diff($contributorAchievementsToAttach, $existingAchievements);
                
                    if (!empty($newAchievements)) {
                        $contributor->achievements()->attach($newAchievements);
                    }
                }
        

                $user->karma += $task->complexity * 10;

                if(
                    in_array(
                        KarmaCategoriesEnum::fromScore($contributor->karma), 
                        [KarmaCategoriesEnum::LEGENDA_RARE, KarmaCategoriesEnum::LEGENDA_PERMANENT]
                    )
                ) {
                    $achievementId = KarmaCategoriesEnum::fromScore($contributor->karma) === KarmaCategoriesEnum::LEGENDA_RARE ? AchievementsIdsEnum::Visionary->value: AchievementsIdsEnum::Legend->value;

                    $achievementsToAttach[] = $achievementId;
                }

                $user->save();
            }
        } else {
            ProjectChatMessage::create([
                'text' => "Task updated by {$user->first_name} {$user->last_name}",
                'project_id' => $task->project_id,
            ]);
        }
    }
}
