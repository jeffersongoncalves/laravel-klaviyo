<?php

namespace Jeffersongoncalves\LaravelKlaviyo;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class LaravelKlaviyo
{
    protected function client(): PendingRequest
    {
        return Http::baseUrl(config('klaviyo.base_url'))
            ->withHeaders([
                'Authorization' => 'Klaviyo-API-Key '.config('klaviyo.api_key'),
                'revision' => config('klaviyo.api_revision'),
            ])
            ->acceptJson()
            ->asJson();
    }

    protected function request(string $method, string $path, ?array $body = null): array
    {
        $response = $this->client()->send($method, $path, $body ? ['json' => $body] : []);

        if ($response->failed()) {
            throw LaravelKlaviyoException::fromResponse($response);
        }

        return $response->json() ?? [];
    }

    // Profiles

    public function listProfiles(array $query = []): array
    {
        return $this->request('GET', '/profiles/?'.http_build_query($query));
    }

    public function getProfile(string $id): array
    {
        return $this->request('GET', "/profiles/{$id}/");
    }

    public function createProfile(array $attributes): array
    {
        return $this->request('POST', '/profiles/', [
            'data' => ['type' => 'profile', 'attributes' => $attributes],
        ]);
    }

    public function updateProfile(string $id, array $attributes): array
    {
        return $this->request('PATCH', "/profiles/{$id}/", [
            'data' => ['type' => 'profile', 'id' => $id, 'attributes' => $attributes],
        ]);
    }

    // Lists

    public function listLists(array $query = []): array
    {
        return $this->request('GET', '/lists/?'.http_build_query($query));
    }

    public function getList(string $id): array
    {
        return $this->request('GET', "/lists/{$id}/");
    }

    public function createList(string $name): array
    {
        return $this->request('POST', '/lists/', [
            'data' => ['type' => 'list', 'attributes' => ['name' => $name]],
        ]);
    }

    public function deleteList(string $id): array
    {
        return $this->request('DELETE', "/lists/{$id}/");
    }

    public function addProfilesToList(string $id, array $profileIds): array
    {
        return $this->request('POST', "/lists/{$id}/relationships/profiles/", [
            'data' => array_map(fn ($profileId) => ['type' => 'profile', 'id' => $profileId], $profileIds),
        ]);
    }

    public function removeProfilesFromList(string $id, array $profileIds): array
    {
        return $this->request('DELETE', "/lists/{$id}/relationships/profiles/", [
            'data' => array_map(fn ($profileId) => ['type' => 'profile', 'id' => $profileId], $profileIds),
        ]);
    }

    // Events

    public function listEvents(array $query = []): array
    {
        return $this->request('GET', '/events/?'.http_build_query($query));
    }

    public function getEvent(string $id): array
    {
        return $this->request('GET', "/events/{$id}/");
    }

    public function createEvent(string $metric, string $email, array $properties = [], ?float $value = null): array
    {
        return $this->request('POST', '/events/', [
            'data' => ['type' => 'event', 'attributes' => [
                'metric' => ['data' => ['type' => 'metric', 'attributes' => ['name' => $metric]]],
                'profile' => ['data' => ['type' => 'profile', 'attributes' => ['email' => $email]]],
                'properties' => $value !== null ? [...$properties, 'value' => $value] : $properties,
                'time' => now()->toIso8601String(),
            ]],
        ]);
    }

    // Campaigns

    public function listCampaigns(array $query = []): array
    {
        return $this->request('GET', '/campaigns/?'.http_build_query($query));
    }

    public function getCampaign(string $id): array
    {
        return $this->request('GET', "/campaigns/{$id}/");
    }

    // Flows

    public function listFlows(array $query = []): array
    {
        return $this->request('GET', '/flows/?'.http_build_query($query));
    }

    public function getFlow(string $id): array
    {
        return $this->request('GET', "/flows/{$id}/");
    }

    public function updateFlow(string $id, string $status): array
    {
        return $this->request('PATCH', "/flows/{$id}/", [
            'data' => ['type' => 'flow', 'id' => $id, 'attributes' => ['status' => $status]],
        ]);
    }

    // Metrics

    public function listMetrics(array $query = []): array
    {
        return $this->request('GET', '/metrics/?'.http_build_query($query));
    }

    public function getMetric(string $id): array
    {
        return $this->request('GET', "/metrics/{$id}/");
    }

    // Segments

    public function listSegments(array $query = []): array
    {
        return $this->request('GET', '/segments/?'.http_build_query($query));
    }

    public function getSegment(string $id): array
    {
        return $this->request('GET', "/segments/{$id}/");
    }

    // Templates

    public function listTemplates(array $query = []): array
    {
        return $this->request('GET', '/templates/?'.http_build_query($query));
    }

    public function getTemplate(string $id): array
    {
        return $this->request('GET', "/templates/{$id}/");
    }
}
