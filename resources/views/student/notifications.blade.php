@extends('layouts.student')

@section('title', 'Notifications')

@section('content')
    @include('partials.notifications-page', [
        'panel' => 'student',
        'notifications' => $notifications,
        'exams' => $exams ?? collect(),
        'description' => 'Stay updated with the latest announcements and alerts for your account.',
        'markAllRoute' => route('student.notifications.markRead'),
        'markSingleReadRoute' => 'student.notifications.markSingleRead',
        'markSingleUnreadRoute' => 'student.notifications.markSingleUnread',
        'deleteRoute' => 'student.notifications.destroy',
        'readRoute' => 'student.notifications.readAndRedirect',
        'emptyTitle' => 'No new notifications at this time.',
        'emptySubtitle' => 'You\'ll see important alerts and updates here.',
        'paginationView' => 'pagination::tailwind',
        'containerClass' => 'bg-white/80 backdrop-blur shadow-lg rounded-2xl border border-gray-100',
        'soundPreference' => $notificationSoundPreference ?? ['tone' => 'chime', 'custom_sound_name' => null, 'custom_sound_url' => null],
        'soundPreferenceUpdateUrl' => route('student.notifications.soundPreference.update'),
    ])
@endsection
