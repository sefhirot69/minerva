<?php

declare(strict_types=1);

namespace Auth\Clients\Infrastructure\Persistence\Doctrine\Mapping;

use Doctrine\DBAL\Types\JsonType;

final class TokenScopesType extends JsonType
{
    private const NAME = 'token_scopes';

    public function getName(): string
    {
        return self::NAME;
    }
}