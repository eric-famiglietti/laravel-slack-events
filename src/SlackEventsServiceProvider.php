<?php

namespace LaravelSlackEvents;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class SlackEventsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/slack-events.php' => config_path('slack-events.php'),
            ], 'config');
        }

        Route::macro('slackEventsWebhook', function ($url) {
            return Route::post($url, '\LaravelSlackEvents\SlackEventsController');
        });
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/slack-events.php', 'slack-events');
    }
}
