<?php

namespace TimFeid\SlackLaravelMail\Storage;

use TimFeid\SlackLaravelMail\SlackStorage;
use TimFeid\SlackLaravelMail\SlackNotFoundException;

class Cache extends SlackStorage
{
    public function create($message) : SlackStorage
    {
        $ttl = config('services.slackmail.cache.ttl', 60);
        $this->cache()->put($this->getName(), $message->getBody(), $ttl);

        return $this;
    }

    public function get($name) : String
    {
        if (!$this->cache()->has($name)) {
            throw new SlackNotFoundException("Unable to find '$name'");
        }

        return $this->cache()->get($name);
    }

    public function cache()
    {
        $cache = app('cache');

        if (($tag = config('serivces.slackmail.cache.tag'))) {
            return $cache->tags($tag);
        }

        return $cache;
    }
}
