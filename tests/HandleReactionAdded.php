<?php

namespace LaravelSlackEvents\Tests;

class HandleReactionAdded
{
    public $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }
}
