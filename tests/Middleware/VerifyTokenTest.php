<?php

namespace LaravelSlackEvents\Tests\Middlewares;

use Illuminate\Support\Facades\Route;
use LaravelSlackEvents\Tests\TestCase;

class VerifyTokenTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        Route::slackEventsWebhook('slack/events');
    }

    public function test_it_fails_when_the_token_is_missing()
    {
        $response = $this->post('slack/events');

        $response
            ->assertStatus(400)
            ->assertSee('The request is missing the token field.');
    }

    public function test_it_fails_when_the_token_is_invalid()
    {
        $response = $this->post('slack/events', ['token' => 'invalid_token']);

        $response
            ->assertStatus(400)
            ->assertSee('The request\'s token field is invalid.');
    }
}
