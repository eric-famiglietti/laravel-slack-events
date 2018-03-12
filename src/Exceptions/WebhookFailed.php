<?php

namespace LaravelSlackEvents\Exceptions;

use Exception;

class WebhookFailed extends Exception
{
    public static function missingToken()
    {
        return new static('The request is missing the token field.');
    }

    public static function invalidToken()
    {
        return new static('The request\'s token field is invalid.');
    }

    public static function missingType()
    {
        return new static('The request is missing the type field.');
    }

    public static function missingChallenge()
    {
        return new static('The request is missing the challege field.');
    }

    public static function nonexistentJob()
    {
        return new static('The job class does not exist.');
    }

    public function render()
    {
        return response(['error' => $this->getMessage()], 400);
    }
}
