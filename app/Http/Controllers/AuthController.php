<?php

namespace App\Http\Controllers;

use App\Enums\AchievementsIdsEnum;
use App\Enums\TagsIdsEnum;
use App\Http\Requests\UserActivateRequest;
use App\Http\Requests\UserInviteRequest;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Log in a user and return an API token.
     */
    public function login(Request $request)
    {
        $tagsToAttach = [];
        $achievementsToAttach = [];
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('api-token')->plainTextToken;
        $tagsToAttach[] = TagsIdsEnum::Active->value;
        $projectIds = $user->allProjects()->pluck('id');

        $currentTime = Carbon::now();
        $startTime = Carbon::createFromTime(18, 0, 0);
        $endTime = Carbon::createFromTime(8, 0, 0)->addDay();
    
        $user->login_strike++;
        if($user->login_strike === 10){
            $achievementsToAttach[] = AchievementsIdsEnum::Maraton->value;
        }

        if ($currentTime->between($startTime, $endTime)) {
            $tagsToAttach[] = TagsIdsEnum::LateWorker->value;
            $user->login_after_hours_count++;

            if($user->login_after_hours_count === 10) {
                $achievementsToAttach[] = AchievementsIdsEnum::NightBird->value;
            }
        }

        $bulkTags = [];

        foreach ($projectIds as $projectId) {
            foreach (array_values($tagsToAttach) as $tagId) {
                $bulkTags[] = [
                    'user_id'    => $user->id,
                    'tag_id'     => $tagId,
                    'project_id' => $projectId,
                ];
            }
        }

        if (! empty($bulkTags)) {
            DB::table('user_project_tag')->insert($bulkTags);
        }

        if (! empty($achievementsToAttach)) {
            $existingAchievements = $user->achievements()->pluck('id')->toArray();
            $newAchievements = array_diff($achievementsToAttach, $existingAchievements);
        
            if (!empty($newAchievements)) {
                $user->achievements()->attach($newAchievements);
            }
        }

        $user->save();

        return response()->json(['token' => $token], 200);
    }

    /**
     * Log out the currently authenticated user.
     */
    public function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();
        $user->tags()->detach(TagsIdsEnum::Active->value);

        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    /**
     * Invite a new user to the platform.
     */
    public function inviteUser(UserInviteRequest $request)
    {
        $this->userRepository->inviteUser($request);
        return response()->json(['message' => 'User invited successfully'], 200);
    }

    /**
     * Activate a user account.
     */
    public function activateUser(UserActivateRequest $request)
    {
        $this->userRepository->activateUser($request);
        return response()->json(['message' => 'User activated successfully'], 200);
    }
}
