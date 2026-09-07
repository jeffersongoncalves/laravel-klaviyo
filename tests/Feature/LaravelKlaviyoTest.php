<?php

use Illuminate\Support\Facades\Http;
use Jeffersongoncalves\LaravelKlaviyo\Facades\LaravelKlaviyo;
use Jeffersongoncalves\LaravelKlaviyo\LaravelKlaviyoException;

it('creates a profile', function () {
    Http::fake([
        '*/profiles/' => Http::response(['data' => ['id' => 'abc123', 'type' => 'profile']], 201),
    ]);

    $result = LaravelKlaviyo::createProfile(['email' => 'user@example.com']);

    expect($result['data']['id'])->toBe('abc123');

    Http::assertSent(fn ($request) => $request['data']['attributes']['email'] === 'user@example.com');
});

it('lists profiles', function () {
    Http::fake([
        '*/profiles/*' => Http::response(['data' => []], 200),
    ]);

    $result = LaravelKlaviyo::listProfiles();

    expect($result)->toBe(['data' => []]);
});

it('creates an event with a value', function () {
    Http::fake([
        '*/events/' => Http::response(['data' => ['id' => 'evt1']], 202),
    ]);

    LaravelKlaviyo::createEvent('Placed Order', 'user@example.com', ['order_id' => 123], value: 49.9);

    Http::assertSent(function ($request) {
        $properties = $request['data']['attributes']['properties'];

        return $properties['order_id'] === 123 && $properties['value'] === 49.9;
    });
});

it('throws on a failed response', function () {
    Http::fake([
        '*/lists/*' => Http::response(['errors' => [['detail' => 'Not found']]], 404),
    ]);

    LaravelKlaviyo::getList('missing');
})->throws(LaravelKlaviyoException::class, 'Klaviyo API error (404): Not found');
