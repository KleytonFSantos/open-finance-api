<?php

namespace App\DTO\Presentation\Pluggy\Response;

class AccountsResponseDto
{
    public function __construct(
        public string $id,
        public string $type,
        public string $subtype,
        public string $name,
        public float $balance,
        public string $currencyCode,
        public string $itemId,
        public string $number,
        public string $createdAt,
        public string $updatedAt,
        public ?string $marketingName,
        public ?string $taxNumber,
        public string $owner,
        public ?array $bankData,
        public ?array $creditData
    ) {
    }
}