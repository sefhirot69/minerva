<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Persistence\Repository;

use Auth\Domain\Token\Token;
use Auth\Domain\Token\TokenFindRepository;
use Auth\Shared\Domain\Exception\NotFoundException;
use Auth\Shared\Infrastructure\Persistence\Doctrine\DoctrineRepository;
use Ramsey\Uuid\UuidInterface;

final class DoctrineTokenFindRepository extends DoctrineRepository implements TokenFindRepository
{
    /**
     * {@inheritDoc}
     */
    public function find(UuidInterface $id): ?Token
    {
        return $this->repository(Token::class)->find($id);
    }

    /**
     * {@inheritDoc}
     */
    public function findOrFail(UuidInterface $id): Token
    {
        $token = $this->find($id);

        if (null === $token) {
            throw NotFoundException::entityWithId(Token::class, $id);
        }

        return $token;
    }
}
