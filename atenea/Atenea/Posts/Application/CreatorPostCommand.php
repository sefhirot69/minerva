<?php

declare(strict_types=1);

namespace Atenea\Posts\Application;

final class CreatorPostCommand
{
    private function __construct(
        private string $title,
        private string $content,
        private string $authorId
    ) {
    }

    public static function fromPrimitive(
        string $title,
        string $content,
        string $authorId
    ): self {
        return new self(
            $title,
            $content,
            $authorId
        );
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getAuthorId(): string
    {
        return $this->authorId;
    }
}
