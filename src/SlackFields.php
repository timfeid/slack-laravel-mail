<?php

namespace TimFeid\SlackLaravelMail;

use Illuminate\Support\Str;

class SlackFields
{
    protected $message;
    protected $defaultFields = [
        'subject',
        'to',
        'cc',
        'bcc',
        'from',
    ];

    public function build($message)
    {
        $this->message = $message;
        $fields = [];

        foreach (config('services.slackmail.fields', $this->defaultFields) as $field) {
            $method = Str::camel("build $field field");
            if (!method_exists($this, $method)) {
                throw new SlackException("Invalid field {$field}");
            }

            $fields[] = $this->$method();
        }

        return $fields;
    }

    public function buildToField()
    {
        return [
            'title' => 'To',
            'value' => implode(', ', array_keys($this->message->getTo() ?: [])),
            'short' => true,
        ];
    }

    public function buildSubjectField()
    {
        return [
            'title' => 'Subject',
            'value' => $this->message->getSubject(),
            'short' => true,
        ];
    }

    public function buildCcField()
    {
        return [
            'title' => 'Cc',
            'value' => implode(', ', array_keys($this->message->getCc() ?: [])),
            'short' => true,
        ];
    }

    public function buildBccField()
    {
        return [
            'title' => 'Bcc',
            'value' => implode(', ', array_keys($this->message->getBcc() ?: [])),
            'short' => true,
        ];
    }

    public function buildFromField()
    {
        return [
            'title' => 'From',
            'value' => implode(', ', array_keys($this->message->getFrom() ?: [])),
            'short' => true,
        ];
    }
}
