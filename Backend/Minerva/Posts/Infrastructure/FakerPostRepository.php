<?php

declare(strict_types=1);

namespace Minerva\Posts\Infrastructure;

use Exception;
use Faker\Factory;
use Faker\Generator;
use Minerva\Posts\Domain\Dto\PostCreatorDto;
use Minerva\Posts\Domain\Post;
use Minerva\Posts\Domain\PostAuthor;
use Minerva\Posts\Domain\PostContent;
use Minerva\Posts\Domain\PostId;
use Minerva\Posts\Domain\PostRepository;
use Minerva\Posts\Domain\PostTitle;
use Minerva\Shared\Domain\ValueObject\Author\AuthorId;
use Minerva\Shared\Domain\ValueObject\Email;
use Minerva\Shared\Domain\ValueObject\Name;
use Minerva\Shared\Domain\ValueObject\Username;
use Minerva\Shared\Domain\ValueObject\Website;

final class FakerPostRepository implements PostRepository
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('es_ES');
    }

    /**
     * {@inheritDoc}
     */
    public function findAll(): array
    {
        return $this->toResponse();
    }

    /**
     * @return array<Post>
     *
     * @throws Exception
     */
    private function toResponse(): array
    {
        $posts = [];
        $limit = 10;

        for ($i = 0; $i < $limit; ++$i) {
            $posts[] = Post::create(
                new PostId((int) $this->faker->numerify()),
                new PostTitle($this->faker->realText(50)),
                new PostContent($this->faker->paragraph(random_int(1, 3))),
                PostAuthor::create(
                    new AuthorId((int) $this->faker->numerify()),
                    new Name($this->faker->name()),
                    new Username($this->faker->userName()),
                    new Website($this->faker->url()),
                    new Email($this->faker->email()),
                )
            );
        }

        return $posts;
    }

    public function save(PostCreatorDto $dto): bool
    {
        Post::create(
            new PostId(random_int(1, 100)),
            $dto->getTitle(),
            $dto->getContent(),
            PostAuthor::create(
                $dto->getAuthorId(),
                new Name($this->faker->name()),
                new Username($this->faker->userName()),
                new Website($this->faker->url()),
                new Email($this->faker->email()),
            )
        );

        return true;
    }
}
