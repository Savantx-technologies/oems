<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use App\Models\Exam;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('notifiable_id', Auth::id())
            ->where('notifiable_type', get_class(Auth::user()))
            ->latest()
            ->paginate(15);

        return view('admin.notifications', compact('notifications'));
    }

    public function markAsRead()
    {
        Notification::where('notifiable_id', Auth::id())
            ->where('notifiable_type', get_class(Auth::user()))
            ->where('is_read', 0)
            ->update(['is_read' => 1]);

        return back()->with('success', 'All notifications marked as read.');
    }

    public function markSingleAsRead(Notification $notification)
    {
        if ($notification->notifiable_id !== Auth::id()) {
            abort(403);
        }

        $notification->update(['is_read' => 1]);

        return back()->with('success', 'Notification marked as read.');
    }

    public function unreadCount()
    {
        $count = Notification::where('notifiable_id', Auth::id())
            ->where('notifiable_type', get_class(Auth::user()))
            ->where('is_read', 0)
            ->count();

        return response()->json(['count' => $count]);
    }
    
    public function readAndRedirect(Notification $notification)
    {
        if (!$notification->is_read) {
            $notification->update(['is_read' => 1]);
        }
        
        // Redirect logic based on type
        if ($notification->type === 'violation' && isset($notification->data['exam_id'])) {
            return redirect()->route('admin.exams.monitor', $notification->data['exam_id']);
        }
        
        if ($notification->type === 'exam_published' && isset($notification->data['exam_id'])) {
            return redirect()->route('admin.exams.show', $notification->data['exam_id']);
        }

        return redirect()->route('admin.notifications');
    }
}
