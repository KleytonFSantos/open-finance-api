<?php

namespace App\Interface\CreditCard;

interface CreditCardTransactionServiceInterface
{
    public function getTransactions(?string $from = null): array;
}