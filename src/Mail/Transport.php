<?php

namespace TimFeid\SlackLaravelMail\Mail;

use Swift_Mime_Message;
use TimFeid\Slack\Client;
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

        $trans = app('slackmail.transport')->create($message);

        // $random_name = uniqid('email', true);
        $subject = $message->getSubject();

        // file_put_contents(storage_path($random_name), $message->getBody());
        $fields = [
            [
                'title' => 'Subject',
                'value' => $subject,
                'short' => true,
            ],
            [
                'title' => 'To',
                'value' => implode(', ', array_keys($message->getTo() ?: [])),
                'short' => true,
            ],
            [
                'title' => 'Cc',
                'value' => implode(', ', array_keys($message->getCc() ?: [])),
                'short' => true,
            ],
            [
                'title' => 'Bcc',
                'value' => implode(', ', array_keys($message->getBcc() ?: [])),
                'short' => true,
            ],
            [
                'title' => 'From',
                'value' => implode(', ', array_keys($message->getFrom() ?: [])),
                'short' => true,
            ],
        ];

        $message = '```'.preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", strip_tags($message->getBody())).'```';

        $message = $slack->attach([
            'fallback' => trim($message, '`'),
            'text' => $message,
            'fields' => $fields,
            'title' => $subject,
            'title_link' => url('/email-preview/'.$trans->getName()),
        ])
            ->to('@timfeid')
            ->from('Dev Emails')
            ->withIcon(':envelope:');

        var_dump($slack->getMessagePayload($message));
        $message->send();
    }

    public function register()
    {

    }
}
