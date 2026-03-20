<?php

namespace App\Providers;

use App\Models\Exam;
use App\Observers\ExamObserver;
use App\Services\NotificationDropdownService;
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
            'student' => \App\Models\User::class,
            'web' => \App\Models\User::class,
        ]);

        // Register the observer to create notifications on exam publish
        Exam::observe(ExamObserver::class);

        $this->composeStudentNotifications();
        $this->composeAdminNotifications();
        $this->composeSuperAdminNotifications();
    }

    private function composeStudentNotifications(): void
    {
        View::composer('layouts.student', function ($view) {
            $notificationDropdownService = $this->app->make(NotificationDropdownService::class);
            $user = auth()->user();

            if (!$user || $user->role !== 'student') {
                $view->with($notificationDropdownService->getEmptyDropdownData());
                return;
            }

            $view->with($notificationDropdownService->getDropdownData(
                $user,
                'student.notifications.readAndRedirect',
                true
            ));
        });
    }

    private function composeAdminNotifications(): void
    {
        View::composer('layouts.admin', function ($view) {
            $notificationDropdownService = $this->app->make(NotificationDropdownService::class);
            $user = auth('admin')->user();

            if (!$user) {
                $view->with($notificationDropdownService->getEmptyDropdownData());
                return;
            }

            $view->with($notificationDropdownService->getDropdownData(
                $user,
                'admin.notifications.readAndRedirect'
            ));
        });
    }

    private function composeSuperAdminNotifications(): void
    {
        View::composer('layouts.superadmin', function ($view) {
            $notificationDropdownService = $this->app->make(NotificationDropdownService::class);
            $user = auth('superadmin')->user();

            if (!$user) {
                $view->with($notificationDropdownService->getEmptyDropdownData());
                return;
            }

            $view->with($notificationDropdownService->getDropdownData(
                $user,
                'superadmin.notifications.readAndRedirect'
            ));
        });
    }
}
