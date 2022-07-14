<?php

declare(strict_types=1);

namespace Atenea\Posts\Application;

use DateTimeImmutable;
use JsonSerializable;
use ReturnTypeWillChange;

final class PostResponse implements JsonSerializable
{
    private function __construct(
        private readonly int $id,
        private readonly string $title,
        private readonly string $content,
        private readonly PostAuthorResponse $author
    ) {
    }

    public static function create(
        int $id,
        string $title,
        string $content,
        PostAuthorResponse $author
    ): self {
        return new self($id, $title, $content, $author);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getAuthor(): PostAuthorResponse
    {
        return $this->author;
    }

    #[ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
