<?php

namespace App\Repositories;

use App\Enums\RolesEnum;
use App\Enums\TeamRolesEnum;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UserActivateRequest;
use App\Http\Requests\UserInviteRequest;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use App\Notifications\UserInviteNotification;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

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

    public function selfUpdate(UpdateUserRequest $request): UserResource
    {
        $user = $request->user();

        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->profile_photo = $request->file('profile_photo') ? 
            $request->file('profile_photo')->store('profile_photos') : null;
        $user->save();

        return new UserResource($user);
    }

    public function delete(User $user): void
    {
        $user->delete();
    }

    public function get(Request $request): ResourceCollection
    {
        $user = $request->user();

        if ($user->role->name === RolesEnum::ADMIN->value) {
            return UserResource::collection(User::all());
        } else {
            return UserResource::collection(User::where('organization_id', $user->organization_id)->get());
        }
    }

    public function self(Request $request): UserResource
    {
        return new UserResource($request->user()->load(['teams', 'tasks', 'achievements', 'tags']));
    }

    public function getOneModel(int $id): User
    {
        return User::find($id);
    }
}