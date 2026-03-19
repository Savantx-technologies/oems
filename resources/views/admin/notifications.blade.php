@extends('layouts.admin')

@section('title', 'Notifications')

@section('content')
    @include('partials.notifications-page', [
        'panel' => 'admin',
        'notifications' => $notifications,
        'exams' => collect(),
        'description' => 'Alerts regarding exams, violations, and system updates.',
        'markAllRoute' => route('admin.notifications.markRead'),
        'markSingleReadRoute' => 'admin.notifications.markSingleRead',
        'markSingleUnreadRoute' => 'admin.notifications.markSingleUnread',
        'deleteRoute' => 'admin.notifications.destroy',
        'readRoute' => 'admin.notifications.readAndRedirect',
        'emptyTitle' => 'No new notifications.',
        'emptySubtitle' => null,
        'paginationView' => 'pagination::tailwind',
        'containerClass' => 'bg-white rounded-2xl shadow-sm border border-gray-200',
        'soundPreference' => $notificationSoundPreference ?? ['tone' => 'chime', 'custom_sound_name' => null, 'custom_sound_url' => null],
        'soundPreferenceUpdateUrl' => route('admin.notifications.soundPreference.update'),
    ])
@endsection