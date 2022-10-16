<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use App\Entity\AuthClient;
use App\Repository\AuthClientRepository;
use Auth\Clients\Application\Create\CreateClientCommand;
use Auth\Clients\Application\Create\CreateClientCommandHandler;
use Auth\Clients\Domain\Client;
use Auth\Clients\Domain\ClientCredentialsParam;
use Auth\Clients\Domain\ClientFindRepository;
use Auth\Clients\Domain\ClientGrants;
use Auth\Clients\Domain\ClientIdentifier;
use Auth\Clients\Domain\ClientName;
use Auth\Clients\Domain\ClientRedirectUris;
use Auth\Clients\Domain\ClientSaveRepository;
use Auth\Clients\Domain\ClientScopes;
use Auth\Clients\Domain\ClientSecret;
use Exception;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CreateClientController extends AbstractController
{
    public function __construct(
        private readonly CreateClientCommandHandler $commandHandler,
    ) {
    }

    /**
     * @throws Exception
     */
    #[Route('/auth/client', methods: ['POST'])]
    public function __invoke(Request $request): JsonResponse
    {
        $request = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $command = CreateClientCommand::create(
            new ClientName($request['name']),
            new ClientGrants($request['grant'])
        );
        $client = ($this->commandHandler)($command);

        return $this->json($client, Response::HTTP_OK);
    }
}