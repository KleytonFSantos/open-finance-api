<?php

namespace App\Service\Account;

use App\Client\PluggyApiKeyService;
use App\Client\PluggyClient;
use App\DTO\Presentation\Pluggy\Response\AccountsResponseDto;
use App\Interface\Account\AccountTransactionServiceInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AccountTransactionService implements AccountTransactionServiceInterface
{
    private HttpClientInterface $client;
    private string $apiKey;

    public function __construct(
        private readonly string $itemId,
        private readonly PluggyApiKeyService $pluggyApiKeyService,
        private readonly PluggyClient $pluggyClient,
        private readonly SerializerInterface $serializer,
    ) {
        $this->client = $this->pluggyClient->getClient();
        $this->apiKey = $this->pluggyApiKeyService->get();
    }

    public function getAllAccounts(): array
    {
        $response = $this->client->request('GET', '/accounts', [
            'headers' => [
                'X-API-KEY' => $this->apiKey,
                'accept' => 'application/json',
            ],
            'query' => ['itemId' => $this->itemId],
        ]);

        return $this->serializer->deserialize(
            json_encode($response->toArray()['results'] ?? []),
            AccountsResponseDto::class . '[]',
            'json'
        );
    }
}