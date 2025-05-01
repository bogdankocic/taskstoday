<?php

namespace App\Providers;

use App\Enums\RolesEnum;
use App\Enums\TeamRolesEnum;
use App\Models\Organization;
use App\Models\Project;
use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\ServiceProvider;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(UserRepository::class, function ($app) {
            return new UserRepository($app->make(\App\Models\User::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('my-organization', function (User $user, Organization $organization) {
            return $user->organization_id === $organization->id;
        });

        Gate::define('my-organization-and-admin', function (User $user, Organization $organization) {
            return $user->organization_id === $organization->id && $user->teamrole === TeamRolesEnum::ADMIN->value;
        });

        Gate::define('my-organization-and-admin-or-moderator', function (User $user, Organization $organization) {
            return $user->organization_id === $organization->id && in_array($user->teamrole, [TeamRolesEnum::ADMIN->value, TeamRolesEnum::MODERATOR->value]);
        });

        Gate::define('my-organization-and-admin-or-moderator-on-project-or-user-team-member', function (
            User $user, 
            Organization $organization,
            Team $team,
            int $projectId,
        ) {
            return 
            ($user->organization_id === $organization->id && $user->teamrole === TeamRolesEnum::ADMIN->value) ||
            (
                $user->teamrole === TeamRolesEnum::MODERATOR->value &&
                $user->teams->count() > 0 && 
                $user->teams->pluck('project_id')->contains($projectId)
            ) ||
            ($team->members->count() > 0 && $team->members->contains('user_id', $user->id));
        });

        Gate::define('my-organization-and-admin-or-moderator-on-project-or-creator', function (
            User $user, 
            Organization $organization,
            Task $task,
            int $projectId,
        ) {
            return
            ($user->organization_id === $organization->id && $user->teamrole === TeamRolesEnum::ADMIN->value) ||
            (
                $user->teamrole === TeamRolesEnum::MODERATOR->value &&
                $user->teams->count() > 0 && 
                $user->teams->pluck('project_id')->contains($projectId)
            ) ||
            $task->creator_id === $user->id;
        });

        Gate::define('my-organization-and-admin-or-moderator-team-member-or-self', function (User $current, Organization $organization, Team $team, User $user) {
            return 
            ($current->organization_id === $organization->id && $current->teamrole === TeamRolesEnum::ADMIN->value) || 
            ($team->members->contains('id', $current->id) && $current->teamrole === TeamRolesEnum::MODERATOR->value) ||
            ($current->id === $user->id);
        });

        Gate::define('my-organization-and-admin-or-moderator-and-user-on-project', function (
            User $user, 
            Organization $organization,
            Project $project,
        ) {
            return
            ($user->organization_id === $organization->id && $user->teamrole === TeamRolesEnum::ADMIN->value) ||
            $project->teams()->with('members')->get()->pluck('members')->flatten()->contains('id', $user->id);
        });

        Gate::define('my-organization-and-admin-or-moderator-on-project-or-user-team-member-project-only', function (
            User $user, 
            Organization $organization,
            Project $project,
        ) {
            return 
            ($user->organization_id === $organization->id && $user->teamrole === TeamRolesEnum::ADMIN->value) || 
            $user->teams->pluck('project_id')->contains($project->id);
        });

        Gate::define('team-member-only', function (
            User $user,
            Team $team,
        ) {
            return $team->members->contains('id', $user->id);
        });

        Gate::define('performer-only', function (
            User $user,
            Task $task,
        ) {
            return $task->performer_id === $user->id;
        });

        Gate::define('app-admin-only', function (User $user) {
            return $user->role->name === RolesEnum::ADMIN->value;
        });
    }
}
