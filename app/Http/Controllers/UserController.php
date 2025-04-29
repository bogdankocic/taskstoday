<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Get all users.
     */
    public function get(Request $request): JsonResponse
    {
        $users = $this->userRepository->get($request);
        return response()->json($users);
    }

    /**
     * Get self data users.
     */
    public function self(Request $request): JsonResponse
    {
        $users = $this->userRepository->self($request);
        return response()->json($users);
    }

    /**
     * Update the authenticated user.
     */
    public function selfUpdate(UpdateUserRequest $request): JsonResponse
    {
        $user = $this->userRepository->selfUpdate($request);
        return response()->json($user);
    }

    /**
     * Delete a user by ID.
     */
    public function delete(User $user): JsonResponse
    {
        if (! Gate::allows('my-organization-and-admin', $this->userRepository->getOneModel($user->id)->organization)) {
            abort(403, 'Unauthorized.');
        }

        $this->userRepository->delete($user);
        return response()->json(['message' => 'User deleted successfully'], 200);
    }
}