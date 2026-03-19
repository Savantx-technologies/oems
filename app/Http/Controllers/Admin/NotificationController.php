<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Notification;
use App\Models\NotificationSoundPreference;

class NotificationController extends Controller
{
    private function ensureOwner(Notification $notification): void
    {
        if (
            $notification->notifiable_id !== Auth::guard('admin')->id() ||
            $notification->notifiable_type !== get_class(Auth::guard('admin')->user())
        ) {
            abort(403);
        }
    }

    public function index()
    {
        $notifications = Notification::where('notifiable_id', Auth::guard('admin')->id())
            ->where('notifiable_type', get_class(Auth::guard('admin')->user()))
            ->latest()
            ->paginate(15);

        return view('admin.notifications', compact('notifications'));
    }

    public function markAsRead()
    {
        Notification::where('notifiable_id', Auth::guard('admin')->id())
            ->where('notifiable_type', get_class(Auth::guard('admin')->user()))
            ->where('is_read', 0)
            ->update(['is_read' => 1]);

        return back()->with('success', 'All notifications marked as read.');
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
        $count = Notification::where('notifiable_id', Auth::guard('admin')->id())
            ->where('notifiable_type', get_class(Auth::guard('admin')->user()))
            ->where('is_read', 0)
            ->count();

        return response()->json(['count' => $count]);
    }
    
    public function readAndRedirect(Notification $notification)
    {
        $this->ensureOwner($notification);

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

    public function updateSoundPreference(Request $request)
    {
        $validated = $request->validate([
            'tone' => ['required', 'string', 'in:chime,alert,bell,pop,custom,silent'],
            'custom_sound' => ['nullable', 'file', 'mimetypes:audio/mpeg,audio/wav,audio/x-wav,audio/ogg,audio/mp3,audio/webm', 'max:512'],
            'remove_custom_sound' => ['nullable', 'boolean'],
        ]);

        $user = Auth::guard('admin')->user();
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
