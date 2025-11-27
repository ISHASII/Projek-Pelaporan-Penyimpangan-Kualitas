<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class NotificationController extends BaseController
{
    public function markAllRead(Request $request)
    {
        $user = $request->user();
        if (! $user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        try {
            $user->unreadNotifications->markAsRead();
            return response()->json(['status' => 'ok', 'message' => 'All notifications marked as read']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Could not mark notifications as read'], 500);
        }
    }

    /**
     * Mark a single notification as read for the authenticated user.
     */
    public function markRead(Request $request, $id)
    {
        $user = $request->user();
        if (! $user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        try {
            $notification = $user->unreadNotifications()->where('id', $id)->first();
            if (! $notification) {
                return response()->json(['status' => 'error', 'message' => 'Notification not found or already read'], 404);
            }

            $notification->markAsRead();
            return response()->json(['status' => 'ok', 'message' => 'Notification marked as read']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Could not mark notification as read'], 500);
        }
    }
}