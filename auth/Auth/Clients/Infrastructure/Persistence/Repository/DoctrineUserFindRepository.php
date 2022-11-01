<?php

declare(strict_types=1);

namespace Auth\Clients\Infrastructure\Persistence\Repository;

use Auth\Clients\Domain\User\User;
use Auth\Clients\Domain\User\UserFindRepository;
use Auth\Shared\Infrastructure\Persistence\Doctrine\DoctrineRepository;
use Ramsey\Uuid\UuidInterface;

final class DoctrineUserFindRepository extends DoctrineRepository implements UserFindRepository
{

    public function find(UuidInterface $id): ?User
    {
        // TODO: Implement find() method.
    }

    public function findOneByEmailOrFail(string $email): User
    {
        $userFound = $this->repository(User::class)->findOneBy(['email' => $email]);

        if(null === $userFound) {
            throw new \RuntimeException('User not found');
        }

        return $userFound;
    }
}
