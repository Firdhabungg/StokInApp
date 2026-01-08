<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display all notifications.
     */
    public function index()
    {
        $tokoId = Auth::user()->effective_toko_id;
        $notifications = $this->notificationService->getNotifications($tokoId);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Get notifications for dropdown (AJAX).
     */
    public function getNotifications()
    {
        $tokoId = Auth::user()->effective_toko_id;
        $notifications = $this->notificationService->getNotifications($tokoId);

        // Limit to 5 for dropdown
        $notifications = array_slice($notifications, 0, 5);

        return response()->json([
            'notifications' => $notifications,
            'count' => $this->notificationService->getNotificationCount($tokoId),
            'critical' => $this->notificationService->getCriticalCount($tokoId),
        ]);
    }
}
