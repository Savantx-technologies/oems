@extends('layouts.admin')

@section('title', 'Notifications')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-800 mb-1 flex items-center gap-2">
                <i class="bi bi-bell-fill text-indigo-500"></i>
                Notifications
            </h1>
            <p class="text-sm text-gray-500">Alerts regarding exams, violations, and system updates.</p>
        </div>
        @if($notifications->where('is_read', false)->count() > 0)
            <form action="{{ route('admin.notifications.markRead') }}" method="POST" class="flex-shrink-0">
                @csrf
                <button type="submit"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 border border-indigo-600 rounded-lg text-sm font-semibold text-white hover:bg-indigo-700 transition-all shadow focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <i class="bi bi-check2-all text-lg"></i>
                    <span>Mark all as read</span>
                </button>
            </form>
        @endif
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="">
            @forelse($notifications as $notification)
                @php
                    $isViolation = $notification->type === 'violation';
                    $isExam = $notification->type === 'exam_published';
                @endphp
                <div class="flex flex-col sm:flex-row items-start gap-5 px-6 py-6 sm:py-7 group transition-colors {{ $notification->is_read ? 'bg-gray-50/40' : 'bg-white hover:bg-indigo-50/30' }} border-b last:border-b-0 border-gray-100">

                    <div class="flex-shrink-0 w-14 h-14 rounded-xl flex items-center justify-center shadow-sm {{ $notification->is_read ? 'bg-gray-200 text-gray-400' : ($isViolation ? 'bg-red-100 text-red-600' : 'bg-indigo-100 text-indigo-600') }}">
                        <i class="bi {{ $isViolation ? 'bi-exclamation-triangle-fill' : ($isExam ? 'bi-check-circle-fill' : 'bi-bell') }} text-2xl"></i>
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                            <h3 class="font-semibold text-lg {{ $notification->is_read ? 'text-gray-600' : 'text-gray-900' }} group-hover:text-indigo-700 transition">
                                {{ $notification->title }}
                            </h3>
                            <div class="flex flex-col items-start sm:items-end flex-shrink-0">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $notification->is_read ? 'bg-gray-200 text-gray-500' : 'bg-blue-100 text-blue-600' }}">
                                    <i class="bi bi-clock mr-1 text-[12px]"></i>
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                                @if(!$notification->is_read)
                                    <span class="mt-1 px-1.5 py-0.5 rounded font-bold text-xs bg-indigo-50 text-indigo-600 animate-pulse">● New</span>
                                @endif
                            </div>
                        </div>
                        <div class="mt-2 text-gray-700 text-sm leading-relaxed {{ $notification->is_read ? 'opacity-70' : 'opacity-95' }}">
                            {{ $notification->message }}
                        </div>

                        <div class="mt-4 flex flex-wrap items-center gap-2">
                            @if($isViolation)
                                <a href="{{ route('admin.notifications.readAndRedirect', $notification->id) }}"
                                    class="inline-flex items-center px-3 py-1.5 rounded bg-red-50 text-red-700 font-medium text-sm hover:bg-red-100 transition border border-red-100 gap-1">
                                    <i class="bi bi-camera-video"></i>
                                    Monitor Exam
                                </a>
                            @elseif($isExam)
                                <a href="{{ route('admin.notifications.readAndRedirect', $notification->id) }}"
                                    class="inline-flex items-center px-3 py-1.5 rounded bg-indigo-50 text-indigo-700 font-medium text-sm hover:bg-indigo-100 transition border border-indigo-100 gap-1">
                                    <i class="bi bi-eye"></i>
                                    View Exam
                                </a>
                            @endif

                            @if(!$notification->is_read)
                                <form action="{{ route('admin.notifications.markSingleRead', $notification->id) }}" method="POST" class="inline-block m-0">
                                    @csrf
                                    <button type="submit"
                                        class="inline-flex items-center px-2.5 py-1.5 rounded text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 hover:text-indigo-600 border border-gray-200 transition shadow-sm gap-1">
                                        <i class="bi bi-check2"></i>
                                        Mark as Read
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-16 text-center text-gray-400 flex flex-col items-center gap-2">
                    <i class="bi bi-bell-slash text-5xl block text-gray-300 mb-3"></i>
                    <p class="text-lg font-medium">No new notifications.</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="mt-8 flex justify-center">
        {{ $notifications->links() }}
    </div>
</div>
@endsection