<?php

namespace LaravelSlackEvents\Tests;

use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;

class SlackEventsControllerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        Event::fake();

        Bus::fake();

        Route::slackEventsWebhook('slack/events');
    }

    public function test_it_fails_when_the_type_is_missing()
    {
        $response = $this->post('slack/events', [
            'token' => $this->token,
        ]);

        $response
            ->assertStatus(400)
            ->assertSee('The request is missing the type field.');
    }

    public function test_it_fails_when_the_challenge_is_missing()
    {
        $response = $this->post('slack/events', [
            'token' => $this->token,
            'type' => 'url_verification',
        ]);

        $response
            ->assertStatus(400)
            ->assertSee('The request is missing the challege field.');
    }

    public function test_it_returns_the_challenge()
    {
        $response = $this->post('slack/events', [
            'challenge' => $this->challenge,
            'token' => $this->token,
            'type' => 'url_verification',
        ]);

        $response
            ->assertStatus(200)
            ->assertSee($this->challenge);
    }

    public function test_it_triggers_an_event()
    {
        $this->post('slack/events', [
            'event' => ['type' => 'reaction_added'],
            'token' => $this->token,
            'type' => 'event_callback',
        ])->assertSuccessful();

        Event::assertDispatched('slack-events::reaction_added', function ($event, $payload) {
            return $payload['event']['type'] === 'reaction_added';
        });
    }

    public function test_it_fails_when_the_job_does_not_exist()
    {
        config(['slack-events.jobs' => ['reaction_added' => Foo::class]]);

        $response = $this->post('slack/events', [
            'event' => ['type' => 'reaction_added'],
            'token' => $this->token,
            'type' => 'event_callback',
        ]);

        $response
            ->assertStatus(400)
            ->assertSee('The job class does not exist.');
    }

    public function test_it_dispatches_the_job()
    {
        config(['slack-events.jobs' => ['reaction_added' => HandleReactionAdded::class]]);

        $this->post('slack/events', [
            'event' => ['type' => 'reaction_added'],
            'token' => $this->token,
            'type' => 'event_callback',
        ])->assertSuccessful();

        Bus::assertDispatched(HandleReactionAdded::class, function ($job) {
            return $job->payload['event']['type'] === 'reaction_added';
        });
    }
}
