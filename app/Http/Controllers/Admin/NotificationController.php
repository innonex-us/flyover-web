<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function markRead(string $id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        $url = $notification->data['url'] ?? route('admin.dashboard');
        return redirect($url);
    }

    public function markAllRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back();
    }

    public function index()
    {
        $notifications = auth()->user()->notifications()->latest()->paginate(20);
        return view('admin.notifications.index', compact('notifications'));
    }

    public function destroy(string $id)
    {
        auth()->user()->notifications()->findOrFail($id)->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back();
    }
}
