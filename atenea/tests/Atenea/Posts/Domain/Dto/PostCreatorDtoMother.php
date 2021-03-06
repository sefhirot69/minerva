<?php

declare(strict_types=1);

namespace Atenea\Tests\Posts\Domain\Dto;

use App\Tests\Atenea\Authors\Domain\AuthorMother;
use Atenea\Authors\Domain\Author;
use Atenea\Posts\Domain\PostId;
use Atenea\Tests\Posts\Domain\PostContentMother;
use Atenea\Tests\Posts\Domain\PostIdMother;
use Atenea\Tests\Posts\Domain\PostTitleMother;
use Atenea\Posts\Domain\Dto\PostCreatorDto;
use Atenea\Posts\Domain\PostContent;
use Atenea\Posts\Domain\PostTitle;

final class PostCreatorDtoMother
{
    public static function create(
        PostId $id,
        PostTitle $title,
        PostContent $content,
        Author $author
    ): PostCreatorDto {
        return PostCreatorDto::create($id, $title, $content, $author);
    }

    public static function random(): PostCreatorDto
    {
        return self::create(
            PostIdMother::random(),
            PostTitleMother::random(),
            PostContentMother::random(),
            AuthorMother::random(),
        );
    }
}
