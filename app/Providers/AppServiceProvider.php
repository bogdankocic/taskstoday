<?php

namespace App\Providers;

use App\Enums\RolesEnum;
use App\Enums\TeamRolesEnum;
use App\Models\Organization;
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

        Gate::define('admin-only', function (User $user) {
            return $user->role->name === RolesEnum::ADMIN->value;
        });
    }
}
