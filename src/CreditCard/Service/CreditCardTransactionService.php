<?php

namespace App\CreditCard\Service;

use App\Account\Interface\AccountTransactionServiceInterface;
use App\CreditCard\Assembler\Pluggy\Response\CreditCardTransactionFromAccountsAssembler;
use App\Account\Client\PluggyApiKeyService;
use App\Account\Client\PluggyClient;
use App\CreditCard\Interface\CreditCardTransactionServiceInterface;
use App\Finance\DTO\Presentation\Pluggy\Response\TransactionResponseDto;
use DateTime;
use Symfony\Component\Serializer\SerializerInterface;

final class CreditCardTransactionService implements CreditCardTransactionServiceInterface
{
    const string CREDIT_CARD_ACCOUNT_TYPE = 'CREDIT_CARD';

    private string $apiKey;

    public function __construct(
        private readonly AccountTransactionServiceInterface $accountTransactionService,
        private readonly PluggyApiKeyService $pluggyApiKeyService,
        private readonly PluggyClient $pluggyClient,
        private readonly SerializerInterface $serializer,
        private readonly CreditCardTransactionFromAccountsAssembler $creditCardTransactionAssembler
    ) {
        $this->apiKey = $this->pluggyApiKeyService->get();
    }

    public function getTransactions(?string $from = null): array
    {
        $creditCards = array_filter(
            $this->accountTransactionService->getAllAccounts(),
            fn($account) => $account->getSubtype() === self::CREDIT_CARD_ACCOUNT_TYPE
        );

        if (empty($creditCards)) {
            return [];
        }

        $fromDate = new DateTime($from ?? 'now');
        $toDate = (clone $fromDate)->modify('last day of this month');
        $formattedTo = $toDate->format('Y-m-d');

        $allTransactions = [];

        foreach ($creditCards as $cardData) {
            $results = $this->fetchTransactions($cardData->getId(), $from, $formattedTo);
            $transactions = $this->deserializeTransactions($results);

            foreach ($transactions as $t) {
                ($this->creditCardTransactionAssembler)($t, $cardData);
            }

            array_push($allTransactions, ...$transactions);
        }

        return $allTransactions;
    }

    private function fetchTransactions(string $accountId, ?string $from, string $to): array
    {
        $response = $this->pluggyClient->get('/transactions', [
            'headers' => [
                'X-API-KEY' => $this->apiKey,
                'accept'    => 'application/json',
            ],
            'query' => [
                'accountId' => $accountId,
                'from'      => $from,
                'to'        => $to,
            ],
        ]);

        $results = $response->toArray()['results'] ?? [];

        return array_map(function (array $item) {
            if (isset($item['creditCardMetadata'])) {
                $item['creditCardMetadata'] = [$item['creditCardMetadata']];
            }
            return $item;
        }, $results);
    }

    private function deserializeTransactions(array $results): array
    {
        return $this->serializer->deserialize(
            json_encode($results),
            TransactionResponseDto::class . '[]',
            'json'
        );
    }
}