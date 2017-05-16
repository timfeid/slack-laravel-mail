<?php

namespace TimFeid\SlackLaravelMail\Mail;

use Swift_Mime_Message;
use TimFeid\Slack\Client;
use TimFeid\SlackLaravelMail\SlackFields;
use TimFeid\SlackLaravelMail\SlackException;
use Illuminate\Mail\Transport\Transport as BaseTransport;

class Transport extends BaseTransport
{
    protected $config;
    protected $transportManager;

    public function __construct($endpoint)
    {
        $this->endpoint = $endpoint;
    }

    public function send(Swift_Mime_Message $message, &$failedRecipients = null)
    {
        if (!$this->endpoint) {
            throw new SlackException('Please provide a slack endpoint in your config file.');
        }

        $slack = new Client($this->endpoint);
        $config = config('services.slackmail');
        $storage = app('slackmail.storage')->create($message);
        $fields = app('slackmail.fields')->build($message);
        $body = '```'.preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", strip_tags($message->getBody())).'```';

        $message = $slack->to($config['channel'] ?? '#emails')
            ->from($config['from'] ?? 'Emails')
            ->withIcon(':envelope:')
            ->attach([
                'fallback' => trim($body, '`'),
                'text' => $body,
                'fields' => $fields,
                'title' => $message->getSubject(),
                'title_link' => $this->route($storage->getName()),
            ]);

        $message->send();
    }

    public function route($name)
    {
        return route(config('services.slackmail.routeName', 'slackmail'), $name);
    }
}
