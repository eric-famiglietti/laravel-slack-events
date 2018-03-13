# Laravel Slack Events

[![Packagist](https://img.shields.io/packagist/v/eric-famiglietti/laravel-slack-events.svg?style=flat-square)](https://packagist.org/packages/eric-famiglietti/laravel-slack-events)
[![Travis](https://img.shields.io/travis/eric-famiglietti/laravel-slack-events.svg?style=flat-square)](https://travis-ci.org/eric-famiglietti/laravel-slack-events)
[![StyleCI](https://styleci.io/repos/124814706/shield?branch=master)](https://styleci.io/repos/124814706)


Laravel package for integrating with the [Slack Events API](https://api.slack.com/events-api).

## Introduction

This package makes it easy to set up a webhook in a Laravel application for receiving requests from Slack through the [Slack Events API](https://api.slack.com/events-api). The package handles token authentication and URL verification and leaves the specific handling of events up to you. Events can be handled through jobs or event listeners.

Before proceeding, it's recommended that you familiarize yourself with the [Slack Events API](https://api.slack.com/events-api) and the available [Event Types](https://api.slack.com/events/api) so you can properly set up Slack to communicate with your application.

## Requirements

- PHP 7.1 or above
- Laravel 5.6

**NOTE:** These requirements are artificially high. The package could work with earlier versions of Laravel but has only been tested with Laravel 5.6.

## Installation

Install the latest version using [Composer](https://getcomposer.org/):

```
$ composer require eric-famiglietti/laravel-slack-events
```

Publish the configuration file using:

```
$ php artisan vendor:publish --provider="LaravelSlackEvents\SlackEventsServiceProvider" --tag="config"
```

## Usage

### Setup

The package requires a `SLACK_EVENTS_TOKEN` environmental variable to be set. The *Verification Token* can be found by on your Slack application's dashboard ([https://api.slack.com/apps/<app_id>](https://api.slack.com/apps/<app_id>)), under the *App Credentials* section.

For the purpose of this guide, the webhook URL that we will use is `https://app.tld/slack/events`.

First, create the webhook route using the route macro:

```php
Route::slackEventsWebhook('slack/events');
```

Next, add the route to the `$except` array of the `VerifyCsrfToken` middleware:

```php
protected $except = [
    'slack/events',
];
```

Once you have created the webhook, configure Slack to send event requests to the webhook. This is done on the Event Subscriptions page ([https://api.slack.com/apps/<app_id>/event-subscriptions](https://api.slack.com/apps/<app_id>/event-subscriptions)) of your Slack application's dashboard.

If your application is publically accessible, you will begin to event requests to the webhook. Events can be handled through jobs or event listeners.

### Jobs

Jobs can be executed when events are triggered. For example, to dispatch a job when a `reaction_added` is received, add the following to `config/slack-events.php`:

```php
'jobs' => [
    'reaction_added' => \App\Jobs\SlackEvents\HandleReactionAdded::class,
],
```

Jobs will receive the event payload through their constructor:

```php
namespace App\Jobs\SlackEvents;

class HandleReactionAdded
{
    public $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }
    
    public function handle()
    {
        // ...
    }
}

```

### Events

Laravel event listeners can handle Slack event requests. For example, to listen to a `reaction_added` event, add an element to the `$listen` property of your `EventServiceProvider`:

```php
protected $listen = [
    'slack-events::reaction_added' => [
        App\Listeners\SlackEvents\ReactionAddedListener::class,
    ],
];
```

The associated event listener would look like:

```php
namespace App\Listeners\SlackEvents;

class ReactionAddedListener
{
    public function handle(array $payload)
    {
        // ...
    }
}

```

## Testing

Run the included tests using:

```
$ vendor/bin/phpunit
```

## Credits

Special thanks to the [ohdearapp/laravel-ohdear-webhooks](https://github.com/ohdearapp/laravel-ohdear-webhooks) package, which this package was heavily based on.

## License

This package is provided under the MIT License (MIT). See the [license file](LICENSE) for more information.
