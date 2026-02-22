<?php

namespace App\Client;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

readonly class PluggyClient
{
    public function __construct(
        private string $baseUrl
    ) {
    }

    public function getClient(): HttpClientInterface
    {
        return HttpClient::createForBaseUri($this->baseUrl, [
            'headers' => [
                'accept' => 'application/json',
                'content-type' => 'application/json',
            ],
        ]);
    }

    public function get(string $resource, ?array $options = []): ResponseInterface
    {
        return $this->getClient()->request('GET', $resource, $options);
    }

    public function post(string $resource, ?array $options = []): ResponseInterface
    {
        return $this->getClient()->request('POST', $resource, $options);
    }
}