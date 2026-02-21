<?php

namespace App\Client;

use Symfony\Component\HttpClient\HttpClient;

class PluggyApiKeyService
{
    public function __construct(
        private string $clientId,
        private string $clientSecret,
        private PluggyClient $client
    ) {
    }

    public function get(): string
    {
        $client = $this->client->getClient();
        $request = $client->request('POST', '/auth', [
            'body' => [
                'clientId' => $this->clientId,
                'clientSecret' => $this->clientSecret,
            ]
        ]);

        return json_decode($request->getContent(), 1)['apiKey'];
    }
}