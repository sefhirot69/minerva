<?php

declare(strict_types=1);

namespace Auth\Clients\Domain;

use Auth\Clients\Domain\ValueObjects\ClientIdentifier;
use Ramsey\Uuid\UuidInterface;

interface ClientFindRepository
{
    public function find(UuidInterface $id): Client;

    public function findByIdentifier(ClientIdentifier $identifier): Client;
}
