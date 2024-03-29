<?php

declare(strict_types=1);

namespace Atenea\Tests\Posts\Application;

use App\Tests\Atenea\Authors\Domain\AuthorMother;
use Atenea\Authors\Application\AuthorFinder;
use Atenea\Posts\Application\Create\CreatorPostCommandHandler;
use Atenea\Posts\Domain\PostRepository;
use Atenea\Shared\Domain\Exceptions\AuthorNotFoundException;
use Atenea\Shared\Domain\ValueObject\AuthorId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Zenstruck\Foundry\Test\Factories;

final class CreatorPostCommandHandlerTest extends TestCase
{
    use Factories;

    private MockObject $repositoryMock;
    private MockObject $authorFinderMock;

    protected function setUp(): void
    {
        $this->repositoryMock = $this->createMock(PostRepository::class);
        $this->authorFinderMock = $this->createMock(AuthorFinder::class);
    }

    /** @test */
    public function itShouldCreatePost(): void
    {
        // GIVEN
        $command = CreatorPostCommandMother::random();
        $authorId = new AuthorId($command->getAuthorId());
        $author = AuthorMother::fromId($authorId);

        $this->repositoryMock
            ->expects(self::once())
            ->method('save');

        $this->authorFinderMock
            ->expects(self::once())
            ->method('__invoke')
            ->with($authorId)
            ->willReturn($author);

        $commandHandler = new CreatorPostCommandHandler($this->repositoryMock, $this->authorFinderMock);

        // WHEN
        $commandHandler($command);
    }

    /** @test */
    public function itShouldNotCreatePost(): void
    {
        // GIVEN
        $command = CreatorPostCommandMother::random();
        $authorId = new AuthorId($command->getAuthorId());

        $this->repositoryMock
            ->expects(self::never())
            ->method('save');

        $this->authorFinderMock
            ->expects(self::once())
            ->method('__invoke')
            ->with($authorId)
            ->willThrowException(new AuthorNotFoundException($authorId->value()));

        $commandHandler = new CreatorPostCommandHandler($this->repositoryMock, $this->authorFinderMock);

        // THEN
        $this->expectException(AuthorNotFoundException::class);

        // WHEN
        $commandHandler($command);
    }
}
