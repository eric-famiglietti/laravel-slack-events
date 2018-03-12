# Laravel Slack Events

[![Build Status](https://travis-ci.org/eric-famiglietti/laravel-slack-events.svg?branch=master)](https://travis-ci.org/eric-famiglietti/laravel-slack-events) [![StyleCI](https://styleci.io/repos/124814706/shield?branch=master)](https://styleci.io/repos/124814706)

Laravel package for integrating with the [Slack Events API](https://api.slack.com/events-api).

## Introduction

This package makes it easy to set up a webhook in a Laravel application for receiving requests from Slack through the [Slack Events API](https://api.slack.com/events-api). The package handles token authentication and URL verification and leaves the specific handling of events up to you. Events can be handled through Laravel job classes or event listeners.

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
php artisan vendor:publish --provider="LaravelSlackEvents\SlackEventsServiceProvider" --tag="config"
```

## Usage

### Setup

For the purpose of this guide, the webhook URL that we want is `https://app.tld/slack/events`.

You will now have a `slack-events.php` file in your `config` directory. The package requires a `SLACK_EVENTS_TOKEN` environmental variable to be set. The *Verification Token* can be found on your Slack application dashboard ([https://api.slack.com/apps/<app_id>](https://api.slack.com/apps/<app_id>)), under the *App Credentials* section.

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

### Jobs

In `config/slack-events.php`, configure any jobs to execute when associated events are triggered:

```php
'jobs' => [
    'member_joined_channel' => \App\Jobs\SlackEvents\HandleMemberJoinedChannel::class,
    'reaction_added' => \App\Jobs\SlackEvents\HandleReactionAdded::class,
],
```

### Events

## Testing

Run the included tests using:

```
$ vendor/bin/phpunit
```

## Credits

Special thanks to the [ohdearapp/laravel-ohdear-webhooks](https://github.com/ohdearapp/laravel-ohdear-webhooks) package, which this package was heavily based on.

## License

This package is provided under the MIT License (MIT). See the [license file](https://github.com/eric-famiglietti/laravel-slack-events/blob/master/LICENSE) for more information.
