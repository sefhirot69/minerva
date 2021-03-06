<?php

declare(strict_types=1);

namespace Atenea\Tests\Posts\Domain;

use Atenea\Tests\Shared\Domain\MotherCreator;
use Atenea\Posts\Domain\PostContent;

final class PostContentMother
{
    public static function create(string $value): PostContent
    {
        return new PostContent($value);
    }

    public static function random(): PostContent
    {
        return self::create(
            MotherCreator::random()->paragraph(random_int(1, 3))
        );
    }
}
