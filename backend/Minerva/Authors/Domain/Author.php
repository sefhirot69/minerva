<?php

declare(strict_types=1);

namespace Minerva\Authors\Domain;

use Minerva\Shared\Domain\ValueObject\Author\AuthorId;
use Minerva\Shared\Domain\ValueObject\Email;
use Minerva\Shared\Domain\ValueObject\Name;
use Minerva\Shared\Domain\ValueObject\Username;
use Minerva\Shared\Domain\ValueObject\Website;

final class Author
{
    private function __construct(
        private AuthorId $id,
        private Name $name,
        private Username $username,
        private Website $web,
        private Email $email
    ) {
    }

    public static function create(
        AuthorId $id,
        Name $name,
        Username $username,
        Website $web,
        Email $email
    ): self {
        return new self(
            $id,
            $name,
            $username,
            $web,
            $email
        );
    }

    public function getId(): AuthorId
    {
        return $this->id;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function getUsername(): Username
    {
        return $this->username;
    }

    public function getWeb(): Website
    {
        return $this->web;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }
}