<?php

namespace App\Client;

readonly class PluggyApiKeyService
{
    public function __construct(
        private string $clientId,
        private string $clientSecret,
        private PluggyClient $client
    ) {
    }

    public function get(): string
    {
        $request = $this->client->post('/auth', [
            'body' => [
                'clientId' => $this->clientId,
                'clientSecret' => $this->clientSecret,
            ]
        ]);

        return json_decode($request->getContent(), 1)['apiKey'];
    }
}