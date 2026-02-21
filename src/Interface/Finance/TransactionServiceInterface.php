<?php

namespace App\Interface\Finance;

interface TransactionServiceInterface
{
    public function getTransactions(?string $from = null): array;

}