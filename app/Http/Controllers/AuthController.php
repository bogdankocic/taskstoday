<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserActivateRequest;
use App\Http\Requests\UserInviteRequest;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json(['token' => $token], 200);
    }

    /**
     * Log out the currently authenticated user.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

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
