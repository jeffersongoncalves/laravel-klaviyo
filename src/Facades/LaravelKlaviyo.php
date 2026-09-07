<?php

namespace Jeffersongoncalves\LaravelKlaviyo\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Jeffersongoncalves\LaravelKlaviyo\LaravelKlaviyo
 */
class LaravelKlaviyo extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-klaviyo';
    }
}
