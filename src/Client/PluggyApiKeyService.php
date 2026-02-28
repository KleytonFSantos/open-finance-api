<?php

namespace App\Client;

use App\DTO\Presentation\Pluggy\Response\ApiKeyResponseDto;
use Symfony\Component\Serializer\SerializerInterface;

readonly class PluggyApiKeyService
{
    public function __construct(
        private string $clientId,
        private string $clientSecret,
        private PluggyClient $client,
        private SerializerInterface $serializer
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

        /** @var ApiKeyResponseDto $response */
        $response = $this->serializer->deserialize(
            $request->getContent(),
            ApiKeyResponseDto::class,
            'json'
        );

        return $response->getApiKey();
    }
}