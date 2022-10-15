<?php

declare(strict_types=1);

namespace Auth\Clients\Domain;

final class ClientScopes
{
    public function __construct(private array $values)
    {
    }

    /**
     * @return array
     */
    public function getValues(): array
    {
        return $this->values;
    }
}