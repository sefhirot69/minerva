<?php

declare(strict_types=1);

namespace Auth\Domain\User;

use Auth\Shared\Domain\Aggregate\AggregateRoot;
use Ramsey\Uuid\UuidInterface;

final class User extends AggregateRoot implements UserInterface
{

    private function __construct(
        private readonly UuidInterface $id,
        private readonly Email $email,
        private readonly Password $password,
        private readonly array $roles,
        private readonly bool $active,
    ) {
    }

    public static function create(
        UuidInterface $id,
        Email $email,
        Password $plainPassword,
        array $roles,
    ): self {
        return new self(
            $id,
            $email,
            $plainPassword,
            $roles,
            true,
        );
    }

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @return Email
     */
    public function getEmail(): Email
    {
        return $this->email;
    }

    /**
     * @return Password
     */
    public function getPassword(): Password
    {
        return $this->password;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    public function eraseCredentials() : void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->getId()->toString();
    }
}
