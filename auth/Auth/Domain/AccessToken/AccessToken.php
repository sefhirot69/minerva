<?php

declare(strict_types=1);

namespace Auth\Domain\AccessToken;

use Auth\Domain\RefreshToken\RefreshToken;

final class AccessToken
{
    private function __construct(
        private readonly TokeType $tokeType,
        private readonly \DateTimeImmutable $expiresIn,
        private readonly string $token,
        private readonly ?string $refreshToken = null,
    ) {
    }

    public static function create(
        TokeType $tokeType,
        \DateTimeImmutable $expiresIn,
        string $token,
    ): self {
        return new self(
            $tokeType,
            $expiresIn,
            $token,
            null
        );
    }

    public static function createWithRefreshToken(
        TokeType $tokeType,
        \DateTimeImmutable $expiresIn,
        string $token,
        string $refreshToken,
    ): self {
        return new self(
            $tokeType,
            $expiresIn,
            $token,
            $refreshToken
        );
    }

    /**
     * @return RefreshToken|null
     */
    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    public function getTokeType(): TokeType
    {
        return $this->tokeType;
    }

    public function getExpiresIn(): int
    {
        return $this->expiresIn->getTimestamp();
    }

    public function getToken(): string
    {
        return $this->token;
    }
}
