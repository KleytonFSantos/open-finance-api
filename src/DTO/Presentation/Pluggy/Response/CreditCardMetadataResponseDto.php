<?php

namespace App\DTO\Presentation\Pluggy\Response;

class CreditCardMetadataResponseDto
{
    private ?string $cardNumber = null;
    private ?string $purchaseDate = null;
    private ?int $totalInstallments = null;
    private ?int $installmentNumber = null;
    private ?int $payeeMCC = null;
    private ?string $level = null;
    private ?string $brand = null;
    private ?string $balanceCloseDate = null;
    private ?string $balanceDueDate = null;
    private ?float $availableCreditLimit = null;
    private ?float $balanceForeignCurrency = null;
    private ?float $minimumPayment = null;
    private ?float $creditLimit = null;
    private ?bool $isLimitFlexible = null;
    private ?string $holderType = null;
    private ?string $status = null;
    /** @var DisaggregatedCreditLimitsResponseDto[]|null */
    private ?array $disaggregatedCreditLimits = null;
    private ?array $additionalCards = null;

    public function getCardNumber(): ?string
    {
        return $this->cardNumber;
    }

    public function setCardNumber(?string $cardNumber): void
    {
        $this->cardNumber = $cardNumber;
    }

    public function getPurchaseDate(): ?string
    {
        return $this->purchaseDate;
    }

    public function setPurchaseDate(?string $purchaseDate): void
    {
        $this->purchaseDate = $purchaseDate;
    }

    public function getTotalInstallments(): ?int
    {
        return $this->totalInstallments;
    }

    public function setTotalInstallments(?int $totalInstallments): void
    {
        $this->totalInstallments = $totalInstallments;
    }

    public function getInstallmentNumber(): ?int
    {
        return $this->installmentNumber;
    }

    public function setInstallmentNumber(?int $installmentNumber): void
    {
        $this->installmentNumber = $installmentNumber;
    }

    public function getPayeeMCC(): ?int
    {
        return $this->payeeMCC;
    }

    public function setPayeeMCC(?int $payeeMCC): void
    {
        $this->payeeMCC = $payeeMCC;
    }

    public function getLevel(): ?string
    {
        return $this->level;
    }

    public function setLevel(?string $level): void
    {
        $this->level = $level;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(?string $brand): void
    {
        $this->brand = $brand;
    }

    public function getBalanceCloseDate(): ?string
    {
        return $this->balanceCloseDate;
    }

    public function setBalanceCloseDate(?string $balanceCloseDate): void
    {
        $this->balanceCloseDate = $balanceCloseDate;
    }

    public function getBalanceDueDate(): ?string
    {
        return $this->balanceDueDate;
    }

    public function setBalanceDueDate(?string $balanceDueDate): void
    {
        $this->balanceDueDate = $balanceDueDate;
    }

    public function getAvailableCreditLimit(): ?float
    {
        return $this->availableCreditLimit;
    }

    public function setAvailableCreditLimit(?float $availableCreditLimit): void
    {
        $this->availableCreditLimit = $availableCreditLimit;
    }

    public function getBalanceForeignCurrency(): ?float
    {
        return $this->balanceForeignCurrency;
    }

    public function setBalanceForeignCurrency(?float $balanceForeignCurrency): void
    {
        $this->balanceForeignCurrency = $balanceForeignCurrency;
    }

    public function getMinimumPayment(): ?float
    {
        return $this->minimumPayment;
    }

    public function setMinimumPayment(?float $minimumPayment): void
    {
        $this->minimumPayment = $minimumPayment;
    }

    public function getCreditLimit(): ?float
    {
        return $this->creditLimit;
    }

    public function setCreditLimit(?float $creditLimit): void
    {
        $this->creditLimit = $creditLimit;
    }

    public function isLimitFlexible(): ?bool
    {
        return $this->isLimitFlexible;
    }

    public function setIsLimitFlexible(?bool $isLimitFlexible): void
    {
        $this->isLimitFlexible = $isLimitFlexible;
    }

    public function getHolderType(): ?string
    {
        return $this->holderType;
    }

    public function setHolderType(?string $holderType): void
    {
        $this->holderType = $holderType;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return DisaggregatedCreditLimitsResponseDto[]|null
     */
    public function getDisaggregatedCreditLimits(): ?array
    {
        return $this->disaggregatedCreditLimits;
    }

    /**
     * @param DisaggregatedCreditLimitsResponseDto[]|null $disaggregatedCreditLimits
     */
    public function setDisaggregatedCreditLimits(?array $disaggregatedCreditLimits): void
    {
        $this->disaggregatedCreditLimits = $disaggregatedCreditLimits;
    }

    public function getAdditionalCards(): ?array
    {
        return $this->additionalCards;
    }

    public function setAdditionalCards(?array $additionalCards): void
    {
        $this->additionalCards = $additionalCards;
    }
}