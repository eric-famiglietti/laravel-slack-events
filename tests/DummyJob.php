<?php

namespace LaravelSlackEvents\Tests;

class DummyJob
{
    public $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }
}
