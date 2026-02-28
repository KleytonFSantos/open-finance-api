<?php

namespace App\CreditCard\Interface;

interface CreditCardTransactionServiceInterface
{
    public function getTransactions(?string $from = null): array;
}