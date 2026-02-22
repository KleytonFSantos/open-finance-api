<?php

namespace App\Assembler\Pluggy\Response;

use App\DTO\Presentation\Pluggy\Response\AccountsResponseDto;
use App\DTO\Presentation\Pluggy\Response\TransactionResponseDto;

class CreditCardTransactionFromAccountsAssembler
{
    public function __invoke(TransactionResponseDto $t, AccountsResponseDto $cardData): void
    {
        $t->setAccountType('CREDIT_CARD');
        $t->setAccountName($cardData->getName());
        $t->setAccountNumber($cardData->getNumber());

        $collection = $t->getCreditCardMetadata();
        if (!$collection->isEmpty()) {
            $meta = $collection->first();
            if ($meta->getInstallmentNumber() && $meta->getTotalInstallments()) {
                $installmentInfo = sprintf(
                    ' (%d/%d)',
                    $meta->getInstallmentNumber(),
                    $meta->getTotalInstallments()
                );
                $t->description .= $installmentInfo;
            }
        }
    }
}