<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Share unread notifications count with all views
        View::composer('*', function ($view) {
            if (auth()->check()) {
                $unreadNotifications = auth()->user()->unreadNotifications()->count();
                $view->with('unreadNotifications', $unreadNotifications);
            }
        });
    }
}