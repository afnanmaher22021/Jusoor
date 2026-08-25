<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        return view('notifications', [
            'notifications' => Auth::user()->notifications()
                ->orderByDesc('created_at')
                ->get(),
            'unreadCount' => Auth::user()->notifications()->unread()->count(),
        ]);
    }

    public function markRead(Notification $notification): RedirectResponse
    {
        abort_unless($notification->user_id === Auth::id(), 403);

        $notification->update(['read_at' => now()]);

        return back();
    }

    public function markAllRead(): RedirectResponse
    {
        Auth::user()->notifications()->unread()->update(['read_at' => now()]);

        return back()->with('success', __('تم تحديد جميع الإشعارات كمقروءة.'));
    }
}
