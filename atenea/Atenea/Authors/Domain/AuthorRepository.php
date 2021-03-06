<?php

declare(strict_types=1);

namespace Atenea\Authors\Domain;

use Atenea\Shared\Domain\ValueObject\AuthorId;

interface AuthorRepository
{
    public function find(AuthorId $id): ?Author;
}
