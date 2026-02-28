<?php

namespace App\Finance\Interface;

interface TransactionServiceInterface
{
    public function getTransactions(?string $from = null): array;

}