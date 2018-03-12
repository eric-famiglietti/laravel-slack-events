<?php

namespace LaravelSlackEvents\Middlewares;

use Closure;
use LaravelSlackEvents\Exceptions\WebhookFailed;

class VerifyToken
{
    public function handle($request, Closure $next)
    {
        if (! $request->has('token')) {
            throw WebhookFailed::missingToken();
        }

        if ($request->token !== config('slack-events.token')) {
            throw WebhookFailed::invalidToken();
        }

        return $next($request);
    }
}
