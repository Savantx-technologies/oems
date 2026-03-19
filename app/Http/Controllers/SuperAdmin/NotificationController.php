<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\NotificationSoundPreference;
use Illuminate\Support\Facades\Auth;
use App\Models\Exam;
use Illuminate\Support\Facades\Storage;

class NotificationController extends Controller
{
    private function ensureOwner(Notification $notification): void
    {
        if (
            $notification->notifiable_id !== Auth::guard('superadmin')->id() ||
            $notification->notifiable_type !== get_class(Auth::guard('superadmin')->user())
        ) {
            abort(403);
        }
    }

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
        $this->ensureOwner($notification);

        $notification->markAsRead();

        return back()->with('success', 'Notification marked as read.');
    }

    public function markSingleAsUnread(Notification $notification)
    {
        $this->ensureOwner($notification);

        $notification->markAsUnread();

        return back()->with('success', 'Notification marked as unread.');
    }

    public function destroy(Notification $notification)
    {
        $this->ensureOwner($notification);

        $notification->delete();

        return back()->with('success', 'Notification deleted successfully.');
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
        $this->ensureOwner($notification);

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

    public function updateSoundPreference(Request $request)
    {
        $validated = $request->validate([
            'tone' => ['required', 'string', 'in:chime,alert,bell,pop,custom,silent'],
            'custom_sound' => ['nullable', 'file', 'mimetypes:audio/mpeg,audio/wav,audio/x-wav,audio/ogg,audio/mp3,audio/webm', 'max:512'],
            'remove_custom_sound' => ['nullable', 'boolean'],
        ]);

        $user = Auth::guard('superadmin')->user();
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
