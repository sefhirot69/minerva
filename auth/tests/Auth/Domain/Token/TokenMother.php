<?php

namespace Tests\Auth\Domain\Token;

use Auth\Domain\Client\Client;
use Auth\Domain\Token\Token;
use Auth\Domain\User\User;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Tests\Auth\Domain\Client\ClientMother;
use Tests\Auth\Domain\User\UserMother;
use Tests\Auth\Shared\Domain\MotherFactory;
use Tests\Auth\Shared\Domain\UuidMother;

class TokenMother
{
    public static function create(
        UuidInterface $id,
        Client $client,
        DateTimeImmutable $expiry,
        bool $revoked,
        array $scopes = [],
        ?User $user = null,
    ): Token {
        return Token::create(
            $id,
            $client,
            $expiry,
            $revoked,
            $scopes,
            $user
        );
    }

    public static function createWithUser(
        UuidInterface $id,
        Client $client,
        DateTimeImmutable $expiry,
        bool $revoked,
        User $user,
        array $scopes = [],
    ): Token {
        return Token::createWithUser(
            $id,
            $client,
            $expiry,
            $user,
            $revoked,
            $scopes,
        );
    }

    public static function random(): Token
    {
        return self::create(
            UuidMother::random(),
            ClientMother::random(),
            DateTimeImmutable::createFromMutable(MotherFactory::random()->dateTime()),
            MotherFactory::random()->randomElement([true, false]),
            [],
        );
    }

    public static function randomWithUser(): Token
    {
        return self::create(
            UuidMother::random(),
            ClientMother::random(),
            DateTimeImmutable::createFromMutable(MotherFactory::random()->dateTime()),
            MotherFactory::random()->randomElement([true, false]),
            [],
            UserMother::random()
        );
    }
}