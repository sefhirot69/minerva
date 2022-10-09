<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\AuthClient;
use App\Repository\AuthClientRepository;
use Exception;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class TokenController extends AbstractController
{

    public function __construct(private readonly AuthClientRepository $authClientRepository)
    {
    }

    /**
     * @throws Exception
     */
    #[Route('/auth/token')]
    public function __invoke(): JsonResponse
    {
        
        return $this->json('token', Response::HTTP_OK);
    }
}