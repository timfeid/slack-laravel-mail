<?php

namespace TimFeid\SlackLaravelMail;

use Illuminate\Support\Manager;
use TimFeid\SlackLaravelMail\Transport\File;

class SlackTransportManager extends Manager
{
    public function createFileDriver()
    {
        return new File();
    }

    public function getDefaultDriver()
    {
        return $this->app['config']->get('services.slack.driver', 'file');
    }
}
