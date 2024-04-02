<?php

declare(strict_types=1);

namespace Crm\ValueObject;

class AccessTokenOptions
{
    public function __construct(
        private readonly string $accessToken,
        private readonly string $refreshToken,
        private readonly int $expiresIn,
    ) {
    }

    public function toAccessTokenOptions(): array
    {
        return [
            'access_token' => $this->accessToken,
            'refresh_token' => $this->refreshToken,
            'expires_in' => $this->expiresIn,
        ];
    }
}
