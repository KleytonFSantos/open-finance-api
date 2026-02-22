<?php

namespace App\DTO\Presentation\Pluggy\Response;

class AccountsResponseDto
{
    public string $id;
    public string $type;
    public string $subtype;
    public string $name;
    public float $balance;
    public string $currencyCode;
    public string $itemId;
    public string $number;
    public string $createdAt;
    public string $updatedAt;
    public ?string $marketingName;
    public ?string $taxNumber;
    public string $owner;
    public ?array $bankData;
    public ?array $creditData;

    public function getId(): string
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getSubtype(): string
    {
        return $this->subtype;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }

    public function getItemId(): string
    {
        return $this->itemId;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    public function getMarketingName(): ?string
    {
        return $this->marketingName;
    }

    public function getTaxNumber(): ?string
    {
        return $this->taxNumber;
    }

    public function getOwner(): string
    {
        return $this->owner;
    }

    public function getBankData(): ?array
    {
        return $this->bankData;
    }

    public function getCreditData(): ?array
    {
        return $this->creditData;
    }
}