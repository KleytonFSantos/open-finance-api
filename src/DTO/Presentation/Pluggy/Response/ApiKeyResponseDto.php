<?php

namespace App\DTO\Presentation\Pluggy\Response;

class ApiKeyResponseDto
{
    private ?string $apiKey;

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function setApiKey(string $apiKey): void
    {
        $this->apiKey = $apiKey;
    }
}