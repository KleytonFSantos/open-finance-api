<?php

namespace App\CreditCard\DTO\Presentation\Pluggy\Response;

class DisaggregatedCreditLimitsResponseDto
{
    private ?float $usedAmount = null;
    private ?float $limitAmount = null;
    private ?float $availableAmount = null;
    private ?bool $isLimitFlexible = null;
    private ?string $consolidationType = null;
    private ?string $creditLineLimitType = null;
    private ?string $identificationNumber = null;
    private ?string $usedAmountCurrencyCode = null;
    private ?string $limitAmountCurrencyCode = null;
    private ?string $availableAmountCurrencyCode = null;
    private ?string $lineName = null;
    private ?string $lineNameAdditionalInfo = null;

    public function getUsedAmount(): ?float
    {
        return $this->usedAmount;
    }

    public function setUsedAmount(?float $usedAmount): void
    {
        $this->usedAmount = $usedAmount;
    }

    public function getLimitAmount(): ?float
    {
        return $this->limitAmount;
    }

    public function setLimitAmount(?float $limitAmount): void
    {
        $this->limitAmount = $limitAmount;
    }

    public function getAvailableAmount(): ?float
    {
        return $this->availableAmount;
    }

    public function setAvailableAmount(?float $availableAmount): void
    {
        $this->availableAmount = $availableAmount;
    }

    public function isLimitFlexible(): ?bool
    {
        return $this->isLimitFlexible;
    }

    public function setIsLimitFlexible(?bool $isLimitFlexible): void
    {
        $this->isLimitFlexible = $isLimitFlexible;
    }

    public function getConsolidationType(): ?string
    {
        return $this->consolidationType;
    }

    public function setConsolidationType(?string $consolidationType): void
    {
        $this->consolidationType = $consolidationType;
    }

    public function getCreditLineLimitType(): ?string
    {
        return $this->creditLineLimitType;
    }

    public function setCreditLineLimitType(?string $creditLineLimitType): void
    {
        $this->creditLineLimitType = $creditLineLimitType;
    }

    public function getIdentificationNumber(): ?string
    {
        return $this->identificationNumber;
    }

    public function setIdentificationNumber(?string $identificationNumber): void
    {
        $this->identificationNumber = $identificationNumber;
    }

    public function getUsedAmountCurrencyCode(): ?string
    {
        return $this->usedAmountCurrencyCode;
    }

    public function setUsedAmountCurrencyCode(?string $usedAmountCurrencyCode): void
    {
        $this->usedAmountCurrencyCode = $usedAmountCurrencyCode;
    }

    public function getLimitAmountCurrencyCode(): ?string
    {
        return $this->limitAmountCurrencyCode;
    }

    public function setLimitAmountCurrencyCode(?string $limitAmountCurrencyCode): void
    {
        $this->limitAmountCurrencyCode = $limitAmountCurrencyCode;
    }

    public function getAvailableAmountCurrencyCode(): ?string
    {
        return $this->availableAmountCurrencyCode;
    }

    public function setAvailableAmountCurrencyCode(?string $availableAmountCurrencyCode): void
    {
        $this->availableAmountCurrencyCode = $availableAmountCurrencyCode;
    }

    public function getLineName(): ?string
    {
        return $this->lineName;
    }

    public function setLineName(?string $lineName): void
    {
        $this->lineName = $lineName;
    }

    public function getLineNameAdditionalInfo(): ?string
    {
        return $this->lineNameAdditionalInfo;
    }

    public function setLineNameAdditionalInfo(?string $lineNameAdditionalInfo): void
    {
        $this->lineNameAdditionalInfo = $lineNameAdditionalInfo;
    }
}