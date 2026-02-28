<?php

namespace App\Controller\Finance;

use App\Interface\Finance\TransactionServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

readonly class FinanceGetAction
{
    public function __construct(
        private TransactionServiceInterface $service,
    ) {
    }

    #[Route('/finance', name: 'api_finance', methods: ['GET'])]
    public function __invoke(Request $request): JsonResponse
    {
        $from = $request->query->get('from');

        return new JsonResponse(
            $this->service->getTransactions(from: $from),
            Response::HTTP_OK,
        );
    }
}