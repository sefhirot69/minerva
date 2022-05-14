<?php

declare(strict_types=1);

namespace Minerva\Tests\Shared\Domain\ValueObject\Author;

use Minerva\Shared\Domain\ValueObject\Author\AuthorId;

final class AuthorIdMother
{
    public static function create(int $value): AuthorId
    {
        return new AuthorId($value);
    }

    public static function random(): AuthorId
    {
        return self::create(random_int(1, 10));
    }
}