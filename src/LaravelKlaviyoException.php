<?php

namespace Jeffersongoncalves\LaravelKlaviyo;

use Exception;
use Illuminate\Http\Client\Response;

class LaravelKlaviyoException extends Exception
{
    public static function fromResponse(Response $response): self
    {
        $message = $response->json('errors.0.detail') ?? $response->body();

        return new self("Klaviyo API error ({$response->status()}): {$message}", $response->status());
    }
}
