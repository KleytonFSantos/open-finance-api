<?php

namespace App\Service\CreditCard;

use App\Client\PluggyApiKeyService;
use App\Client\PluggyClient;
use App\DTO\Presentation\Pluggy\Response\AccountsResponseDto;
use App\Interface\CreditCard\CreditCardTransactionServiceInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class CreditCardTransactionService implements CreditCardTransactionServiceInterface
{
    private HttpClientInterface $client;
    private string $apiKey;

    public function __construct(
        private readonly string $itemId,
        private readonly PluggyApiKeyService $pluggyApiKeyService,
        private readonly PluggyClient $pluggyClient,
        private readonly SerializerInterface $serializer
    ) {
        $this->client = $this->pluggyClient->getClient();
        $this->apiKey = $this->pluggyApiKeyService->get();
    }

    public function getTransactions(?string $from = null): array
    {
        $allAccounts = $this->getAllAccounts();
        $creditCards = [];

        foreach ($allAccounts as $account) {
            if (($account->type ?? '') === 'CREDIT_CARD' || ($account->subtype ?? '') === 'CREDIT_CARD') {
                $creditCards[] = $account;
            }
        }

        $allTransactions = [];

        foreach ($creditCards as $cardData) {
            $query = [
                'accountId' => $cardData->id,
            ];

            if ($from) {
                $query['from'] = $from;

                $fromDate = new \DateTime($from);
                $toDate = (clone $fromDate)->modify('last day of this month');
                $query['to'] = $toDate->format('Y-m-d');

            }

            try {
                $response = $this->client->request('GET', '/transactions', [
                    'headers' => [
                        'X-API-KEY' => $this->apiKey,
                        'accept' => 'application/json',
                    ],
                    'query' => $query,
                ]);

                $transactions = $response->toArray()['results'] ?? [];

                foreach ($transactions as &$t) {
                    $t['accountType'] = 'CREDIT_CARD';
                    $t['accountName'] = $cardData->name;
                    $t['accountNumber'] = $cardData->number;
                    $t['creditCardMetadata'] = $cardData->creditData ?? null;
                    
                    if (isset($t['creditCardMetadata']['installmentNumber']) && isset($t['creditCardMetadata']['totalInstallments'])) {
                        $installmentInfo = sprintf(
                            ' (%d/%d)', 
                            $t['creditCardMetadata']['installmentNumber'], 
                            $t['creditCardMetadata']['totalInstallments']
                        );
                        $t['description'] .= $installmentInfo;
                    }
                }

                $allTransactions = array_merge($allTransactions, $transactions);
            } catch (\Exception $e) {
                continue;
            }
        }

        return $allTransactions;
    }

    /**
     * @return AccountsResponseDto[]
     */
    private function getAllAccounts(): array
    {
        try {
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
        } catch (\Exception $e) {
            return [];
        }
    }
}