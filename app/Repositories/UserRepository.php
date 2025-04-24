<?php

namespace App\Repositories;

use App\Enums\RolesEnum;
use App\Enums\TeamRolesEnum;
use App\Http\Requests\UserActivateRequest;
use App\Http\Requests\UserInviteRequest;
use App\Models\Role;
use App\Models\User;
use App\Notifications\UserInviteNotification;

class UserRepository extends BaseRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function inviteUser(UserInviteRequest $request): void
    {
        $organizationId = $request->input('organization_id') ?? $request->user()->organization_id;
        $teamRole = $request->user()->role->name === RolesEnum::ADMIN->value ?              TeamRolesEnum::ADMIN : $request->input('team_role');

        $user = User::create([
            'email' => $request->input('email'),
            'organization_id' => $organizationId,
            'teamrole' => $teamRole,
            'role_id' => Role::where('name', 'user')->first()->id,
        ]);

        $user->notify(new UserInviteNotification($user));
    }

    public function activateUser(UserActivateRequest $request): void
    {
        $user = User::findOrFail($request->query('user_id'));
        $user->update([
            'password' => bcrypt($request->input('password')),
        ]);
    }
}