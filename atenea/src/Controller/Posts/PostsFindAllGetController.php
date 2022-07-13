<?php

declare(strict_types=1);

namespace App\Controller\Posts;

use Atenea\Posts\Domain\Post;
use Atenea\Posts\Domain\PostAuthor;
use Atenea\Posts\Domain\PostContent;
use Atenea\Posts\Domain\PostId;
use Atenea\Posts\Domain\PostTitle;
use Atenea\Shared\Domain\ValueObject\Author\AuthorId;
use Atenea\Shared\Domain\ValueObject\Email;
use Atenea\Shared\Domain\ValueObject\Name;
use Atenea\Shared\Domain\ValueObject\Username;
use Atenea\Shared\Domain\ValueObject\Website;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Atenea\Posts\Application\FindAllPostQueryHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class PostsFindAllGetController extends AbstractController
{
    public function __construct(
        private FindAllPostQueryHandler $queryHandler,
        private EntityManagerInterface $entityManager
    ) {
    }

    #[Route('/posts/all', name: 'posts_find_all', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        try {
            // TODO BREAKING CHANGE!
            $post = $this->entityManager
                ->getRepository(Post::class)
                ->find(new PostId(1));

//            $post = Post::create(
//                new PostId(2),
//                new PostTitle('Hello world!'),
//                new PostContent('Hi people'),
//                PostAuthor::create(
//                    new AuthorId(2),
//                    new Name('Tests'),
//                    new Username('Test6'),
//                    new Website('https://google.es'),
//                    new Email('test@test.es'),
//                )
//            );
//
//            $this->entityManager->persist($post);
//            $this->entityManager->flush();

            $result = ($this->queryHandler)();

            return $this->json(['data' => $result->getPosts()], Response::HTTP_OK);
        } catch (Exception $exception) {
            return $this->json(['error' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
