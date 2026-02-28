<?php

namespace App\CreditCard\Assembler\Pluggy\Response;

use App\Account\DTO\Presentation\Pluggy\Response\AccountsResponseDto;
use App\Finance\DTO\Presentation\Pluggy\Response\TransactionResponseDto;

class CreditCardTransactionFromAccountsAssembler
{
    public function __invoke(TransactionResponseDto $t, AccountsResponseDto $cardData): void
    {
        $t->setAccountType('CREDIT_CARD');
        $t->setAccountName($cardData->getName());
        $t->setAccountNumber($cardData->getNumber());

        $array = $t->getCreditCardMetadata();
        if (!empty($array)) {
            $meta = $array[0];
            if (!empty($meta['ínstallmentNumber']) && !empty($meta['totalInstallments'])) {
                $installmentInfo = sprintf(
                    ' (%d/%d)',
                    $meta['ínstallmentNumber'],
                    $meta['totalInstallments']
                );
                $t->description .= $installmentInfo;
            }
        }
    }
}