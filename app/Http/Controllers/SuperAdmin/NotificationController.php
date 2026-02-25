<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use App\Models\Exam;

class NotificationController extends Controller
{
    //
    /**
     * Display all notifications for the super admin.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::guard('superadmin')->user();
        $notifications = $user->notifications()->latest()->paginate(15);

        // Get unique exam IDs from notifications on the current page to avoid N+1
        $examIds = $notifications->pluck('data.exam_id')->filter()->unique()->toArray();

        // Eager load the exams and their schedules
        $exams = Exam::with('schedule')
            ->whereIn('id', $examIds)
            ->get()
            ->keyBy('id');

        return view('superadmin.notifications', compact('notifications', 'exams'));
    }

    /**
     * Mark all unread notifications as read.
     */
    public function markAsRead()
    {
        Auth::guard('superadmin')->user()->unreadNotifications()->update(['is_read' => 1]);

        return back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Mark a single notification as read.
     */
    public function markSingleAsRead(Notification $notification)
    {
        if ($notification->notifiable_id !== Auth::guard('superadmin')->id() || $notification->notifiable_type !== get_class(Auth::guard('superadmin')->user())) {
            abort(403);
        }

        $notification->markAsRead();

        return back()->with('success', 'Notification marked as read.');
    }

    /**
     * Get the count of unread notifications.
     */
    public function unreadCount()
    {
        $count = Auth::guard('superadmin')->user()->unreadNotifications()->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Mark a notification as read and redirect to its associated URL.
     */
    public function readAndRedirect(Notification $notification)
    {
        if ($notification->notifiable_id !== Auth::guard('superadmin')->id() || $notification->notifiable_type !== get_class(Auth::guard('superadmin')->user())) {
            abort(403);
        }

        $notification->markAsRead();

        // Redirect logic based on notification data
        if (isset($notification->data['url'])) {
            return redirect($notification->data['url']);
        }

        if (isset($notification->data['exam_id'])) {
            return redirect()->route('superadmin.exams.show', $notification->data['exam_id']);
        }

        // Fallback to notifications index
        return redirect()->route('superadmin.notifications.index');
    }
}
