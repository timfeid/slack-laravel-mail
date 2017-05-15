<?php

namespace TimFeid\SlackLaravelMail;

abstract class SlackStorage {
    protected $name;

    abstract public function create($message) : SlackStorage;
    abstract public function get($name) : String;

    public function getName() {
        return $this->name = $this->name ?? uniqid('email', true);
    }
}