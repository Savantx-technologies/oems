<?php

namespace App\Providers;

use App\Models\Exam;
use App\Models\Notification;
use App\Observers\ExamObserver;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        Relation::morphMap([
            'admin' => \App\Models\Admin::class,
            'superadmin' => \App\Models\SuperAdmin::class,
        ]);

        // Register the observer to create notifications on exam publish
        Exam::observe(ExamObserver::class);

        // Share unread notifications count with student layouts
        View::composer('layouts.student', function ($view) {
            if (auth()->check() && auth()->user()->role === 'student') {
                $unreadCount = Notification::where('notifiable_id', auth()->id())
                    ->where('notifiable_type', get_class(auth()->user()))
                    ->where('is_read', 0)->count();
                $view->with('unreadNotificationsCount', $unreadCount);
            }
        });

        // Share unread notifications count with admin layouts
        View::composer('layouts.admin', function ($view) {
            if (auth('admin')->check()) {
                $unreadCount = Notification::where('notifiable_id', auth('admin')->id())
                    ->where('notifiable_type', get_class(auth('admin')->user()))
                    ->where('is_read', 0)->count();
                $view->with('unreadNotificationsCount', $unreadCount);
            }
        });

        // Share unread notifications count with superadmin layouts
        View::composer('layouts.superadmin', function ($view) {
            if (auth('superadmin')->check()) {
                $unreadCount = auth('superadmin')->user()->unreadNotifications()->count();
                $view->with('unreadNotificationsCount', $unreadCount);
            } else {
                $view->with('unreadNotificationsCount', 0);
            }
        });
    }
}
