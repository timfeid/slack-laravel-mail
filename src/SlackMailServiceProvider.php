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
          return new Transport(config('services.slack.endpoint', env('SLACK_ENDPOINT')));
        });
    }

    public function register()
    {
        parent::register();

        $this->registerSlackTransport();
    }

    public function registerSlackTransport()
    {
        $this->app['slackmail.transport'] = $this->app->share(function () {
            return new SlackTransportManager($this->app);
        });
    }
}
