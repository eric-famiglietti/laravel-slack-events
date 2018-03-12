<?php

namespace LaravelSlackEvents;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use LaravelSlackEvents\Middlewares\VerifyToken;
use LaravelSlackEvents\Exceptions\WebhookFailed;

class SlackEventsController extends Controller
{
    public function __construct()
    {
        $this->middleware(VerifyToken::class);
    }

    public function __invoke(Request $request)
    {
        if (! $request->has('type')) {
            throw WebhookFailed::missingType();
        }

        if ($request->type === 'url_verification') {
            return $this->handleUrlVerification($request);
        }

        $type = $request->event['type'];
        $payload = $request->all();

        event("slack-events::{$type}", $payload);

        $job = config("slack-events.jobs.{$type}", null);

        if (is_null($job)) {
            return;
        }

        if (! class_exists($job)) {
            throw WebhookFailed::nonexistentJob();
        }

        dispatch(new $job($payload));
    }

    private function handleUrlVerification(Request $request)
    {
        if (! $request->has('challenge')) {
            throw WebhookFailed::missingChallenge();
        }

        return $request->challenge;
    }
}
