<?php

namespace TimFeid\SlackLaravelMail\Transport;

use TimFeid\SlackLaravelMail\SlackTransport;

class File extends SlackTransport
{
    public function create($message)
    {
        $this->name = uniqid('email', true);
        file_put_contents(storage_path($this->name), $message->getBody());

        return $this;
    }
}
