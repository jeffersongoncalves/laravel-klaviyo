<?php

namespace Jeffersongoncalves\LaravelKlaviyo\Tests;

use Jeffersongoncalves\LaravelKlaviyo\LaravelKlaviyoServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            LaravelKlaviyoServiceProvider::class,
        ];
    }
}
