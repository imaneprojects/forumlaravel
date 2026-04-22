<?php
namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $notifications = Notification::where('user_id', $user->id)
            ->with('fromUser')
            ->orderByDesc('created_at')
            ->paginate(10);

        $user->notifications()->update(['read' => true]);

        return view('notifications.index', compact('notifications',"user"));
    }

    public function getNotifications()
    {
        return auth()->user()
            ->notifications()
            ->with('fromUser') // 🔥 important pour avatar
            ->latest()
            ->take(10)
            ->get();
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('read', false)
            ->update(['read' => true]);

        return response()->json(['success' => true]);
    }

}
