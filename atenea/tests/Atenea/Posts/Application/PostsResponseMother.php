<?php

declare(strict_types=1);

namespace Atenea\Tests\Posts\Application;

use Atenea\Posts\Application\PostResponse;
use Atenea\Posts\Application\PostsResponse;

final class PostsResponseMother
{
    /**
     * @param array<PostResponse> $posts
     */
    public static function create(array $posts): PostsResponse
    {
        return PostsResponse::create($posts);
    }

    public static function random(): PostsResponse
    {
        $postResponse = [];
        $limit = random_int(1, 10);

        for ($i = 0; $i < $limit; ++$i) {
            $postResponse[] = PostResponseMother::random();
        }

        return PostsResponse::create($postResponse);
    }
}
