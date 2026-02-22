<?php

namespace App\Service\Finance;

use App\Client\PluggyApiKeyService;
use App\Client\PluggyClient;
use App\DTO\Presentation\Pluggy\Response\TransactionResponseDto;
use App\Interface\Finance\TransactionServiceInterface;
use Symfony\Component\Serializer\SerializerInterface;

readonly class TransactionService implements TransactionServiceInterface
{
    public function __construct(
        private string $accountId,
        private PluggyApiKeyService $pluggyApiKeyService,
        private PluggyClient $pluggyClient,
        private SerializerInterface $serializer
    ) {
    }

    public function getTransactions(?string $from = null): array
    {
        $apiKey = $this->pluggyApiKeyService->get();

        $response = $this->pluggyClient->get('/transactions', [
            'headers' => [
                'X-API-KEY' => $apiKey,
                'accept' => 'application/json',
            ],
            'query' => [
                'accountId' => $this->accountId,
                'from' => $from,
            ],
        ]);

        return $this->serializer->deserialize(
            json_encode($response->toArray()['results'] ?? []),
            TransactionResponseDto::class . '[]',
            'json'
        );
    }
}