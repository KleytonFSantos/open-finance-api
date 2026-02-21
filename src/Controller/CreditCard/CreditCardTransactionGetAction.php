<?php

namespace App\Controller\CreditCard;

use App\Interface\CreditCard\CreditCardTransactionServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

readonly class CreditCardTransactionGetAction
{
    public function __construct(
        private CreditCardTransactionServiceInterface $service
    ) {
    }

    #[Route('/credit-card/transactions', name: 'api_credit_card_transactions', methods: ['GET'])]
    public function __invoke(Request $request): JsonResponse
    {
        $from = $request->query->get('from');

        return new JsonResponse(
            $this->service->getTransactions($from),
            Response::HTTP_OK,
        );
    }
}