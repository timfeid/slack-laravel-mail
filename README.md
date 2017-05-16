# Set up
## Installation
`composer install timfeid/laravel-slack-mail`

Add `TimFeid\SlackLaravelMail\SlackMailServiceProvider::class,` to your providers list in your `app.php` config.

Comment out `Illuminate\Mail\MailServiceProvider::class,` in your providers list.
```php
    // Illuminate\Mail\MailServiceProvider::class,
    TimFeid\SlackLaravelMail\SlackMailServiceProvider::class,
```

## Config
Add the following config to your `services.php` config file.
```php
    'slackmail' => [
        // The endpoint to your webhook
        'endpoint' => env('SLACKMAIL_ENDPOINT', 'https://hooks.slack.com/services/..../....'),
        // The driver for which you would like to store emails
        // Drivers are 'cache' and 'file' for now
        'driver' => env('SLACKMAIL_DRIVER', 'cache'),
        // Cache settings, will always use your default cache driver for now
        'cache' => [
            // Remove if you would not like to use cache()->tags()
            'tag' => 'slack-mail',
            // Time you want to keep emails for in minutes
            'ttl' => 60,
        ],
        'file' => [
            // File location you would like to save your emails
            'location' => storage_path('/mail'),
        ],
        // From user for your Slack message
        'from' => 'Emails',
        // Channel you'd like to send your messages to
        // @username to send private messages from @Slackbot
        'channel' => '#emails',
        // Fields you would like to show up in your message
        'fields' => [
            'subject',
            'to',
            'cc',
            'bcc',
            'from',
        ],
    ],
```

## Route
Add this route to your routes file. 
```php
    if (!app()->environment('production')) {
        Route::get('/slack-mail/{name}', '\TimFeid\SlackLaravelMail\Controllers\SlackMailController@slackMail')
            ->name('slackmail');
    }
```
