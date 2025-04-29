<?php

namespace App\Repositories;

use App\Http\Requests\MarkAsSeenRequest;
use App\Http\Requests\NotificationCreateRequest;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class NotificationRepository extends BaseRepository
{
    public function __construct(Notification $model)
    {
        parent::__construct($model);
    }

    public function markAsSeen(MarkAsSeenRequest $request): void
    {
        Notification::whereIn('id', $request->notifications)
            ->where('user_id', request()->user()->id)
            ->update(['is_seen' => true]);
    }

    public function get(Request $request): ResourceCollection
    {
        return NotificationResource::collection(Notification::where('user_id', $request->user()->id)->get());
    }
}