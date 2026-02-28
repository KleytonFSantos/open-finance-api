<?php

namespace App\Finance\DTO\Presentation\Pluggy\Response;

use App\CreditCard\DTO\Presentation\Pluggy\Response\CreditCardMetadataResponseDto;

class TransactionResponseDto
{
    public string $id;
    public string $description;
    public string $descriptionRaw;
    public string $currencyCode;
    public float $amount;
    public ?float $amountInAccountCurrency;
    public string $date;
    public ?string $category;
    public ?string $categoryId;
    public ?float $balance;
    public string $accountId;
    public ?string $providerCode;
    public string $status;
    public ?array $paymentData;
    public string $type;
    public ?string $operationType;
    /** @var CreditCardMetadataResponseDto[]|null */
    public ?array $creditCardMetadata = [];
    public ?array $acquirerData;
    public ?array $merchant;
    public ?string $providerId;
    public ?int $order;
    public string $createdAt;
    public string $updatedAt;
    public ?string $accountType = null;
    public ?string $accountName = null;
    public ?string $accountNumber = null;

    public function getId(): string
    {
        return $this->id;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getDescriptionRaw(): string
    {
        return $this->descriptionRaw;
    }

    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getAmountInAccountCurrency(): ?float
    {
        return $this->amountInAccountCurrency;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function getCategoryId(): ?string
    {
        return $this->categoryId;
    }

    public function getBalance(): ?float
    {
        return $this->balance;
    }

    public function getAccountId(): string
    {
        return $this->accountId;
    }

    public function getProviderCode(): ?string
    {
        return $this->providerCode;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getPaymentData(): ?array
    {
        return $this->paymentData;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getOperationType(): ?string
    {
        return $this->operationType;
    }

    /**
     * @return CreditCardMetadataResponseDto[]|null
     */
    public function getCreditCardMetadata(): ?array
    {
        return $this->creditCardMetadata;
    }

    /**
     * @param CreditCardMetadataResponseDto[]|null $creditCardMetadata
     */
    public function setCreditCardMetadata(?array $creditCardMetadata): void
    {
        $this->creditCardMetadata = $creditCardMetadata;
    }

    public function getAcquirerData(): ?array
    {
        return $this->acquirerData;
    }

    public function getMerchant(): ?array
    {
        return $this->merchant;
    }

    public function getProviderId(): ?string
    {
        return $this->providerId;
    }

    public function getOrder(): ?int
    {
        return $this->order;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    public function getAccountType(): ?string
    {
        return $this->accountType;
    }

    public function setAccountType(?string $accountType): void
    {
        $this->accountType = $accountType;
    }

    public function getAccountName(): ?string
    {
        return $this->accountName;
    }

    public function setAccountName(string $accountName): void
    {
        $this->accountName = $accountName;
    }

    public function getAccountNumber(): ?string
    {
        return $this->accountNumber;
    }

    public function setAccountNumber(?string $accountNumber): void
    {
        $this->accountNumber = $accountNumber;
    }
}