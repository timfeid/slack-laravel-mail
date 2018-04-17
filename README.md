
# Set up
## Installation
`composer require timfeid/slack-laravel-mail`

Add `TimFeid\SlackLaravelMail\SlackMailServiceProvider::class,` to your providers list in your `app.php` config.

```php
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
        'channel' => env('SLACKMAIL_TO', '#emails'),
        // Fields you would like to show up in your message
        'fields' => [
            'subject',
            'to',
            'cc',
            'bcc',
            'from',
            'attachments',
        ],
    ],
```

### Dot File (.env)
As you can probably tell, .env is encouraged for a per-environment setup
```
    SLACKMAIL_ENDPOINT="https://hooks.slack.com/services..."
    SLACKMAIL_DRIVER="cache"
    SLACKMAIL_TO="@username"
```

## Route
Add this route to your routes file. The route can be set up however you'd like, but it *must* have `slackmail` as the name `->name('slackmail')`
```php
    if (!app()->environment('production')) {
        Route::get('/slack-mail/{name}', '\TimFeid\SlackLaravelMail\Controllers\SlackMailController@slackMail')
            ->name('slackmail');
    }
```


### Extending the `fields`
You'll want to create service provider that extends `SlackMailServiceProvider` and overwrite the `registerSlackFields` method.
```php
<?php

namespace App\Providers;

use App\Services\Slack\SlackFields;
use TimFeid\SlackLaravelMail\SlackMailServiceProvider as BaseProvider;

class SlackMailServiceProvider extends BaseProvider
{
    public function registerSlackFields()
    {
        $this->app->singleton('slackmail.fields', SlackFields::class);
    }
}

```

#### Example SlackFields class
```php
<?php

namespace App\Services\Slack;

use TimFeid\SlackLaravelMail\SlackFields as BaseSlackFields;

class SlackFields extends BaseSlackFields
{
    public function buildSendgridField()
    {
        return [
            'title' => 'Sendgrid Headers',
            'value' => '```'.json_encode([
                'categories' => $this->message->getCategories(),
                'custom_args' => $this->message->getArguments(),
            ], JSON_PRETTY_PRINT).'```',
        ];
    }
}

```
