<?php

declare(strict_types=1);

namespace App\Controller\Auth\Dto;

use Auth\Domain\AccessToken\AccessToken;

final class AccessTokenDto implements \JsonSerializable
{
    private function __construct(
        private readonly string $tokeType,
        private readonly int $expiresIn,
        private readonly string $accessToken,
        private readonly ?string $refreshToken,
    ) {
    }

    public static function fromDomain(AccessToken $accessToken): self
    {
        if (null !== $accessToken->getRefreshToken()) {
            $refreshToken = $accessToken->getRefreshToken();
        }

        return new self(
            $accessToken->getTokeType()->value,
            $accessToken->getExpiresIn(),
            $accessToken->getToken(),
            $refreshToken ?? null
        );
    }

    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    public function getTokeType(): string
    {
        return $this->tokeType;
    }

    public function getExpiresIn(): int
    {
        return $this->expiresIn;
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function jsonSerialize(): array
    {
        $result = [
            'access_token' => $this->getAccessToken(),
            'token_type' => $this->getTokeType(),
            'expires_in' => $this->getExpiresIn(),
        ];

        if ($this->getRefreshToken()) {
            $result['refresh_token'] = $this->getRefreshToken();
        }

        return $result;
    }
}
