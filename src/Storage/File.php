<?php

namespace TimFeid\SlackLaravelMail\Storage;

use TimFeid\SlackLaravelMail\SlackStorage;
use TimFeid\SlackLaravelMail\SlackException;
use TimFeid\SlackLaravelMail\SlackNotFoundException;

class File extends SlackStorage
{
    public function create($message) : SlackStorage
    {
        $created = new self();
        
        $file = $created->path($created->getName());
        if (!is_writable($name)) {
            throw new SlackException("Unable to write to file '$file'");
        }

        file_put_contents($name, $message->getBody());

        return $created;
    }

    public function path($name)
    {
        return rtrim(config('serivces.slackmail.file.location'), PATH_SEPARATOR).PATH_SEPARATOR.$name;
    }

    public function get($name) : String
    {
        $file = $this->path($name);
        if (!file_exists($file)) {
            throw new SlackNotFoundException("Unable to find '$name'");
        }

        return file_get_contents($file);
    }
}
