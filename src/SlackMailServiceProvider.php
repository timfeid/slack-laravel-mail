<?php

namespace TimFeid\SlackLaravelMail;

use Illuminate\Mail\TransportManager;
use Illuminate\Support\ServiceProvider;
use TimFeid\SlackLaravelMail\Mail\Transport;

class SlackMailServiceProvider extends ServiceProvider
{
    public function extendTransportManager($manager)
    {
        $manager->extend('slack', function($app) {
          return new Transport(config('services.slackmail.endpoint', env('SLACK_ENDPOINT')));
        });
    }

    public function register()
    {
        $this->registerSlackStorage();
        $this->registerSlackFields();

        $this->app->afterResolving(TransportManager::class, function(TransportManager $manager) {
            $this->extendTransportManager($manager);
        });
    }

    public function registerSlackStorage()
    {
        $this->app->singleton('slackmail.storage', function() {
            return new SlackStorageManager($this->app);
        });
    }

    public function registerSlackFields()
    {
        $this->app->singleton('slackmail.fields', SlackFields::class);
    }

    public function provides()
    {
        return ['slackmail.storage', 'slackmail.fields'];
    }
}
