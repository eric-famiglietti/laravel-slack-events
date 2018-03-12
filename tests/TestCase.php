<?php

namespace LaravelSlackEvents\Tests;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    // Dummy values from https://api.slack.com/events-api...
    protected $challenge = '3eZbrw1aBm2rZgRNFdxV2595E9CY3gmdALWMmHkvFXO7tYXAYM8P';
    protected $token = 'Jhj5dZrVaK7ZwHHjRyZWjbDl';

    protected function getEnvironmentSetUp($app)
    {
        config(['slack-events.token' => $this->token]);
    }

    protected function getPackageProviders($app)
    {
        return ['LaravelSlackEvents\SlackEventsServiceProvider'];
    }
}
