<?php

namespace TimFeid\SlackLaravelMail;

use Illuminate\Support\Manager;
use TimFeid\SlackLaravelMail\Storage\File;
use TimFeid\SlackLaravelMail\Storage\Cache;

class SlackStorageManager extends Manager
{
    public function createFileDriver()
    {
        return new File();
    }

    public function createCacheDriver()
    {
        return new Cache();
    }

    public function getDefaultDriver()
    {
        return $this->app['config']->get('services.slackmail.driver', 'file');
    }
}
