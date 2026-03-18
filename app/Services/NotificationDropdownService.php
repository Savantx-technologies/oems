<?php

namespace App\Services;

use App\Models\Exam;
use App\Models\Notification;
use App\Models\NotificationSoundPreference;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;

class NotificationDropdownService
{
    public function getDropdownData(Authenticatable $user, string $routeName, bool $filterExpiredStudentExams = false): array
    {
        $notificationsQuery = Notification::where('notifiable_id', $user->getAuthIdentifier())
            ->where('notifiable_type', get_class($user))
            ->latest();

        $notifications = (clone $notificationsQuery)->take(8)->get();
        $unreadCount = (clone $notificationsQuery)->where('is_read', 0)->count();

        $previewItems = $filterExpiredStudentExams
            ? $this->buildStudentPreviewItems($notifications, $routeName)
            : $this->buildPreviewItems($notifications->take(5), $routeName);

        return [
            'unreadNotificationsCount' => $unreadCount,
            'notificationPreviewItems' => $previewItems,
            'notificationSoundPreference' => $this->getSoundPreferenceData($user),
        ];
    }

    public function getEmptyDropdownData(): array
    {
        return [
            'unreadNotificationsCount' => 0,
            'notificationPreviewItems' => collect(),
            'notificationSoundPreference' => $this->getDefaultSoundPreferenceData(),
        ];
    }

    private function getSoundPreferenceData(Authenticatable $user): array
    {
        $preference = $user->notificationSoundPreference;

        if (!$preference instanceof NotificationSoundPreference) {
            return $this->getDefaultSoundPreferenceData();
        }

        return [
            'tone' => $preference->tone ?: 'chime',
            'custom_sound_name' => $preference->custom_sound_name,
            'custom_sound_url' => $preference->custom_sound_url,
        ];
    }

    private function getDefaultSoundPreferenceData(): array
    {
        return [
            'tone' => 'chime',
            'custom_sound_name' => null,
            'custom_sound_url' => null,
        ];
    }

    private function buildStudentPreviewItems(Collection $notifications, string $routeName): Collection
    {
        if ($notifications->isEmpty()) {
            return collect();
        }

        $examIds = $notifications->pluck('data.exam_id')->filter()->unique()->toArray();
        $exams = Exam::with('schedule')->whereIn('id', $examIds)->get()->keyBy('id');

        return $notifications
            ->filter(function (Notification $notification) use ($exams) {
                if ($notification->type !== 'exam') {
                    return true;
                }

                $examId = $notification->data['exam_id'] ?? null;

                if (!$examId || !isset($exams[$examId])) {
                    return true;
                }

                return !$exams[$examId]->isExpired();
            })
            ->take(5)
            ->values()
            ->map(fn (Notification $notification) => $this->transformNotification($notification, $routeName));
    }

    private function buildPreviewItems(Collection $notifications, string $routeName): Collection
    {
        return $notifications
            ->map(fn (Notification $notification) => $this->transformNotification($notification, $routeName));
    }

    private function transformNotification(Notification $notification, string $routeName): array
    {
        $data = is_array($notification->data) ? $notification->data : [];

        return [
            'id' => $notification->id,
            'title' => $notification->title ?? $data['title'] ?? 'Notification',
            'message' => $notification->message ?? $data['message'] ?? 'You have a new notification.',
            'time' => $notification->created_at?->diffForHumans(),
            'is_read' => (bool) $notification->is_read,
            'url' => route($routeName, $notification->id),
        ];
    }
}
