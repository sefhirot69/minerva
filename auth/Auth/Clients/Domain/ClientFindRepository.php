<?php

declare(strict_types=1);

namespace Auth\Clients\Domain;

use Ramsey\Uuid\UuidInterface;

interface ClientFindRepository
{
    public function find(UuidInterface $id): Client;
}
