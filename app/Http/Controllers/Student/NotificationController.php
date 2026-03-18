<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Exam;
use App\Models\Notification;
use App\Models\NotificationSoundPreference;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class NotificationController extends Controller
{
    private function ensureOwner(Notification $notification): void
    {
        if (
            $notification->notifiable_id !== Auth::id() ||
            $notification->notifiable_type !== get_class(Auth::user())
        ) {
            abort(403);
        }
    }

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
        $this->ensureOwner($notification);

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
        $this->ensureOwner($notification);

        $notification->update(['is_read' => 1]);

        return back()->with('success', 'Notification marked as read.');
    }

    public function markSingleAsUnread(Notification $notification)
    {
        $this->ensureOwner($notification);

        $notification->update(['is_read' => 0]);

        return back()->with('success', 'Notification marked as unread.');
    }

    public function destroy(Notification $notification)
    {
        $this->ensureOwner($notification);

        $notification->delete();

        return back()->with('success', 'Notification deleted successfully.');
    }

    public function unreadCount()
    {
        $count = Notification::where('notifiable_id', Auth::id())
            ->where('notifiable_type', get_class(Auth::user()))
            ->where('is_read', 0)
            ->count();

        return response()->json(['count' => $count]);
    }

    public function updateSoundPreference(Request $request)
    {
        $validated = $request->validate([
            'tone' => ['required', 'string', 'in:chime,alert,bell,pop,custom,silent'],
            'custom_sound' => ['nullable', 'file', 'mimetypes:audio/mpeg,audio/wav,audio/x-wav,audio/ogg,audio/mp3,audio/webm', 'max:512'],
            'remove_custom_sound' => ['nullable', 'boolean'],
        ]);

        $user = Auth::user();
        $preference = $user->notificationSoundPreference()->firstOrCreate([], ['tone' => 'chime']);
        $removeCustomSound = (bool) ($validated['remove_custom_sound'] ?? false);

        if ($removeCustomSound) {
            $this->deleteCustomSound($preference);
        }

        if ($request->hasFile('custom_sound')) {
            $this->deleteCustomSound($preference);
            $path = $request->file('custom_sound')->store('notification-ringtones', 'public');
            $preference->custom_sound_path = $path;
            $preference->custom_sound_name = $request->file('custom_sound')->getClientOriginalName();
        }

        if (($validated['tone'] ?? null) === 'custom' && !$preference->custom_sound_path) {
            return response()->json(['message' => 'Upload a custom ringtone first.'], 422);
        }

        $preference->tone = $validated['tone'];
        $preference->save();

        return response()->json([
            'message' => 'Notification ringtone updated successfully.',
            'preference' => [
                'tone' => $preference->tone,
                'custom_sound_name' => $preference->custom_sound_name,
                'custom_sound_url' => $preference->custom_sound_url,
            ],
        ]);
    }

    private function deleteCustomSound(NotificationSoundPreference $preference): void
    {
        if ($preference->custom_sound_path) {
            Storage::disk('public')->delete($preference->custom_sound_path);
        }

        $preference->custom_sound_path = null;
        $preference->custom_sound_name = null;
    }
}
