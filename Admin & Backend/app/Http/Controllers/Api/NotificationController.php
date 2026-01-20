<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(Request $req)
    {
        [$notification ,$total] = $this->notificationService->index($req);

        return response()
            ->json(
                [
                    'notification' => $notification,
                    'total' => $total,
                ]);

    }

    public function markAsRead(Request $req, $id)
    {
        $notification = $req->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['status' => 'Notification marked as read']);

    }

    public function markAllAsRead(Request $req)
    {
        $req->user()->unreadNotifications->markAsRead();

        return response()->json(['status' => 'All notifications marked as read']);
    }
}
