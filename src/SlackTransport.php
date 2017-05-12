<?php

namespace TimFeid\SlackLaravelMail;

abstract class SlackTransport {
    protected $name;

    abstract public function create($message);

    public function getName() {
        return $this->name;
    }
}