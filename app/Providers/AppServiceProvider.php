<?php

namespace App\Providers;

use App\Models\Notification;
use App\Models\Topic;
use App\Policies\TopicPolicy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Nette\Utils\Paginator;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
    Topic::class => TopicPolicy::class,
    ];

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
        View::composer('*', function ($view) {
        if (Auth::check()) {
            $notificationsCount = Notification::where('user_id', Auth::id())
                ->where('read', false)
                ->count();

            $view->with('notificationsCount', $notificationsCount);
        }
    });
    }

}
