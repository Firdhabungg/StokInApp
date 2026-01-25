<?php

namespace App\Http\Controllers;

use App\Services\AdminNotificationService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    protected NotificationService $notificationService;
    protected AdminNotificationService $adminNotificationService;

    public function __construct(
        NotificationService $notificationService,
        AdminNotificationService $adminNotificationService
    ) {
        $this->notificationService = $notificationService;
        $this->adminNotificationService = $adminNotificationService;
    }

    public function index()
    {
        $user = Auth::user();

        // Redirect super admin (not in Akses Toko mode) to admin notifications
        if ($user->isSuperAdmin() && !$user->isAksesToko()) {
            return redirect()->route('admin.notifications.index');
        }

        $tokoId = $user->effective_toko_id;
        $notifications = $this->notificationService->getNotifications($tokoId);

        return view('notifications.index', compact('notifications'));
    }

    public function getNotifications()
    {
        $user = Auth::user();

        // Super Admin not in Akses Toko mode: use admin notifications
        if ($user->isSuperAdmin() && !$user->isAksesToko()) {
            $notifications = $this->adminNotificationService->getNotifications();
            $notifications = array_slice($notifications, 0, 5);

            return response()->json([
                'notifications' => $notifications,
                'count' => $this->adminNotificationService->getNotificationCount(),
                'critical' => $this->adminNotificationService->getCriticalCount(),
            ]);
        }

        // Regular users or Super Admin in Akses Toko mode
        $tokoId = $user->effective_toko_id;
        $notifications = $this->notificationService->getNotifications($tokoId);
        $notifications = array_slice($notifications, 0, 5);

        return response()->json([
            'notifications' => $notifications,
            'count' => $this->notificationService->getNotificationCount($tokoId),
            'critical' => $this->notificationService->getCriticalCount($tokoId),
        ]);
    }

    public function adminIndex()
    {
        $notifications = $this->adminNotificationService->getNotifications();

        return view('admin.notifications.index', compact('notifications'));
    }
}
