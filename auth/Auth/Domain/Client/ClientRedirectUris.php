<?php

declare(strict_types=1);

namespace Auth\Domain\Client;

use Auth\Shared\Domain\ValueObject\ArrayValues;

final class ClientRedirectUris implements ArrayValues
{
    public function __construct(private array $values)
    {
        $this->ensureIsValidUrl($values);
    }

    public function getValues(): array
    {
        return $this->values;
    }

    private function ensureIsValidUrl(array $values): void
    {
        foreach ($values as $value) {
            if (!filter_var($value, FILTER_VALIDATE_URL)) {
                throw new \InvalidArgumentException(sprintf('The redirectUris <%s> is not valid', $value));
            }
        }
    }
}
