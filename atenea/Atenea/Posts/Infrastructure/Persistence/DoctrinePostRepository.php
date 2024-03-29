<?php

declare(strict_types=1);

namespace Atenea\Posts\Infrastructure\Persistence;

use Atenea\Posts\Domain\Post;
use Atenea\Posts\Domain\PostRepository;
use Atenea\Shared\Domain\Criteria\Criteria;
use Atenea\Shared\Infrastructure\Persistence\Doctrine\DoctrineCriteriaConverter;
use Atenea\Shared\Infrastructure\Persistence\DoctrineRepository;

final class DoctrinePostRepository extends DoctrineRepository implements PostRepository
{
    /**
     * @return array<int, Post>
     */
    public function findAll(): array
    {
        return $this->getRepository(Post::class)->findAll();
    }

    public function save(Post $post): void
    {
        $this->persist($post);
    }

    public function matching(Criteria $criteria): array
    {
        $criteriaToDoctrine = [
            'title' => 'title.value',
        ];

        $doctrineCriteria = DoctrineCriteriaConverter::convert($criteria, $criteriaToDoctrine);

        return $this->getRepository(Post::class)->matching($doctrineCriteria)->toArray();
    }
}
