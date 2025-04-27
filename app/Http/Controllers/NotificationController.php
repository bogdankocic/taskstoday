<?php

namespace App\Http\Controllers;

use App\Http\Requests\MarkAsSeenRequest;
use App\Http\Requests\NotificationCreateRequest;
use App\Models\Notification;
use App\Repositories\NotificationRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class NotificationController extends Controller
{
    protected NotificationRepository $notificationRepository;

    public function __construct(NotificationRepository $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    /**
     * Get all notifications for the authenticated user.
     */
    public function get(Request $request): JsonResponse
    {
        $notifications = $this->notificationRepository->get($request);
        return response()->json($notifications);
    }

    /**
     * Mark a notification as seen.
     */
    public function markAsSeen(MarkAsSeenRequest $request): JsonResponse
    {
        $this->notificationRepository->markAsSeen($request);
        return response()->json(['message' => 'Notification marked as seen'], 200);
    }
}