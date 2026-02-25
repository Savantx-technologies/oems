<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Exam;
use App\Models\Notification;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class NotificationController extends Controller
{
    public function index()
    {
        $allNotifications = Notification::where('notifiable_id', Auth::id())
            ->where('notifiable_type', get_class(Auth::user()))
            ->latest()
            ->get();

        // Get unique exam IDs from all notifications to avoid N+1 problem
        $examIds = $allNotifications->pluck('data.exam_id')->filter()->unique()->toArray();

        // Eager load the exams and their schedules
        $exams = Exam::with('schedule')
            ->whereIn('id', $examIds)
            ->get()
            ->keyBy('id');
        
        $filteredNotifications = $allNotifications->filter(function ($notification) use ($exams) {
            if ($notification->type !== 'exam') {
                return true; // Keep non-exam notifications
            }
            $examId = $notification->data['exam_id'] ?? null;
            if (!$examId || !isset($exams[$examId])) {
                return true; // Keep if no exam_id or exam not found
            }
            // Discard notification if the associated exam has expired
            return !$exams[$examId]->isExpired();
        });

        // Manually paginate the filtered collection
        $perPage = 15;
        $currentPage = Paginator::resolveCurrentPage('page');
        $currentPageItems = $filteredNotifications->slice(($currentPage - 1) * $perPage, $perPage);
        $notifications = new LengthAwarePaginator($currentPageItems, $filteredNotifications->count(), $perPage, $currentPage, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);
        
        return view('student.notifications', compact('notifications', 'exams'));
    }

    public function markAsRead()
    {
        Notification::where('notifiable_id', Auth::id())
            ->where('notifiable_type', get_class(Auth::user()))
            ->where('is_read', 0)
            ->update(['is_read' => 1]);

        return back()->with('success', 'All notifications marked as read.');
    }

    public function readAndRedirect(Notification $notification)
    {
        // Ensure the notification belongs to the authenticated user
        if ($notification->notifiable_id !== Auth::id()) {
            abort(403);
        }

        // Mark the notification as read if it's unread
        if (!$notification->is_read) {
            $notification->update(['is_read' => 1]);
        }

        // Redirect to the exam if the data exists
        if (isset($notification->data['exam_id'])) {
            return redirect()->route('student.exams.live', $notification->data['exam_id']);
        }

        // Fallback redirect to the notifications page if no exam_id is found
        return redirect()->route('student.notifications');
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
}
