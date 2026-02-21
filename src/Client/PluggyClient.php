<?php

namespace App\Client;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

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
}