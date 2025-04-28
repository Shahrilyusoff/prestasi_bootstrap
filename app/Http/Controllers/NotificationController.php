<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = $request->user()->notifications()
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($notifications);
    }

    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== request()->user()->id) {
            abort(403, 'Anda tidak dibenarkan mengemaskini notifikasi ini');
        }

        $notification->update(['is_read' => true]);

        return response()->json([
            'message' => 'Notifikasi telah ditandakan sebagai dibaca',
            'notification' => $notification,
        ]);
    }

    public function markAllAsRead(Request $request)
    {
        $request->user()->notifications()
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'message' => 'Semua notifikasi telah ditandakan sebagai dibaca',
        ]);
    }

    public function destroy(Notification $notification)
    {
        if ($notification->user_id !== request()->user()->id) {
            abort(403, 'Anda tidak dibenarkan memadam notifikasi ini');
        }

        $notification->delete();

        return response()->json([
            'message' => 'Notifikasi berjaya dipadam',
        ]);
    }
}