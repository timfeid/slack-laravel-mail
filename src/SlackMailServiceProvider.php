<?php

namespace TimFeid\SlackLaravelMail;

use Illuminate\Mail\MailServiceProvider;
use TimFeid\SlackLaravelMail\Mail\Transport;

class SlackMailServiceProvider extends MailServiceProvider
{
    public function registerSwiftTransport()
    {
        parent::registerSwiftTransport();

        app('swift.transport')->extend('slack', function($app) {
          return new Transport(config('services.slackmail.endpoint', env('SLACK_ENDPOINT')));
        });
    }

    public function register()
    {
        $this->registerSlackStorage();
        $this->registerSlackFields();

        parent::register();
    }

    public function registerSlackStorage()
    {
        $this->app['slackmail.storage'] = $this->app->share(function () {
            return new SlackStorageManager($this->app);
        });
    }

    public function registerSlackFields()
    {
        $this->app['slackmail.fields'] = $this->app->share(function () {
            return new SlackFields();
        });
    }

    public function provides()
    {
        return array_merge(parent::provides(), ['slackmail.storage', 'slackmail.fields']);
    }
}
